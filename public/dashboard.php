<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use MoodTune\Auth;
use MoodTune\Database;

// Require authentication
Auth::requireLogin();

$user = Auth::getCurrentUser();
$stats = Auth::getUserStats($user['id']);
$recentMoods = Auth::getUserMoodHistory($user['id'], 5);

// Get mood distribution
$pdo = Database::getConnection();
$stmt = $pdo->prepare("
    SELECT detected_mood, COUNT(*) as count 
    FROM mood_history 
    WHERE user_id = ? 
    GROUP BY detected_mood 
    ORDER BY count DESC
");
$stmt->execute([$user['id']]);
$moodDistribution = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MoodTune</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <span class="logo-icon">🎵</span>
                <span class="logo-text">MoodTune</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item active">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>
            <a href="analyze.php" class="nav-item">
                <span class="nav-icon">🎯</span>
                <span class="nav-text">Analyze Mood</span>
            </a>
            <a href="history.php" class="nav-item">
                <span class="nav-icon">📈</span>
                <span class="nav-text">History</span>
            </a>
            <a href="favorites.php" class="nav-item">
                <span class="nav-icon">❤️</span>
                <span class="nav-text">Favorites</span>
            </a>
            <a href="settings.php" class="nav-item">
                <span class="nav-icon">⚙️</span>
                <span class="nav-text">Settings</span>
            </a>
            
            <?php if ($user['role'] === 'admin'): ?>
            <div class="nav-divider"></div>
            <a href="admin.php" class="nav-item">
                <span class="nav-icon">👑</span>
                <span class="nav-text">Admin Panel</span>
            </a>
            <?php endif; ?>
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
        <!-- Top Bar -->
        <header class="top-bar">
            <div class="page-title">
                <h1>Welcome back, <?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?>! 👋</h1>
                <p>Here's what's happening with your mood today</p>
            </div>
            <div class="top-bar-actions">
                <button class="btn-notification">
                    <span class="notification-icon">🔔</span>
                    <span class="notification-badge">3</span>
                </button>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    🎯
                </div>
                <div class="stat-content">
                    <div class="stat-label">Total Sessions</div>
                    <div class="stat-value"><?php echo $stats['total_sessions']; ?></div>
                    <div class="stat-change positive">
                        <span>↑ 12%</span>
                        <span>from last week</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <?php 
                    $moodEmojis = [
                        'happy' => '😊',
                        'excited' => '🎉',
                        'calm' => '😌',
                        'sad' => '😢',
                        'anxious' => '😰',
                        'neutral' => '😐'
                    ];
                    echo $moodEmojis[$stats['most_common_mood']] ?? '😐';
                    ?>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Most Common Mood</div>
                    <div class="stat-value"><?php echo ucfirst($stats['most_common_mood']); ?></div>
                    <div class="stat-change">
                        <span><?php echo $stats['mood_count']; ?> times</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    📊
                </div>
                <div class="stat-content">
                    <div class="stat-label">Avg Confidence</div>
                    <div class="stat-value"><?php echo $stats['avg_confidence']; ?>%</div>
                    <div class="stat-change positive">
                        <span>↑ 5%</span>
                        <span>accuracy improved</span>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    🎼
                </div>
                <div class="stat-content">
                    <div class="stat-label">Songs Recommended</div>
                    <div class="stat-value"><?php echo $stats['total_sessions'] * 4; ?></div>
                    <div class="stat-change">
                        <span>across all moods</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="dashboard-grid">
            <!-- Recent Moods -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Recent Mood History</h2>
                    <a href="history.php" class="card-action">View All →</a>
                </div>
                <div class="card-content">
                    <?php if (empty($recentMoods)): ?>
                        <div class="empty-state">
                            <div class="empty-icon">🎯</div>
                            <h3>No mood data yet</h3>
                            <p>Start analyzing your mood to see your history here</p>
                            <a href="analyze.php" class="btn btn-primary">Analyze Now</a>
                        </div>
                    <?php else: ?>
                        <div class="mood-timeline">
                            <?php foreach ($recentMoods as $mood): 
                                $emoji = $moodEmojis[$mood['detected_mood']] ?? '😐';
                                $timeAgo = date('M d, Y H:i', strtotime($mood['created_at']));
                            ?>
                            <div class="timeline-item">
                                <div class="timeline-icon"><?php echo $emoji; ?></div>
                                <div class="timeline-content">
                                    <div class="timeline-title">
                                        <?php echo ucfirst($mood['detected_mood']); ?> Mood
                                    </div>
                                    <div class="timeline-meta">
                                        <span><?php echo $timeAgo; ?></span>
                                        <span class="confidence-badge">
                                            <?php echo round($mood['confidence_score']); ?>% confidence
                                        </span>
                                    </div>
                                </div>
                                <div class="timeline-energy">
                                    <span class="energy-badge <?php echo $mood['energy_level']; ?>">
                                        <?php echo ucfirst($mood['energy_level']); ?> Energy
                                    </span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Mood Distribution Chart -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h2>Mood Distribution</h2>
                    <select class="card-filter">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                        <option>All time</option>
                    </select>
                </div>
                <div class="card-content">
                    <?php if (empty($moodDistribution)): ?>
                        <div class="empty-state-small">
                            <p>No mood data to display</p>
                        </div>
                    <?php else: 
                        $total = array_sum(array_column($moodDistribution, 'count'));
                    ?>
                        <div class="mood-chart">
                            <?php foreach ($moodDistribution as $mood): 
                                $percentage = ($mood['count'] / $total) * 100;
                                $emoji = $moodEmojis[$mood['detected_mood']] ?? '😐';
                            ?>
                            <div class="mood-bar">
                                <div class="mood-bar-label">
                                    <span class="mood-emoji"><?php echo $emoji; ?></span>
                                    <span><?php echo ucfirst($mood['detected_mood']); ?></span>
                                </div>
                                <div class="mood-bar-track">
                                    <div class="mood-bar-fill" style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                                <div class="mood-bar-value">
                                    <?php echo round($percentage); ?>%
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-card quick-actions-card">
                <div class="card-header">
                    <h2>Quick Actions</h2>
                </div>
                <div class="card-content">
                    <div class="quick-actions">
                        <a href="analyze.php" class="quick-action-btn">
                            <div class="quick-action-icon">🎯</div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Analyze Mood</div>
                                <div class="quick-action-desc">Get instant recommendations</div>
                            </div>
                        </a>
                        
                        <a href="history.php" class="quick-action-btn">
                            <div class="quick-action-icon">📊</div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">View History</div>
                                <div class="quick-action-desc">Track your patterns</div>
                            </div>
                        </a>
                        
                        <a href="favorites.php" class="quick-action-btn">
                            <div class="quick-action-icon">❤️</div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Your Favorites</div>
                                <div class="quick-action-desc">Saved music tracks</div>
                            </div>
                        </a>
                        
                        <a href="settings.php" class="quick-action-btn">
                            <div class="quick-action-icon">⚙️</div>
                            <div class="quick-action-content">
                                <div class="quick-action-title">Settings</div>
                                <div class="quick-action-desc">Customize your experience</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Fun Fact -->
            <div class="dashboard-card fun-fact-card">
                <div class="card-content">
                    <div class="fun-fact">
                        <div class="fun-fact-icon">💡</div>
                        <div class="fun-fact-content">
                            <h3>Did you know?</h3>
                            <p>Listening to music that matches your mood can help regulate emotions and improve mental wellbeing. Our AI helps you discover the perfect soundtrack!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Add active state to current nav item
        document.querySelectorAll('.nav-item').forEach(item => {
            if (item.href === window.location.href) {
                item.classList.add('active');
            }
        });

        // Notification badge animation
        const notificationBtn = document.querySelector('.btn-notification');
        notificationBtn?.addEventListener('click', () => {
            const badge = document.querySelector('.notification-badge');
            badge.style.display = 'none';
        });
    </script>
</body>
</html>