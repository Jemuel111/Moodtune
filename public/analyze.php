<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use MoodTune\Auth;

// Require authentication
Auth::requireLogin();

$user = Auth::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyze Mood - MoodTune</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/analyze.css">
</head>
<body>
    <!-- Sidebar Navigation (same as dashboard) -->
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
            <a href="analyze.php" class="nav-item active">
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
                <h1>Analyze Your Mood 🎯</h1>
                <p>Interact naturally and let AI detect your emotional state</p>
            </div>
        </header>

        <div class="analyze-container">
            <div class="info-banner">
                <div class="info-icon">💡</div>
                <div class="info-content">
                    <strong>How it works:</strong> Move your mouse, click around, and type naturally. 
                    Our ML algorithm analyzes your behavioral patterns to detect your mood and recommend perfect music.
                </div>
            </div>

            <div class="interaction-section">
                <div class="interaction-area" id="interactionArea">
                    <h3>👆 Interact Here!</h3>
                    <p>Move your mouse • Click around • Type something</p>
                    <input type="text" placeholder="Type how you feel..." id="userInput">
                </div>

                <div class="metrics-grid" id="metrics">
                    <div class="metric-card">
                        <div class="metric-icon">🖱️</div>
                        <div class="metric-content">
                            <div class="metric-value" id="mouseSpeed">0</div>
                            <div class="metric-label">Mouse Speed</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">👆</div>
                        <div class="metric-content">
                            <div class="metric-value" id="clickCount">0</div>
                            <div class="metric-label">Clicks</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">⌨️</div>
                        <div class="metric-content">
                            <div class="metric-value" id="typingSpeed">0</div>
                            <div class="metric-label">Typing Speed</div>
                        </div>
                    </div>
                    <div class="metric-card">
                        <div class="metric-icon">🎯</div>
                        <div class="metric-content">
                            <div class="metric-value" id="interactions">0</div>
                            <div class="metric-label">Interactions</div>
                        </div>
                    </div>
                </div>

                <button id="analyzeBtn" class="btn-analyze">
                    <span class="btn-icon">🔍</span>
                    <span class="btn-text">Analyze My Mood with AI</span>
                </button>
            </div>

            <div id="results" class="results-section" style="display: none;">
                <div class="mood-result">
                    <div class="mood-indicator" id="moodEmoji">😊</div>
                    <h2 id="moodText">Happy</h2>
                    <p id="moodDescription"></p>
                    <div class="mood-stats">
                        <span class="mood-stat">
                            <strong>Confidence:</strong> <span id="confidence">0</span>%
                        </span>
                        <span class="mood-stat">
                            <strong>Energy:</strong> <span id="energy">medium</span>
                        </span>
                        <span class="mood-stat">
                            <strong>Stability:</strong> <span id="stability">medium</span>
                        </span>
                    </div>
                </div>

                <div class="recommendations">
                    <h3>🎼 Recommended Music for You</h3>
                    <div id="songList" class="song-grid"></div>
                </div>
            </div>
        </div>
    </main>

    <script src="js/app.js"></script>
</body>
</html>