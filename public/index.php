<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoodTune - PHP ML Music Recommender</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>🎵 HearYou</h1>
        <p class="subtitle">PHP Machine Learning Music Recommendations</p>
        <div class="tech-badge">Powered by Rubix ML + PHP</div>

        <div class="info-box">
            <strong>How it works:</strong> Interact with the area below! Our PHP backend uses <strong>Rubix ML</strong> (from Packagist.org) to analyze your behavior and detect your mood.
        </div>

        <div class="interaction-area" id="interactionArea">
            <h3>👆 Interact Here!</h3>
            <p>Move your mouse • Click around • Type something</p>
            <input type="text" placeholder="Type how you feel..." id="userInput">
        </div>

        <div class="metrics" id="metrics">
            <div class="metric-card">
                <div class="metric-value" id="mouseSpeed">0</div>
                <div class="metric-label">Mouse Speed</div>
            </div>
            <div class="metric-card">
                <div class="metric-value" id="clickCount">0</div>
                <div class="metric-label">Clicks</div>
            </div>
            <div class="metric-card">
                <div class="metric-value" id="typingSpeed">0</div>
                <div class="metric-label">Typing Speed</div>
            </div>
            <div class="metric-card">
                <div class="metric-value" id="interactions">0</div>
                <div class="metric-label">Interactions</div>
            </div>
        </div>

        <button id="analyzeBtn">🎯 Analyze My Mood with ML</button>

        <div id="results" style="display: none;">
            <div class="mood-status">
                <h3>Your Detected Mood (ML Prediction)</h3>
                <div class="mood-indicator" id="moodEmoji">😊</div>
                <h2 id="moodText">Happy</h2>
                <p id="moodDescription"></p>
                <p id="confidence" style="font-size: 0.9em; opacity: 0.9;"></p>
            </div>

            <div class="recommendations">
                <h3 style="color: #667eea; margin-bottom: 20px;">🎼 From Database</h3>
                <div id="songList"></div>
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>