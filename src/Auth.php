<?php

namespace MoodTune;

use PDO;

class Auth
{
    private static $currentUser = null;

    public static function register(string $username, string $email, string $password, string $fullName = ''): array
    {
        try {
            $pdo = Database::getConnection();

            // Validate input
            if (strlen($username) < 3) {
                throw new \Exception('Username must be at least 3 characters');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Invalid email format');
            }

            if (strlen($password) < 6) {
                throw new \Exception('Password must be at least 6 characters');
            }

            // Check if user exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                throw new \Exception('Username or email already exists');
            }

            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $stmt = $pdo->prepare("
                INSERT INTO users (username, email, password, full_name) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$username, $email, $hashedPassword, $fullName]);

            $userId = $pdo->lastInsertId();

            return [
                'success' => true,
                'message' => 'Registration successful!',
                'user_id' => $userId
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public static function login(string $username, string $password): array
    {
        try {
            $pdo = Database::getConnection();

            // Find user
            $stmt = $pdo->prepare("
                SELECT id, username, email, password, full_name, role, avatar_url 
                FROM users 
                WHERE (username = ? OR email = ?) AND is_active = TRUE
            ");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();

            if (!$user) {
                throw new \Exception('Invalid credentials');
            }

            // Verify password
            if (!password_verify($password, $user['password'])) {
                throw new \Exception('Invalid credentials');
            }

            // Update last login
            $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);

            // Set session
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            unset($user['password']);

            return [
                'success' => true,
                'message' => 'Login successful!',
                'user' => $user
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public static function logout(): void
    {
        session_destroy();
        $_SESSION = [];
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public static function getCurrentUser(): ?array
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        if (self::$currentUser === null) {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                SELECT id, username, email, full_name, role, avatar_url, created_at, last_login
                FROM users 
                WHERE id = ?
            ");
            $stmt->execute([$_SESSION['user_id']]);
            self::$currentUser = $stmt->fetch();
        }

        return self::$currentUser;
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: /login.php');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            die('Access denied');
        }
    }

    public static function getUserMoodHistory(int $userId, int $limit = 10): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            SELECT detected_mood, energy_level, confidence_score, created_at
            FROM mood_history
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$userId, $limit]);
        return $stmt->fetchAll();
    }

    public static function getUserStats(int $userId): array
    {
        $pdo = Database::getConnection();
        
        // Total sessions
        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM mood_history WHERE user_id = ?");
        $stmt->execute([$userId]);
        $total = $stmt->fetch()['total'];

        // Most common mood
        $stmt = $pdo->prepare("
            SELECT detected_mood, COUNT(*) as count 
            FROM mood_history 
            WHERE user_id = ? 
            GROUP BY detected_mood 
            ORDER BY count DESC 
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        $mostCommon = $stmt->fetch();

        // Average confidence
        $stmt = $pdo->prepare("
            SELECT AVG(confidence_score) as avg_confidence 
            FROM mood_history 
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $avgConfidence = $stmt->fetch()['avg_confidence'];

        return [
            'total_sessions' => $total,
            'most_common_mood' => $mostCommon['detected_mood'] ?? 'N/A',
            'mood_count' => $mostCommon['count'] ?? 0,
            'avg_confidence' => round($avgConfidence ?? 0, 2)
        ];
    }
}