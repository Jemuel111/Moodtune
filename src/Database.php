<?php

namespace MoodTune;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../config/database.php';

            try {
                $dsn = sprintf(
                    "mysql:host=%s;dbname=%s;charset=%s",
                    $config['host'],
                    $config['database'],
                    $config['charset']
                );

                self::$connection = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                throw new PDOException("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }

    public static function saveSession(string $sessionId): void
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("INSERT IGNORE INTO sessions (session_id) VALUES (?)");
        $stmt->execute([$sessionId]);
    }

    public static function saveBehaviorData(string $sessionId, array $data, string $mood): void
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO behavior_data 
            (session_id, mouse_speed, click_rate, typing_speed, mouse_variance, total_interactions, detected_mood) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $sessionId,
            $data['avgMouseSpeed'],
            $data['clickRate'],
            $data['avgTypingInterval'],
            $data['mouseVariance'],
            $data['totalInteractions'],
            $mood
        ]);
    }

    public static function getMusicByMood(string $mood): array
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("
            SELECT title, artist, genre, emoji, energy_level 
            FROM music_library 
            WHERE mood_category = ? 
            ORDER BY RAND() 
            LIMIT 4
        ");
        $stmt->execute([$mood]);
        return $stmt->fetchAll();
    }

    public static function saveFeedback(string $sessionId, string $predictedMood, ?string $actualMood, int $rating): void
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO user_feedback (session_id, predicted_mood, actual_mood, rating) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$sessionId, $predictedMood, $actualMood, $rating]);
    }
}