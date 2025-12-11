<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use MoodTune\Auth;
use MoodTune\Database;

Auth::requireLogin();
$user = Auth::getCurrentUser();

// Get history
$pdo = Database::getConnection();
$stmt = $pdo->prepare("
    SELECT * FROM mood_history 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 50
");
$stmt->execute([$user['id']]);
$history = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - MoodTune</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/history.css">
</head>
<body>
    <!-- Sidebar (same as dashboard) -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <span class="logo-icon">🎵</span>
                <span class="logo-text">MoodTune</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="analyze.php" class="nav-item">
                <span class="nav-icon">🎯</span>
                <span class="nav-text">Analyze Mood</span>
            </a>
            <a href="history.php" class="nav-item active">
                <span class="nav-icon">📈</span>
                <span class="nav-text">History</span>
            </a>
            <a href="discover.php" class="nav-item">
                <span class="nav-icon">🎼</span>
                <span class="nav-text">Discover</span>
            </a>
            <a href="settings.php" class="nav-item">
                <span class="nav-icon">⚙️</span>
                <span class="nav-text">Settings</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-profile">
                <img src="<?php echo htmlspecialchars($user['avatar_url']); ?>" alt="Profile" class="user-avatar">
                <div class="user-info">
                    <div class="user-name"><?php echo htmlspecialchars($user['username']); ?></div>
                    <div class="user-role"><?php echo ucfirst($user['role']); ?></div>
                </div>
            </div>
            <a href="logout.php" class="btn-logout">
                <span>🚪</span>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="top-bar">
            <div class="page-title">
                <h1>Mood History 📈</h1>
                <p>Track your emotional patterns over time</p>
            </div>
            <div class="top-bar-actions">
                <select class="filter-select">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
                <button class="btn-export">
                    <span>📥</span>
                    <span>Export Data</span>
                </button>
            </div>
        </header>

        <?php if (empty($history)): ?>
            <div class="empty-state-large">
                <div class="empty-icon">📊</div>
                <h2>No History Yet</h2>
                <p>Start analyzing your mood to see your history here</p>
                <a href="analyze.php" class="btn btn-primary">Analyze Your Mood</a>
            </div>
        <?php else: ?>
            <!-- Stats Overview -->
            <div class="history-stats">
                <?php
                $moodEmojis = [
                    'happy' => '😊',
                    'excited' => '🎉',
                    'calm' => '😌',
                    'sad' => '😢',
                    'anxious' => '😰',
                    'neutral' => '😐'
                ];

                // Calculate stats
                $totalSessions = count($history);
                $moodCounts = array_count_values(array_column($history, 'detected_mood'));
                arsort($moodCounts);
                $mostCommonMood = key($moodCounts);
                $avgConfidence = array_sum(array_column($history, 'confidence_score')) / $totalSessions;
                ?>

                <div class="stat-box">
                    <div class="stat-icon">🎯</div>
                    <div class="stat-info">
                        <div class="stat-value"><?php echo $totalSessions; ?></div>
                        <div class="stat-label">Total Sessions</div>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-icon"><?php echo $moodEmojis[$mostCommonMood]; ?></div>
                    <div class="stat-info">
                        <div class="stat-value"><?php echo ucfirst($mostCommonMood); ?></div>
                        <div class="stat-label">Most Common</div>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-icon">📊</div>
                    <div class="stat-info">
                        <div class="stat-value"><?php echo round($avgConfidence, 1); ?>%</div>
                        <div class="stat-label">Avg Confidence</div>
                    </div>
                </div>

                <div class="stat-box">
                    <div class="stat-icon">🔥</div>
                    <div class="stat-info">
                        <div class="stat-value"><?php echo count(array_unique(array_column($history, 'detected_mood'))); ?></div>
                        <div class="stat-label">Unique Moods</div>
                    </div>
                </div>
            </div>

            <!-- History Timeline -->
            <div class="history-container">
                <h2>Your Mood Timeline</h2>
                
                <div class="history-timeline">
                    <?php foreach ($history as $entry): 
                        $emoji = $moodEmojis[$entry['detected_mood']] ?? '😐';
                        $date = new DateTime($entry['created_at']);
                        $timeAgo = $date->format('M d, Y') . ' at ' . $date->format('g:i A');
                    ?>
                    <div class="history-card">
                        <div class="history-left">
                            <div class="history-emoji"><?php echo $emoji; ?></div>
                            <div class="history-info">
                                <h3><?php echo ucfirst($entry['detected_mood']); ?> Mood</h3>
                                <p class="history-time">
                                    <span class="time-icon">🕐</span>
                                    <?php echo $timeAgo; ?>
                                </p>
                            </div>
                        </div>

                        <div class="history-metrics">
                            <div class="metric-item">
                                <span class="metric-label">Confidence</span>
                                <span class="metric-value"><?php echo round($entry['confidence_score']); ?>%</span>
                            </div>
                            <div class="metric-item">
                                <span class="metric-label">Energy</span>
                                <span class="metric-badge <?php echo $entry['energy_level']; ?>">
                                    <?php echo ucfirst($entry['energy_level']); ?>
                                </span>
                            </div>
                            <div class="metric-item">
                                <span class="metric-label">Interactions</span>
                                <span class="metric-value"><?php echo $entry['total_interactions']; ?></span>
                            </div>
                        </div>

                        <div class="history-actions">
                            <button class="btn-icon" title="View Details">
                                <span>👁️</span>
                            </button>
                            <button class="btn-icon" title="Share">
                                <span>📤</span>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="load-more">
                    <button class="btn-load-more">Load More</button>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <script>
        // Filter functionality
        document.querySelector('.filter-select')?.addEventListener('change', function(e) {
            console.log('Filter by:', e.target.value);
            // Implement filtering logic
        });

        // Export functionality
        document.querySelector('.btn-export')?.addEventListener('click', function() {
            alert('Exporting your mood data...');
            // Implement export logic
        });

        // Load more functionality
        document.querySelector('.btn-load-more')?.addEventListener('click', function() {
            alert('Loading more history...');
            // Implement pagination
        });
    </script>
</body>
</html>