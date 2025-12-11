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
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fullName = trim($_POST['full_name'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($username)) {
        $errors['username'] = 'Username is required';
    } elseif (strlen($username) < 3) {
        $errors['username'] = 'Username must be at least 3 characters';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }

    if ($password !== $confirmPassword) {
        $errors['confirm_password'] = 'Passwords do not match';
    }

    // If no validation errors, attempt registration
    if (empty($errors)) {
        $result = Auth::register($username, $email, $password, $fullName);
        
        if ($result['success']) {
            header('Location: login.php?registered=1');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - MoodTune</title>
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
                <h2>Join MoodTune Today!</h2>
                <p>Create your account and start discovering music that truly understands you.</p>
                
                <div class="feature-list">
                    <div class="feature-item">
                        <span class="feature-icon">✨</span>
                        <span>AI-powered mood detection</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">🎼</span>
                        <span>Personalized music recommendations</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">📊</span>
                        <span>Track your mood patterns</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">🔒</span>
                        <span>100% privacy guaranteed</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-form-container">
                <h1>Create Account</h1>
                <p class="auth-subtitle">Sign up to get started with MoodTune</p>

                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <span class="alert-icon">⚠️</span>
                        <span><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="auth-form" id="registerForm">
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            placeholder="Choose a username"
                            required
                            autofocus
                            value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                            class="<?php echo isset($errors['username']) ? 'error' : ''; ?>"
                        >
                        <?php if (isset($errors['username'])): ?>
                            <span class="error-message"><?php echo $errors['username']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="your@email.com"
                            required
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                            class="<?php echo isset($errors['email']) ? 'error' : ''; ?>"
                        >
                        <?php if (isset($errors['email'])): ?>
                            <span class="error-message"><?php echo $errors['email']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="full_name">Full Name (Optional)</label>
                        <input 
                            type="text" 
                            id="full_name" 
                            name="full_name" 
                            placeholder="Enter your full name"
                            value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                        >
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Min. 6 characters"
                                required
                                class="<?php echo isset($errors['password']) ? 'error' : ''; ?>"
                            >
                            <?php if (isset($errors['password'])): ?>
                                <span class="error-message"><?php echo $errors['password']; ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password *</label>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                placeholder="Re-enter password"
                                required
                                class="<?php echo isset($errors['confirm_password']) ? 'error' : ''; ?>"
                            >
                            <?php if (isset($errors['confirm_password'])): ?>
                                <span class="error-message"><?php echo $errors['confirm_password']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <span class="strength-text" id="strengthText">Password strength</span>
                    </div>

                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms" required>
                            <span>I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a></span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Create Account
                    </button>
                </form>

                <div class="auth-divider">
                    <span>or</span>
                </div>

                <div class="social-login">
                    <button class="btn btn-social btn-google" disabled>
                        <span>Continue with Google</span>
                    </button>
                    <button class="btn btn-social btn-github" disabled>
                        <span>Continue with GitHub</span>
                    </button>
                </div>

                <div class="auth-footer">
                    <p>Already have an account? <a href="login.php">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 6) strength += 25;
            if (password.length >= 10) strength += 25;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
            if (/\d/.test(password) && /[!@#$%^&*]/.test(password)) strength += 25;

            strengthFill.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthFill.style.background = '#ff4444';
                strengthText.textContent = 'Weak password';
            } else if (strength <= 50) {
                strengthFill.style.background = '#ffaa00';
                strengthText.textContent = 'Fair password';
            } else if (strength <= 75) {
                strengthFill.style.background = '#00aa00';
                strengthText.textContent = 'Good password';
            } else {
                strengthFill.style.background = '#00ff00';
                strengthText.textContent = 'Strong password';
            }
        });

        // Password match validation
        const confirmPassword = document.getElementById('confirm_password');
        confirmPassword.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.style.borderColor = '#ff4444';
            } else {
                this.style.borderColor = '#00aa00';
            }
        });

        // Auto-hide alerts
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