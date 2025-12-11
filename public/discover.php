<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use MoodTune\Auth;
use MoodTune\Database;

Auth::requireLogin();
$user = Auth::getCurrentUser();

// Get all moods with their music
$pdo = Database::getConnection();
$stmt = $pdo->query("
    SELECT mood_category, COUNT(*) as song_count
    FROM music_library 
    GROUP BY mood_category
");
$moodCounts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Get recommended mood based on user's history
$stmt = $pdo->prepare("
    SELECT detected_mood, COUNT(*) as count 
    FROM mood_history 
    WHERE user_id = ? 
    AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY detected_mood 
    ORDER BY count DESC 
    LIMIT 1
");
$stmt->execute([$user['id']]);
$recentMood = $stmt->fetch();
$suggestedMood = $recentMood['detected_mood'] ?? 'happy';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover - MoodTune</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/discover.css">
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
            <a href="discover.php" class="nav-item active">
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
                <h1>Discover Music 🎼</h1>
                <p>Explore curated playlists for every mood</p>
            </div>
        </header>

        <!-- Personalized Recommendation Banner -->
        <div class="recommendation-banner">
            <div class="banner-content">
                <div class="banner-icon">✨</div>
                <div class="banner-text">
                    <h3>Recommended for You</h3>
                    <p>Based on your recent activity, you might enjoy our <strong><?php echo ucfirst($suggestedMood); ?></strong> playlist</p>
                </div>
            </div>
            <button class="btn-explore" onclick="exploreMood('<?php echo $suggestedMood; ?>')">
                <span>Explore Now</span>
                <span>→</span>
            </button>
        </div>

        <!-- Mood Playlists Grid -->
        <div class="playlists-section">
            <h2>Browse by Mood</h2>
            <div class="playlists-grid">
                <!-- Happy Playlist -->
                <div class="playlist-card" data-mood="happy">
                    <div class="playlist-header" style="background: linear-gradient(135deg, #FFD93D 0%, #6BCB77 100%);">
                        <div class="playlist-emoji">😊</div>
                        <div class="playlist-stats">
                            <span class="song-count"><?php echo $moodCounts['happy'] ?? 4; ?> songs</span>
                        </div>
                    </div>
                    <div class="playlist-body">
                        <h3>Happy Vibes</h3>
                        <p>Uplifting tracks to boost your mood and energy</p>
                        <div class="playlist-tags">
                            <span class="tag">Upbeat</span>
                            <span class="tag">Positive</span>
                            <span class="tag">Energetic</span>
                        </div>
                    </div>
                    <div class="playlist-footer">
                        <button class="btn-view" onclick="viewPlaylist('happy')">View Playlist</button>
                    </div>
                </div>

                <!-- Excited Playlist -->
                <div class="playlist-card" data-mood="excited">
                    <div class="playlist-header" style="background: linear-gradient(135deg, #FF6B6B 0%, #FFE66D 100%);">
                        <div class="playlist-emoji">🎉</div>
                        <div class="playlist-stats">
                            <span class="song-count"><?php echo $moodCounts['excited'] ?? 4; ?> songs</span>
                        </div>
                    </div>
                    <div class="playlist-body">
                        <h3>Excitement Zone</h3>
                        <p>High-energy anthems for peak moments</p>
                        <div class="playlist-tags">
                            <span class="tag">Pumped</span>
                            <span class="tag">Dynamic</span>
                            <span class="tag">Intense</span>
                        </div>
                    </div>
                    <div class="playlist-footer">
                        <button class="btn-view" onclick="viewPlaylist('excited')">View Playlist</button>
                    </div>
                </div>

                <!-- Calm Playlist -->
                <div class="playlist-card" data-mood="calm">
                    <div class="playlist-header" style="background: linear-gradient(135deg, #A8E6CF 0%, #DCEDC1 100%);">
                        <div class="playlist-emoji">😌</div>
                        <div class="playlist-stats">
                            <span class="song-count"><?php echo $moodCounts['calm'] ?? 4; ?> songs</span>
                        </div>
                    </div>
                    <div class="playlist-body">
                        <h3>Peaceful Moments</h3>
                        <p>Soothing melodies for relaxation and focus</p>
                        <div class="playlist-tags">
                            <span class="tag">Relaxing</span>
                            <span class="tag">Ambient</span>
                            <span class="tag">Zen</span>
                        </div>
                    </div>
                    <div class="playlist-footer">
                        <button class="btn-view" onclick="viewPlaylist('calm')">View Playlist</button>
                    </div>
                </div>

                <!-- Sad Playlist -->
                <div class="playlist-card" data-mood="sad">
                    <div class="playlist-header" style="background: linear-gradient(135deg, #A8DADC 0%, #457B9D 100%);">
                        <div class="playlist-emoji">😢</div>
                        <div class="playlist-stats">
                            <span class="song-count"><?php echo $moodCounts['sad'] ?? 4; ?> songs</span>
                        </div>
                    </div>
                    <div class="playlist-body">
                        <h3>Reflective Tunes</h3>
                        <p>Thoughtful tracks for emotional moments</p>
                        <div class="playlist-tags">
                            <span class="tag">Melancholic</span>
                            <span class="tag">Emotional</span>
                            <span class="tag">Deep</span>
                        </div>
                    </div>
                    <div class="playlist-footer">
                        <button class="btn-view" onclick="viewPlaylist('sad')">View Playlist</button>
                    </div>
                </div>

                <!-- Anxious Playlist -->
                <div class="playlist-card" data-mood="anxious">
                    <div class="playlist-header" style="background: linear-gradient(135deg, #FAD5A5 0%, #E8B4B8 100%);">
                        <div class="playlist-emoji">😰</div>
                        <div class="playlist-stats">
                            <span class="song-count"><?php echo $moodCounts['anxious'] ?? 4; ?> songs</span>
                        </div>
                    </div>
                    <div class="playlist-body">
                        <h3>Calming Relief</h3>
                        <p>Comforting songs to ease tension</p>
                        <div class="playlist-tags">
                            <span class="tag">Soothing</span>
                            <span class="tag">Comfort</span>
                            <span class="tag">Peace</span>
                        </div>
                    </div>
                    <div class="playlist-footer">
                        <button class="btn-view" onclick="viewPlaylist('anxious')">View Playlist</button>
                    </div>
                </div>

                <!-- Neutral Playlist -->
                <div class="playlist-card" data-mood="neutral">
                    <div class="playlist-header" style="background: linear-gradient(135deg, #E3E3E3 0%, #B8B8B8 100%);">
                        <div class="playlist-emoji">😐</div>
                        <div class="playlist-stats">
                            <span class="song-count"><?php echo $moodCounts['neutral'] ?? 4; ?> songs</span>
                        </div>
                    </div>
                    <div class="playlist-body">
                        <h3>Everyday Sounds</h3>
                        <p>Balanced mix for any moment</p>
                        <div class="playlist-tags">
                            <span class="tag">Versatile</span>
                            <span class="tag">Balanced</span>
                            <span class="tag">Easy</span>
                        </div>
                    </div>
                    <div class="playlist-footer">
                        <button class="btn-view" onclick="viewPlaylist('neutral')">View Playlist</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Collections -->
        <div class="collections-section">
            <h2>Featured Collections</h2>
            <div class="collections-grid">
                <div class="collection-card">
                    <div class="collection-icon">🌅</div>
                    <h4>Morning Boost</h4>
                    <p>Start your day with energy</p>
                </div>
                <div class="collection-card">
                    <div class="collection-icon">💼</div>
                    <h4>Focus Flow</h4>
                    <p>Music for deep work</p>
                </div>
                <div class="collection-card">
                    <div class="collection-icon">🏃</div>
                    <h4>Workout Power</h4>
                    <p>High-energy exercise tracks</p>
                </div>
                <div class="collection-card">
                    <div class="collection-icon">🌙</div>
                    <h4>Night Wind Down</h4>
                    <p>Relax before sleep</p>
                </div>
            </div>
        </div>

        <!-- Playlist Modal -->
        <div id="playlistModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="modalTitle">Playlist</h2>
                    <button class="modal-close" onclick="closeModal()">×</button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Songs will be loaded here -->
                </div>
            </div>
        </div>
    </main>

    <script>
        function exploreMood(mood) {
            viewPlaylist(mood);
        }

        async function viewPlaylist(mood) {
            const modal = document.getElementById('playlistModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalBody = document.getElementById('modalBody');
            
            const moodData = {
                'happy': { emoji: '😊', name: 'Happy Vibes' },
                'excited': { emoji: '🎉', name: 'Excitement Zone' },
                'calm': { emoji: '😌', name: 'Peaceful Moments' },
                'sad': { emoji: '😢', name: 'Reflective Tunes' },
                'anxious': { emoji: '😰', name: 'Calming Relief' },
                'neutral': { emoji: '😐', name: 'Everyday Sounds' }
            };

            modalTitle.innerHTML = `${moodData[mood].emoji} ${moodData[mood].name}`;
            modalBody.innerHTML = '<div class="loading">Loading playlist...</div>';
            modal.style.display = 'flex';

            try {
                const response = await fetch(`api.php?action=get_playlist&mood=${mood}`);
                const data = await response.json();

                if (data.success) {
                    modalBody.innerHTML = data.songs.map((song, index) => `
                        <div class="song-item">
                            <div class="song-number">${index + 1}</div>
                            <div class="song-emoji">${song.emoji}</div>
                            <div class="song-details">
                                <div class="song-title">${song.title}</div>
                                <div class="song-artist">${song.artist} • ${song.genre}</div>
                            </div>
                            <div class="song-badge">${song.energy_level}</div>
                        </div>
                    `).join('');
                } else {
                    modalBody.innerHTML = '<div class="error">Failed to load playlist</div>';
                }
            } catch (error) {
                console.error('Error:', error);
                modalBody.innerHTML = '<div class="error">Failed to load playlist</div>';
            }
        }

        function closeModal() {
            document.getElementById('playlistModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('playlistModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Add hover effect to playlist cards
        document.querySelectorAll('.playlist-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
</body>
</html>