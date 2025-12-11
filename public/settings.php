<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use MoodTune\Auth;

Auth::requireLogin();
$user = Auth::getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - MoodTune</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/settings.css">
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
            <a href="favorites.php" class="nav-item">
                <span class="nav-icon">❤️</span>
                <span class="nav-text">Favorites</span>
            </a>
            <a href="settings.php" class="nav-item active">
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
                <h1>Settings ⚙️</h1>
                <p>Manage your account and preferences</p>
            </div>
        </header>

        <div class="settings-container">
            <!-- Profile Settings -->
            <div class="settings-section">
                <h2>Profile Information</h2>
                <form id="profileForm" class="settings-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                        <small>Username cannot be changed</small>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Password Change -->
            <div class="settings-section">
                <h2>Change Password</h2>
                <form id="passwordForm" class="settings-form">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                        <small>Must be at least 6 characters</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>

            <!-- Account Info -->
            <div class="settings-section">
                <h2>Account Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Account Created</span>
                        <span class="info-value"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last Login</span>
                        <span class="info-value"><?php echo $user['last_login'] ? date('M d, Y H:i', strtotime($user['last_login'])) : 'N/A'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Account Type</span>
                        <span class="info-value"><?php echo ucfirst($user['role']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Account Status</span>
                        <span class="info-value status-active">Active</span>
                    </div>
                </div>
            </div>

            <!-- Preferences -->
            <div class="settings-section">
                <h2>Preferences</h2>
                <div class="preference-list">
                    <div class="preference-item">
                        <div class="preference-info">
                            <h4>Email Notifications</h4>
                            <p>Receive email updates about your mood analysis</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="preference-item">
                        <div class="preference-info">
                            <h4>Weekly Reports</h4>
                            <p>Get weekly mood summary reports</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="preference-item">
                        <div class="preference-info">
                            <h4>Music Recommendations</h4>
                            <p>Show personalized music suggestions</p>
                        </div>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="settings-section danger-zone">
                <h2>Danger Zone</h2>
                <div class="danger-actions">
                    <div class="danger-item">
                        <div class="danger-info">
                            <h4>Delete All History</h4>
                            <p>Permanently delete all your mood history data</p>
                        </div>
                        <button class="btn btn-danger" onclick="confirmAction('delete history')">Delete History</button>
                    </div>

                    <div class="danger-item">
                        <div class="danger-info">
                            <h4>Delete Account</h4>
                            <p>Permanently delete your account and all associated data</p>
                        </div>
                        <button class="btn btn-danger" onclick="confirmAction('delete account')">Delete Account</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Profile Form
        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = {
                email: document.getElementById('email').value,
                full_name: document.getElementById('full_name').value
            };

            try {
                const response = await fetch('api.php?action=update_profile', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Profile updated successfully!', 'success');
                } else {
                    showNotification(data.error || 'Failed to update profile', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        });

        // Password Form
        document.getElementById('passwordForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (newPassword !== confirmPassword) {
                showNotification('Passwords do not match', 'error');
                return;
            }

            const formData = {
                current_password: document.getElementById('current_password').value,
                new_password: newPassword
            };

            try {
                const response = await fetch('api.php?action=change_password', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    showNotification('Password changed successfully!', 'success');
                    document.getElementById('passwordForm').reset();
                } else {
                    showNotification(data.error || 'Failed to change password', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        });

        // Notification System
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Danger Zone Actions
        function confirmAction(action) {
            if (confirm(`Are you sure you want to ${action}? This action cannot be undone.`)) {
                alert(`${action} functionality would be implemented here`);
            }
        }
    </script>
</body>
</html>