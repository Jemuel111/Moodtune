<?php

require_once __DIR__ . '/../vendor/autoload.php';

use MoodTune\Database;
use MoodTune\BehaviorAnalyzer;
use MoodTune\MoodClassifier;
use MoodTune\MusicRecommender;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? $_GET['action'] ?? '';

        switch ($action) {
            case 'analyze':
                $rawData = json_decode(file_get_contents('php://input'), true);
                
                if (!$rawData) {
                    throw new Exception('Invalid input data');
                }

                // Generate or get session ID
                $sessionId = session_id();
                Database::saveSession($sessionId);

                // Analyze behavior features
                $features = BehaviorAnalyzer::analyzeFeatures($rawData);

                // Classify mood using Machine Learning
                $classifier = new MoodClassifier();
                $mood = $classifier->predict($features);

                // Save behavior data
                Database::saveBehaviorData($sessionId, $features, $mood['type']);

                // Get music recommendations
                $songs = MusicRecommender::getRecommendations($mood['type']);
                $formattedSongs = MusicRecommender::formatRecommendations($songs);

                // Get mood information
                $moodInfo = MoodClassifier::getMoodInfo($mood['type']);

                echo json_encode([
                    'success' => true,
                    'mood' => array_merge($mood, $moodInfo),
                    'recommendations' => $formattedSongs,
                    'sessionId' => $sessionId
                ]);
                break;

            case 'feedback':
                $data = json_decode(file_get_contents('php://input'), true);
                $sessionId = session_id();
                
                Database::saveFeedback(
                    $sessionId,
                    $data['predictedMood'],
                    $data['actualMood'] ?? null,
                    $data['rating']
                );

                echo json_encode(['success' => true, 'message' => 'Feedback saved']);
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