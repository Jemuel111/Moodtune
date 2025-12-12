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

    // Demo mode thresholds for easy mood triggering
    private const DEMO_THRESHOLDS = [
        'happy' => [
            'min_mouse_speed' => 40,
            'min_click_rate' => 1.5,
            'max_typing_interval' => 300,
            'description' => 'Move mouse FAST, Click FREQUENTLY (1.5+ clicks/sec), Type QUICKLY'
        ],
        'excited' => [
            'min_mouse_speed' => 50,
            'min_click_rate' => 2.0,
            'min_variance' => 800,
            'description' => 'Move mouse VERY FAST with ERRATIC movements, Click RAPIDLY (2+ clicks/sec)'
        ],
        'calm' => [
            'max_mouse_speed' => 20,
            'max_click_rate' => 0.5,
            'min_typing_interval' => 600,
            'description' => 'Move mouse SLOWLY, Click RARELY (< 0.5 clicks/sec), Type SLOWLY'
        ],
        'sad' => [
            'max_mouse_speed' => 15,
            'max_click_rate' => 0.3,
            'min_variance' => 200,
            'description' => 'Move mouse VERY SLOWLY with irregular pauses, Almost NO clicks'
        ],
        'anxious' => [
            'mouse_speed' => [30, 60],
            'min_variance' => 1000,
            'min_click_rate' => 1.0,
            'description' => 'Move mouse with JERKY, ERRATIC movements, Click INCONSISTENTLY'
        ],
        'neutral' => [
            'mouse_speed' => [25, 35],
            'click_rate' => [0.5, 1.2],
            'description' => 'Move mouse at NORMAL pace, Click at REGULAR intervals'
        ]
    ];

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
            [0.82, 0.78, 0.78, 0.68],
            
            // Excited: high energy, low stability
            [0.85, 0.3, 0.8, 0.75],
            [0.9, 0.25, 0.85, 0.8],
            [0.8, 0.35, 0.75, 0.7],
            [0.88, 0.28, 0.82, 0.78],
            
            // Calm: low energy, high stability
            [0.2, 0.8, 0.25, 0.15],
            [0.15, 0.85, 0.2, 0.1],
            [0.25, 0.75, 0.3, 0.2],
            [0.18, 0.82, 0.22, 0.12],
            
            // Sad: low energy, low stability
            [0.2, 0.3, 0.15, 0.2],
            [0.15, 0.25, 0.1, 0.15],
            [0.25, 0.35, 0.2, 0.25],
            [0.18, 0.28, 0.12, 0.18],
            
            // Anxious: medium energy, very low stability
            [0.5, 0.2, 0.45, 0.5],
            [0.55, 0.15, 0.5, 0.55],
            [0.45, 0.25, 0.4, 0.45],
            [0.52, 0.18, 0.48, 0.52],
            
            // Neutral: medium energy, medium stability
            [0.5, 0.5, 0.5, 0.5],
            [0.45, 0.55, 0.48, 0.52],
            [0.55, 0.45, 0.52, 0.48],
            [0.48, 0.52, 0.49, 0.51]
        ];

        $labels = [
            'happy', 'happy', 'happy', 'happy',
            'excited', 'excited', 'excited', 'excited',
            'calm', 'calm', 'calm', 'calm',
            'sad', 'sad', 'sad', 'sad',
            'anxious', 'anxious', 'anxious', 'anxious',
            'neutral', 'neutral', 'neutral', 'neutral'
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

        // First, try demo mode detection for easier triggering
        $demoMood = $this->detectDemoMood($features);
        if ($demoMood) {
            return $this->createMoodResult($demoMood, $features);
        }

        // Fall back to ML prediction
        return $this->mlPredict($features);
    }

    private function detectDemoMood(array $features): ?string
    {
        $mouseSpeed = $features['avgMouseSpeed'];
        $clickRate = $features['clickRate'];
        $typingInterval = $features['avgTypingInterval'];
        $variance = $features['mouseVariance'];

        // Excited: Very fast, erratic, lots of clicks
        if ($mouseSpeed > 50 && $clickRate > 2.0 && $variance > 800) {
            return 'excited';
        }

        // Happy: Fast, steady, frequent clicks
        if ($mouseSpeed > 40 && $clickRate > 1.5 && $typingInterval < 300 && $variance < 600) {
            return 'happy';
        }

        // Anxious: Jerky, erratic movements
        if ($variance > 1000 && $mouseSpeed > 30 && $mouseSpeed < 60 && $clickRate > 1.0) {
            return 'anxious';
        }

        // Sad: Very slow, few clicks, irregular
        if ($mouseSpeed < 15 && $clickRate < 0.3 && $variance > 200) {
            return 'sad';
        }

        // Calm: Slow, steady, few clicks
        if ($mouseSpeed < 20 && $clickRate < 0.5 && $typingInterval > 600 && $variance < 300) {
            return 'calm';
        }

        // Neutral: Medium everything
        if ($mouseSpeed >= 25 && $mouseSpeed <= 35 && 
            $clickRate >= 0.5 && $clickRate <= 1.2) {
            return 'neutral';
        }

        return null; // No demo match, use ML
    }

    private function createMoodResult(string $mood, array $features): array
    {
        $normalized = BehaviorAnalyzer::normalizeFeatures($features);
        $energy = BehaviorAnalyzer::calculateEnergyLevel($normalized);
        $stability = BehaviorAnalyzer::calculateStability($normalized);

        return [
            'type' => $mood,
            'energy' => $energy > 0.6 ? 'high' : ($energy < 0.3 ? 'low' : 'medium'),
            'stability' => $stability > 0.6 ? 'high' : ($stability < 0.3 ? 'low' : 'medium'),
            'confidence' => $this->calculateConfidence($energy, $stability),
            'detected_by' => 'demo_mode'
        ];
    }

    private function mlPredict(array $features): array
    {
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
            'confidence' => $this->calculateConfidence($energy, $stability),
            'detected_by' => 'ml_model'
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
                'description' => 'Your behavior indicates an energetic and positive mood!',
                'demo_tip' => 'Move mouse FAST (40+ speed), Click FREQUENTLY (1.5+ times/sec)'
            ],
            'excited' => [
                'emoji' => '🎉',
                'description' => 'You\'re showing high energy and enthusiasm!',
                'demo_tip' => 'Move mouse VERY FAST (50+ speed) with ERRATIC movements, Click RAPIDLY (2+ times/sec)'
            ],
            'calm' => [
                'emoji' => '😌',
                'description' => 'Your interactions suggest a relaxed and peaceful state.',
                'demo_tip' => 'Move mouse SLOWLY (< 20 speed), Click RARELY (< 0.5 times/sec)'
            ],
            'sad' => [
                'emoji' => '😢',
                'description' => 'You seem to be in a reflective, low-energy mood.',
                'demo_tip' => 'Move mouse VERY SLOWLY (< 15 speed), Almost NO clicks (< 0.3 times/sec)'
            ],
            'anxious' => [
                'emoji' => '😰',
                'description' => 'Your behavior shows some restlessness and tension.',
                'demo_tip' => 'Move mouse with JERKY, ERRATIC movements (high variance), Click INCONSISTENTLY'
            ],
            'neutral' => [
                'emoji' => '😐',
                'description' => 'You\'re in a balanced, moderate mood state.',
                'demo_tip' => 'Move mouse at NORMAL pace (25-35 speed), Click at REGULAR intervals'
            ]
        ];

        return $moodInfo[$moodType] ?? $moodInfo['neutral'];
    }

    public static function getDemoGuide(): array
    {
        return [
            'happy' => [
                'name' => '😊 Happy',
                'instructions' => [
                    'Move your mouse FAST around the screen',
                    'Click frequently (1.5+ times per second)',
                    'Type quickly if using the input field',
                    'Maintain consistent, energetic movements'
                ],
                'metrics' => 'Target: Mouse Speed > 40, Click Rate > 1.5/sec'
            ],
            'excited' => [
                'name' => '🎉 Excited',
                'instructions' => [
                    'Move your mouse VERY FAST with wild movements',
                    'Click RAPIDLY (2+ times per second)',
                    'Make ERRATIC, unpredictable movements',
                    'High energy, high variance movements'
                ],
                'metrics' => 'Target: Mouse Speed > 50, Click Rate > 2/sec, High Variance'
            ],
            'calm' => [
                'name' => '😌 Calm',
                'instructions' => [
                    'Move your mouse SLOWLY and smoothly',
                    'Click RARELY (less than once per 2 seconds)',
                    'Type slowly and deliberately',
                    'Keep movements smooth and consistent'
                ],
                'metrics' => 'Target: Mouse Speed < 20, Click Rate < 0.5/sec'
            ],
            'sad' => [
                'name' => '😢 Sad',
                'instructions' => [
                    'Move your mouse VERY SLOWLY',
                    'Almost NO clicking (very infrequent)',
                    'Make irregular, hesitant movements',
                    'Low energy with pauses'
                ],
                'metrics' => 'Target: Mouse Speed < 15, Click Rate < 0.3/sec'
            ],
            'anxious' => [
                'name' => '😰 Anxious',
                'instructions' => [
                    'Move mouse with JERKY, ERRATIC movements',
                    'Sudden starts and stops',
                    'Inconsistent clicking patterns',
                    'High variance in movement speed'
                ],
                'metrics' => 'Target: High Variance (> 1000), Irregular patterns'
            ],
            'neutral' => [
                'name' => '😐 Neutral',
                'instructions' => [
                    'Move mouse at NORMAL, comfortable pace',
                    'Click at regular, moderate intervals',
                    'Maintain balanced, steady movements',
                    'No extremes in speed or frequency'
                ],
                'metrics' => 'Target: Mouse Speed 25-35, Click Rate 0.5-1.2/sec'
            ]
        ];
    }
}