const behaviorData = {
    mouseMovements: [],
    clicks: 0,
    keyPresses: [],
    startTime: Date.now(),
    lastMousePos: { x: 0, y: 0 },
    lastKeyTime: 0
};

const interactionArea = document.getElementById('interactionArea');
const userInput = document.getElementById('userInput');
const analyzeBtn = document.getElementById('analyzeBtn');

// Track mouse movement
interactionArea.addEventListener('mousemove', (e) => {
    const now = Date.now();
    const dx = e.clientX - behaviorData.lastMousePos.x;
    const dy = e.clientY - behaviorData.lastMousePos.y;
    const speed = Math.sqrt(dx * dx + dy * dy);
    
    behaviorData.mouseMovements.push({ speed, time: now });
    behaviorData.lastMousePos = { x: e.clientX, y: e.clientY };
    
    updateMetrics();
});

// Track clicks
interactionArea.addEventListener('click', () => {
    behaviorData.clicks++;
    updateMetrics();
});

// Track typing
userInput.addEventListener('keypress', () => {
    const now = Date.now();
    const timeDiff = now - behaviorData.lastKeyTime;
    behaviorData.keyPresses.push({ time: now, interval: timeDiff });
    behaviorData.lastKeyTime = now;
    updateMetrics();
});

function updateMetrics() {
    const avgMouseSpeed = behaviorData.mouseMovements.length > 0
        ? Math.round(behaviorData.mouseMovements.slice(-10).reduce((a, b) => a + b.speed, 0) / 10)
        : 0;
    
    const avgTypingSpeed = behaviorData.keyPresses.length > 1
        ? Math.round(1000 / (behaviorData.keyPresses.slice(-5).reduce((a, b) => a + b.interval, 0) / 5))
        : 0;

    document.getElementById('mouseSpeed').textContent = avgMouseSpeed;
    document.getElementById('clickCount').textContent = behaviorData.clicks;
    document.getElementById('typingSpeed').textContent = avgTypingSpeed;
    document.getElementById('interactions').textContent = 
        behaviorData.mouseMovements.length + behaviorData.clicks + behaviorData.keyPresses.length;
}

function calculateFeatures() {
    const totalTime = (Date.now() - behaviorData.startTime) / 1000;
    
    const avgMouseSpeed = behaviorData.mouseMovements.length > 0
        ? behaviorData.mouseMovements.reduce((a, b) => a + b.speed, 0) / behaviorData.mouseMovements.length
        : 0;
    
    const clickRate = behaviorData.clicks / totalTime;
    
    const avgTypingInterval = behaviorData.keyPresses.length > 1
        ? behaviorData.keyPresses.slice(1).reduce((a, b) => a + b.interval, 0) / (behaviorData.keyPresses.length - 1)
        : 500;

    const mouseVariance = calculateVariance(behaviorData.mouseMovements.map(m => m.speed));
    
    return {
        avgMouseSpeed,
        clickRate,
        avgTypingInterval,
        mouseVariance,
        totalInteractions: behaviorData.mouseMovements.length + behaviorData.clicks + behaviorData.keyPresses.length
    };
}

function calculateVariance(arr) {
    if (arr.length === 0) return 0;
    const mean = arr.reduce((a, b) => a + b, 0) / arr.length;
    return arr.reduce((a, b) => a + Math.pow(b - mean, 2), 0) / arr.length;
}

// Analyze mood using PHP backend
analyzeBtn.addEventListener('click', async () => {
    analyzeBtn.disabled = true;
    analyzeBtn.textContent = '🔄 Analyzing with ML...';

    try {
        const features = calculateFeatures();

        // Send to PHP backend
        const response = await fetch('api.php?action=analyze', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(features)
        });

        const data = await response.json();

        if (data.success) {
            displayResults(data.mood, data.recommendations);
        } else {
            alert('Error: ' + data.error);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to analyze mood. Please try again.');
    } finally {
        analyzeBtn.disabled = false;
        analyzeBtn.textContent = '🎯 Analyze Again';
    }
});

function displayResults(mood, songs) {
    const resultsDiv = document.getElementById('results');
    const moodEmoji = document.getElementById('moodEmoji');
    const moodText = document.getElementById('moodText');
    const moodDescription = document.getElementById('moodDescription');
    const confidence = document.getElementById('confidence');
    const songList = document.getElementById('songList');

    // Update mood display
    moodEmoji.textContent = mood.emoji;
    moodText.textContent = mood.type.charAt(0).toUpperCase() + mood.type.slice(1);
    moodDescription.textContent = mood.description;
    confidence.textContent = `Confidence: ${Math.round(mood.confidence)}% | Energy: ${mood.energy} | Stability: ${mood.stability}`;

    // Display songs
    songList.innerHTML = songs.map(song => `
        <div class="song-card">
            <div class="song-icon">${song.emoji}</div>
            <div class="song-info">
                <h4>${song.title}</h4>
                <p>${song.artist} • ${song.genre}</p>
            </div>
        </div>
    `).join('');

    resultsDiv.style.display = 'block';
    resultsDiv.scrollIntoView({ behavior: 'smooth' });
}