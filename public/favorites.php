<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use MoodTune\Auth;
use MoodTune\Database;

Auth::requireLogin();
$user = Auth::getCurrentUser();

// Get favorites
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorites - MoodTune</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/favorites.css">
</head>
<body>
    <!-- Sidebar -->
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
            <a href="history.php" class="nav-item">
                <span class="nav-icon">📈</span>
                <span class="nav-text">History</span>
            </a>
            <a href="favorites.php" class="nav-item active">
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
                <h1>Your Favorites ❤️</h1>
                <p>Music you love, all in one place</p>
            </div>
            <div class="top-bar-actions">
                <button class="btn-create-playlist">
                    <span>➕</span>
                    <span>Create Playlist</span>
                </button>
            </div>
        </header>

        <?php if (empty($favorites)): ?>
            <div class="empty-state-large">
                <div class="empty-icon">❤️</div>
                <h2>No Favorites Yet</h2>
                <p>Start adding your favorite songs by analyzing your mood!</p>
                <a href="analyze.php" class="btn btn-primary">Discover Music</a>
            </div>
        <?php else: ?>
            <div class="favorites-grid">
                <?php foreach ($favorites as $song): ?>
                    <div class="favorite-card">
                    <div class="favorite-emoji"><?php echo htmlspecialchars($song['emoji']); ?></div>
                    <div class="favorite-content">
                        <h3><?php echo htmlspecialchars($song['title']); ?></h3>
                        <p class="artist"><?php echo htmlspecialchars($song['artist']); ?></p>
                        <div class="favorite-meta">
                            <span class="genre-badge"><?php echo htmlspecialchars($song['genre']); ?></span>
                            <span class="mood-badge"><?php echo ucfirst($song['mood_category']); ?></span>
                        </div>
                    </div>
                    <div class="favorite-actions">
                        <button class="btn-play" title="Play">
                            <span>▶️</span>
                        </button>
                        <button class="btn-remove" data-id="<?php echo $song['id']; ?>" title="Remove">
                            <span>🗑️</span>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        // Remove favorite functionality
        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', async function() {
                if (!confirm('Remove this song from favorites?')) return;
                
                const musicId = this.dataset.id;
                
                try {
                    const response = await fetch('api.php?action=toggle_favorite', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ music_id: musicId })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.closest('.favorite-card').remove();
                        
                        // Check if empty
                        if (document.querySelectorAll('.favorite-card').length === 0) {
                            location.reload();
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to remove favorite');
                }
            });
        });
    </script>
</body>
</html>