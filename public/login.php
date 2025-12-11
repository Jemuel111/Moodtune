<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use MoodTune\Auth;

// Redirect if already logged in
if (Auth::isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = Auth::login($username, $password);
    
    if ($result['success']) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MoodTune</title>
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-left">
            <div class="auth-brand">
                <a href="landing.php">
                    <span class="logo-icon">🎵</span>
                    <span class="logo-text">MoodTune</span>
                </a>
            </div>
            <div class="auth-illustration">
                <h2>Welcome Back!</h2>
                <p>Discover music that matches your mood with AI-powered recommendations.</p>
                <div class="floating-emojis">
                    <span class="float-1">🎵</span>
                    <span class="float-2">😊</span>
                    <span class="float-3">🎧</span>
                    <span class="float-4">🎶</span>
                </div>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-form-container">
                <h1>Sign In</h1>
                <p class="auth-subtitle">Enter your credentials to access your account</p>

                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <span class="alert-icon">⚠️</span>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['registered'])): ?>
                    <div class="alert alert-success">
                        <span class="alert-icon">✅</span>
                        <span>Registration successful! Please login.</span>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['logout'])): ?>
                    <div class="alert alert-info">
                        <span class="alert-icon">ℹ️</span>
                        <span>You have been logged out successfully.</span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="auth-form">
                    <div class="form-group">
                        <label for="username">Username or Email</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            placeholder="Enter your username or email"
                            required
                            autofocus
                            value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password"
                            required
                        >
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>
                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Sign In
                    </button>
                </form>

                <div class="auth-divider">
                    <span>or</span>
                </div>

                <div class="demo-accounts">
                    <p class="demo-title">Demo Accounts (for testing):</p>
                    <div class="demo-credentials">
                        <div class="demo-item">
                            <strong>User:</strong> demo / admin123
                        </div>
                        <div class="demo-item">
                            <strong>Admin:</strong> admin / admin123
                        </div>
                    </div>
                </div>

                <div class="auth-footer">
                    <p>Don't have an account? <a href="register.php">Sign Up</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>