<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use MoodTune\Database;
use MoodTune\BehaviorAnalyzer;
use MoodTune\MoodClassifier;
use MoodTune\MusicRecommender;
use MoodTune\Auth;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
        $action = $_POST['action'] ?? $_GET['action'] ?? '';

        switch ($action) {
            case 'analyze':
                // Require login for analysis
                if (!Auth::isLoggedIn()) {
                    throw new Exception('Please login to analyze your mood');
                }

                $rawData = json_decode(file_get_contents('php://input'), true);
                
                if (!$rawData) {
                    throw new Exception('Invalid input data');
                }

                $user = Auth::getCurrentUser();
                $sessionId = session_id();
                
                Database::saveSession($sessionId);

                // Analyze behavior features
                $features = BehaviorAnalyzer::analyzeFeatures($rawData);

                // Classify mood using Machine Learning
                $classifier = new MoodClassifier();
                $mood = $classifier->predict($features);

                // Save behavior data with user ID
                Database::saveBehaviorData($sessionId, $features, $mood['type']);

                // Save to mood history
                $pdo = Database::getConnection();
                $stmt = $pdo->prepare("
                    INSERT INTO mood_history 
                    (user_id, session_id, detected_mood, energy_level, confidence_score, 
                     mouse_speed, click_rate, typing_speed, total_interactions) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $user['id'],
                    $sessionId,
                    $mood['type'],
                    $mood['energy'],
                    $mood['confidence'],
                    $features['avgMouseSpeed'],
                    $features['clickRate'],
                    $features['avgTypingInterval'],
                    $features['totalInteractions']
                ]);

                // Get music recommendations
                $songs = MusicRecommender::getRecommendations($mood['type']);
                $formattedSongs = MusicRecommender::formatRecommendations($songs);

                // Get mood information
                $moodInfo = MoodClassifier::getMoodInfo($mood['type']);

                echo json_encode([
                    'success' => true,
                    'mood' => array_merge($mood, $moodInfo),
                    'recommendations' => $formattedSongs,
                    'sessionId' => $sessionId,
                    'user' => [
                        'id' => $user['id'],
                        'username' => $user['username']
                    ]
                ]);
                break;

            case 'feedback':
                if (!Auth::isLoggedIn()) {
                    throw new Exception('Please login to submit feedback');
                }

                $data = json_decode(file_get_contents('php://input'), true);
                $user = Auth::getCurrentUser();
                $sessionId = session_id();
                
                Database::saveFeedback(
                    $sessionId,
                    $data['predictedMood'],
                    $data['actualMood'] ?? null,
                    $data['rating']
                );

                echo json_encode(['success' => true, 'message' => 'Feedback saved']);
                break;

            case 'toggle_favorite':
                if (!Auth::isLoggedIn()) {
                    throw new Exception('Please login to save favorites');
                }

                $data = json_decode(file_get_contents('php://input'), true);
                $user = Auth::getCurrentUser();
                $musicId = $data['music_id'] ?? null;

                if (!$musicId) {
                    throw new Exception('Music ID is required');
                }

                $pdo = Database::getConnection();
                
                // Check if already favorited
                $stmt = $pdo->prepare("SELECT id FROM user_favorites WHERE user_id = ? AND music_id = ?");
                $stmt->execute([$user['id'], $musicId]);
                $exists = $stmt->fetch();

                if ($exists) {
                    // Remove favorite
                    $stmt = $pdo->prepare("DELETE FROM user_favorites WHERE user_id = ? AND music_id = ?");
                    $stmt->execute([$user['id'], $musicId]);
                    $isFavorite = false;
                } else {
                    // Add favorite
                    $stmt = $pdo->prepare("INSERT INTO user_favorites (user_id, music_id) VALUES (?, ?)");
                    $stmt->execute([$user['id'], $musicId]);
                    $isFavorite = true;
                }

                echo json_encode([
                    'success' => true,
                    'is_favorite' => $isFavorite,
                    'message' => $isFavorite ? 'Added to favorites' : 'Removed from favorites'
                ]);
                break;

            case 'get_history':
                if (!Auth::isLoggedIn()) {
                    throw new Exception('Please login to view history');
                }

                $user = Auth::getCurrentUser();
                $limit = $_GET['limit'] ?? 50;
                $offset = $_GET['offset'] ?? 0;

                $pdo = Database::getConnection();
                $stmt = $pdo->prepare("
                    SELECT * FROM mood_history 
                    WHERE user_id = ? 
                    ORDER BY created_at DESC 
                    LIMIT ? OFFSET ?
                ");
                $stmt->execute([$user['id'], $limit, $offset]);
                $history = $stmt->fetchAll();

                // Get total count
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM mood_history WHERE user_id = ?");
                $stmt->execute([$user['id']]);
                $total = $stmt->fetch()['total'];

                echo json_encode([
                    'success' => true,
                    'history' => $history,
                    'total' => $total
                ]);
                break;

            case 'get_favorites':
                if (!Auth::isLoggedIn()) {
                    throw new Exception('Please login to view favorites');
                }

                $user = Auth::getCurrentUser();
                $pdo = Database::getConnection();
                
                $stmt = $pdo->prepare("
                    SELECT ml.*, uf.created_at as favorited_at
                    FROM user_favorites uf
                    JOIN music_library ml ON uf.music_id = ml.id
                    WHERE uf.user_id = ?
                    ORDER BY uf.created_at DESC
                ");
                $stmt->execute([$user['id']]);
                $favorites = $stmt->fetchAll();

                echo json_encode([
                    'success' => true,
                    'favorites' => $favorites
                ]);
                break;

            case 'update_profile':
                if (!Auth::isLoggedIn()) {
                    throw new Exception('Please login to update profile');
                }

                $data = json_decode(file_get_contents('php://input'), true);
                $user = Auth::getCurrentUser();
                $pdo = Database::getConnection();

                $fullName = $data['full_name'] ?? $user['full_name'];
                $email = $data['email'] ?? $user['email'];

                // Validate email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception('Invalid email format');
                }

                // Check if email is taken by another user
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt->execute([$email, $user['id']]);
                if ($stmt->fetch()) {
                    throw new Exception('Email already taken');
                }

                // Update profile
                $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
                $stmt->execute([$fullName, $email, $user['id']]);

                echo json_encode([
                    'success' => true,
                    'message' => 'Profile updated successfully'
                ]);
                break;

            case 'change_password':
                if (!Auth::isLoggedIn()) {
                    throw new Exception('Please login to change password');
                }

                $data = json_decode(file_get_contents('php://input'), true);
                $user = Auth::getCurrentUser();
                $pdo = Database::getConnection();

                $currentPassword = $data['current_password'] ?? '';
                $newPassword = $data['new_password'] ?? '';

                // Verify current password
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$user['id']]);
                $userPassword = $stmt->fetch()['password'];

                if (!password_verify($currentPassword, $userPassword)) {
                    throw new Exception('Current password is incorrect');
                }

                if (strlen($newPassword) < 6) {
                    throw new Exception('New password must be at least 6 characters');
                }

                // Update password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashedPassword, $user['id']]);

                echo json_encode([
                    'success' => true,
                    'message' => 'Password changed successfully'
                ]);
                break;

            default:
                throw new Exception('Invalid action');
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}