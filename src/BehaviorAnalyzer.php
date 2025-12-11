<?php

namespace MoodTune;

class BehaviorAnalyzer
{
    public static function analyzeFeatures(array $rawData): array
    {
        $features = [
            'avgMouseSpeed' => $rawData['avgMouseSpeed'] ?? 0,
            'clickRate' => $rawData['clickRate'] ?? 0,
            'avgTypingInterval' => $rawData['avgTypingInterval'] ?? 500,
            'mouseVariance' => $rawData['mouseVariance'] ?? 0,
            'totalInteractions' => $rawData['totalInteractions'] ?? 0
        ];

        return $features;
    }

    public static function normalizeFeatures(array $features): array
    {
        return [
            'speedScore' => min($features['avgMouseSpeed'] / 50, 1),
            'clickScore' => min($features['clickRate'] / 2, 1),
            'typingScore' => $features['avgTypingInterval'] < 200 
                ? 1 
                : max(1 - ($features['avgTypingInterval'] / 1000), 0),
            'varianceScore' => min($features['mouseVariance'] / 1000, 1)
        ];
    }

    public static function calculateEnergyLevel(array $normalized): float
    {
        return ($normalized['speedScore'] + 
                $normalized['clickScore'] + 
                $normalized['typingScore'] + 
                $normalized['varianceScore']) / 4;
    }

    public static function calculateStability(array $normalized): float
    {
        return 1 - min($normalized['varianceScore'], 1);
    }
}