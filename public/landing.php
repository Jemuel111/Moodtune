<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoodTune - AI Music Recommendations</title>
    <link rel="stylesheet" href="css/landing.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="#" class="logo" id="logo">
                <span class="logo-icon">🎵</span>
                <span class="logo-text">MoodTune</span>
            </a>
            <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                <span id="menuIcon">☰</span>
            </button>
            <div class="nav-links" id="navLinks">
                <a href="#features">Features</a>
                <a href="#how-it-works">How It Works</a>
                <a href="login.php" class="btn-login">Login</a>
                <a href="register.php" class="btn-signup">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">
                Music That Understands
                <span class="gradient-text">Your Mood</span>
            </h1>
            <p class="hero-subtitle">
                AI-powered music recommendations based on your behavior. 
                Let machine learning discover the perfect soundtrack for your emotions.
            </p>
            <div class="hero-buttons">
                <a href="register.php" class="btn btn-primary">Get Started Free</a>
                <a href="#how-it-works" class="btn btn-secondary">Learn More</a>
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-number" data-target="1000">0</div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat">
                    <div class="stat-number" data-target="50">0</div>
                    <div class="stat-label">Moods Detected (K+)</div>
                </div>
                <div class="stat">
                    <div class="stat-number" data-target="95">0</div>
                    <div class="stat-label">Accuracy (%)</div>
                </div>
            </div>
        </div>
        <div class="hero-illustration">
            <div class="floating-emoji">😊</div>
            <div class="floating-emoji">🎉</div>
            <div class="floating-emoji">😌</div>
            <div class="floating-emoji">🎵</div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <h2 class="section-title">Powerful Features</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🧠</div>
                <h3>Machine Learning</h3>
                <p>Powered by Rubix ML for accurate mood detection based on behavioral patterns</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Real-time Analysis</h3>
                <p>Instant mood detection from mouse movements, clicks, and typing patterns</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎼</div>
                <h3>Smart Recommendations</h3>
                <p>Curated music playlists matched perfectly to your current emotional state</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Mood Tracking</h3>
                <p>Track your emotional patterns over time with beautiful visualizations</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Privacy First</h3>
                <p>Your data is encrypted and never shared. Complete control over your information</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Lightning Fast</h3>
                <p>Instant results with our optimized PHP backend and ML algorithms</p>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="how-it-works">
        <h2 class="section-title">How It Works</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Sign Up</h3>
                <p>Create your free account in seconds</p>
            </div>
            <div class="step-arrow">→</div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Interact</h3>
                <p>Move your mouse, click, and type naturally</p>
            </div>
            <div class="step-arrow">→</div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Analyze</h3>
                <p>Our AI detects your mood instantly</p>
            </div>
            <div class="step-arrow">→</div>
            <div class="step">
                <div class="step-number">4</div>
                <h3>Enjoy</h3>
                <p>Get perfect music recommendations</p>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <h2>Ready to Discover Your Perfect Soundtrack?</h2>
        <p>Join thousands of users who've found their musical soulmate</p>
        <a href="register.php" class="btn btn-large">Start Your Journey</a>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>🎵 MoodTune</h4>
                <p>AI-powered music recommendations</p>
            </div>
            <div class="footer-section">
                <h4>Product</h4>
                <a href="#features">Features</a>
                <a href="#how-it-works">How It Works</a>
                <a href="register.php">Sign Up</a>
            </div>
            <div class="footer-section">
                <h4>Technology</h4>
                <p>Powered by Rubix ML</p>
                <p>Built with PHP & MySQL</p>
            </div>
            <div class="footer-section">
                <h4>Legal</h4>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 MoodTune. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // ============================================
        // MOBILE MENU TOGGLE
        // ============================================
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const navLinks = document.getElementById('navLinks');
        const menuIcon = document.getElementById('menuIcon');
        let menuOpen = false;

        mobileMenuToggle.addEventListener('click', function() {
            menuOpen = !menuOpen;
            navLinks.classList.toggle('mobile-open');
            menuIcon.textContent = menuOpen ? '✕' : '☰';
        });

        // Close menu when clicking on a link
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 968) {
                    menuOpen = false;
                    navLinks.classList.remove('mobile-open');
                    menuIcon.textContent = '☰';
                }
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (menuOpen && !navLinks.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                menuOpen = false;
                navLinks.classList.remove('mobile-open');
                menuIcon.textContent = '☰';
            }
        });

        // ============================================
        // NAVBAR SCROLL EFFECTS
        // ============================================
        let lastScroll = 0;
        const navbar = document.getElementById('navbar');
        let ticking = false;

        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    const currentScroll = window.pageYOffset;
                    
                    if (currentScroll > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                    
                    lastScroll = currentScroll;
                    ticking = false;
                });
                ticking = true;
            }
        });

        // ============================================
        // SMOOTH SCROLL FOR ANCHOR LINKS
        // ============================================
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href !== '') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const offsetTop = target.offsetTop - 80;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });

        // ============================================
        // ANIMATED COUNTER FOR STATS
        // ============================================
        function animateCounter(element, target, suffix = '') {
            const duration = 2000;
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + suffix;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + suffix;
                }
            }, 16);
        }

        // Trigger counters when in view
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px'
        };

        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                    entry.target.classList.add('counted');
                    const statNumbers = entry.target.querySelectorAll('.stat-number');
                    
                    statNumbers.forEach(stat => {
                        const target = parseInt(stat.getAttribute('data-target'));
                        const label = stat.nextElementSibling.textContent;
                        
                        if (label.includes('K+')) {
                            animateCounter(stat, target, 'K+');
                        } else if (label.includes('%')) {
                            animateCounter(stat, target, '%');
                        } else {
                            animateCounter(stat, target, '+');
                        }
                    });
                }
            });
        }, observerOptions);

        const heroStats = document.querySelector('.hero-stats');
        if (heroStats) {
            statsObserver.observe(heroStats);
        }

        // ============================================
        // SCROLL REVEAL ANIMATIONS
        // ============================================
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, {
            threshold: 0.15,
            rootMargin: '0px'
        });

        // Add scroll reveal to sections
        const revealElements = document.querySelectorAll('.feature-card, .step, .footer-section');
        revealElements.forEach(el => {
            el.classList.add('scroll-reveal');
            revealObserver.observe(el);
        });

        // ============================================
        // PARTICLE CURSOR EFFECT (Optional)
        // ============================================
        const createParticle = (x, y) => {
            const particle = document.createElement('div');
            particle.style.position = 'fixed';
            particle.style.left = x + 'px';
            particle.style.top = y + 'px';
            particle.style.width = '4px';
            particle.style.height = '4px';
            particle.style.borderRadius = '50%';
            particle.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
            particle.style.pointerEvents = 'none';
            particle.style.zIndex = '9999';
            particle.style.opacity = '0.8';
            particle.style.transition = 'all 0.5s ease';
            
            document.body.appendChild(particle);
            
            setTimeout(() => {
                particle.style.transform = 'scale(0) translateY(-30px)';
                particle.style.opacity = '0';
            }, 10);
            
            setTimeout(() => {
                particle.remove();
            }, 500);
        };

        let particleTimer;
        document.addEventListener('mousemove', (e) => {
            clearTimeout(particleTimer);
            particleTimer = setTimeout(() => {
                if (Math.random() > 0.8) { // 20% chance
                    createParticle(e.clientX, e.clientY);
                }
            }, 50);
        });

        // ============================================
        // EMOJI INTERACTION
        // ============================================
        const emojis = document.querySelectorAll('.floating-emoji');
        emojis.forEach(emoji => {
            emoji.addEventListener('click', function() {
                // Create burst effect
                for (let i = 0; i < 8; i++) {
                    const clone = this.cloneNode(true);
                    clone.style.position = 'fixed';
                    clone.style.left = this.getBoundingClientRect().left + 'px';
                    clone.style.top = this.getBoundingClientRect().top + 'px';
                    clone.style.fontSize = '2em';
                    clone.style.zIndex = '10000';
                    clone.style.pointerEvents = 'none';
                    
                    document.body.appendChild(clone);
                    
                    const angle = (360 / 8) * i;
                    const distance = 100;
                    const x = Math.cos(angle * Math.PI / 180) * distance;
                    const y = Math.sin(angle * Math.PI / 180) * distance;
                    
                    setTimeout(() => {
                        clone.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                        clone.style.transform = `translate(${x}px, ${y}px) scale(0)`;
                        clone.style.opacity = '0';
                    }, 10);
                    
                    setTimeout(() => {
                        clone.remove();
                    }, 800);
                }
            });
        });

        // ============================================
        // BUTTON RIPPLE EFFECT
        // ============================================
        const buttons = document.querySelectorAll('.btn, .btn-primary, .btn-secondary, .btn-large');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const ripple = document.createElement('span');
                ripple.style.position = 'absolute';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.width = '0';
                ripple.style.height = '0';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                ripple.style.transform = 'translate(-50%, -50%)';
                ripple.style.animation = 'ripple 0.6s ease-out';
                ripple.style.pointerEvents = 'none';
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // ============================================
        // PARALLAX EFFECT FOR HERO SECTION
        // ============================================
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-illustration');
            
            if (hero && scrolled < window.innerHeight) {
                hero.style.transform = `translateY(${scrolled * 0.3}px)`;
            }
        });

        // ============================================
        // PERFORMANCE OPTIMIZATION
        // ============================================
        // Lazy load images if any
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        // ============================================
        // CONSOLE MESSAGE (Easter Egg)
        // ============================================
        console.log('%c🎵 MoodTune', 'font-size: 24px; font-weight: bold; color: #667eea;');
        console.log('%cMusic that understands your mood!', 'font-size: 14px; color: #764ba2;');
        console.log('%c💡 Tip: Click on the floating emojis for a surprise!', 'font-size: 12px; color: #666;');

        // ============================================
        // ACCESSIBILITY: KEYBOARD NAVIGATION
        // ============================================
        document.addEventListener('keydown', (e) => {
            // Press 'H' to go to hero
            if (e.key === 'h' || e.key === 'H') {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
            // Press 'F' to go to features
            if (e.key === 'f' || e.key === 'F') {
                document.getElementById('features')?.scrollIntoView({ behavior: 'smooth' });
            }
        });

        // ============================================
        // PERFORMANCE MONITORING
        // ============================================
        window.addEventListener('load', () => {
            const loadTime = performance.now();
            console.log(`⚡ Page loaded in ${Math.round(loadTime)}ms`);
        });
    </script>
</body>
</html>