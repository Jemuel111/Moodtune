<?php

namespace MoodTune;

use Rubix\ML\Classifiers\KNearestNeighbors;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Kernels\Distance\Euclidean;

class MoodClassifier
{
    private $classifier;
    private $isTrained = false;

    public function __construct()
    {
        // Initialize K-Nearest Neighbors classifier with Rubix ML
        $this->classifier = new KNearestNeighbors(5, false, new Euclidean());
        $this->trainModel();
    }

    private function trainModel(): void
    {
        // Training data: [energy, stability, speedScore, clickScore]
        $samples = [
            // Happy: high energy, high stability
            [0.8, 0.7, 0.75, 0.6],
            [0.75, 0.8, 0.7, 0.65],
            [0.85, 0.75, 0.8, 0.7],
            
            // Excited: high energy, low stability
            [0.85, 0.3, 0.8, 0.75],
            [0.9, 0.25, 0.85, 0.8],
            [0.8, 0.35, 0.75, 0.7],
            
            // Calm: low energy, high stability
            [0.2, 0.8, 0.25, 0.15],
            [0.15, 0.85, 0.2, 0.1],
            [0.25, 0.75, 0.3, 0.2],
            
            // Sad: low energy, low stability
            [0.2, 0.3, 0.15, 0.2],
            [0.15, 0.25, 0.1, 0.15],
            [0.25, 0.35, 0.2, 0.25],
            
            // Anxious: medium energy, very low stability
            [0.5, 0.2, 0.45, 0.5],
            [0.55, 0.15, 0.5, 0.55],
            [0.45, 0.25, 0.4, 0.45],
            
            // Neutral: medium energy, medium stability
            [0.5, 0.5, 0.5, 0.5],
            [0.45, 0.55, 0.48, 0.52],
            [0.55, 0.45, 0.52, 0.48]
        ];

        $labels = [
            'happy', 'happy', 'happy',
            'excited', 'excited', 'excited',
            'calm', 'calm', 'calm',
            'sad', 'sad', 'sad',
            'anxious', 'anxious', 'anxious',
            'neutral', 'neutral', 'neutral'
        ];

        $dataset = new Labeled($samples, $labels);
        $this->classifier->train($dataset);
        $this->isTrained = true;
    }

    public function predict(array $features): array
    {
        if (!$this->isTrained) {
            throw new \Exception("Model not trained yet");
        }

        // Normalize features
        $normalized = BehaviorAnalyzer::normalizeFeatures($features);
        $energy = BehaviorAnalyzer::calculateEnergyLevel($normalized);
        $stability = BehaviorAnalyzer::calculateStability($normalized);

        // Prepare sample for prediction
        $sample = [
            $energy,
            $stability,
            $normalized['speedScore'],
            $normalized['clickScore']
        ];

        // Create unlabeled dataset
        $dataset = new Unlabeled([$sample]);
        
        // Make prediction
        $predictions = $this->classifier->predict($dataset);
        $predictedMood = $predictions[0];

        return [
            'type' => $predictedMood,
            'energy' => $energy > 0.6 ? 'high' : ($energy < 0.3 ? 'low' : 'medium'),
            'stability' => $stability > 0.6 ? 'high' : ($stability < 0.3 ? 'low' : 'medium'),
            'confidence' => $this->calculateConfidence($energy, $stability)
        ];
    }

    private function calculateConfidence(float $energy, float $stability): float
    {
        $distinctiveness = abs($energy - 0.5) + abs($stability - 0.5);
        return min($distinctiveness * 100, 95);
    }

    public static function getMoodInfo(string $moodType): array
    {
        $moodInfo = [
            'happy' => [
                'emoji' => '😊',
                'description' => 'Your behavior indicates an energetic and positive mood!'
            ],
            'excited' => [
                'emoji' => '🎉',
                'description' => 'You\'re showing high energy and enthusiasm!'
            ],
            'calm' => [
                'emoji' => '😌',
                'description' => 'Your interactions suggest a relaxed and peaceful state.'
            ],
            'sad' => [
                'emoji' => '😢',
                'description' => 'You seem to be in a reflective, low-energy mood.'
            ],
            'anxious' => [
                'emoji' => '😰',
                'description' => 'Your behavior shows some restlessness and tension.'
            ],
            'neutral' => [
                'emoji' => '😐',
                'description' => 'You\'re in a balanced, moderate mood state.'
            ]
        ];

        return $moodInfo[$moodType] ?? $moodInfo['neutral'];
    }
}