// Behavior Tracking Module
const BehaviorTracker = (function() {
    const behaviorData = {
        mouseMovements: [],
        clicks: 0,
        keyPresses: [],
        startTime: Date.now(),
        lastMousePos: { x: 0, y: 0 },
        lastKeyTime: 0
    };

    function init() {
        const interactionArea = document.getElementById('interactionArea');
        const userInput = document.getElementById('userInput');

        // Track mouse movement
        interactionArea.addEventListener('mousemove', handleMouseMove);
        
        // Track clicks
        interactionArea.addEventListener('click', handleClick);
        
        // Track typing
        userInput.addEventListener('keypress', handleKeyPress);
    }

    function handleMouseMove(e) {
        const now = Date.now();
        const dx = e.clientX - behaviorData.lastMousePos.x;
        const dy = e.clientY - behaviorData.lastMousePos.y;
        const speed = Math.sqrt(dx * dx + dy * dy);
        
        behaviorData.mouseMovements.push({ speed, time: now });
        behaviorData.lastMousePos = { x: e.clientX, y: e.clientY };
        
        updateMetrics();
    }

    function handleClick() {
        behaviorData.clicks++;
        updateMetrics();
    }

    function handleKeyPress() {
        const now = Date.now();
        const timeDiff = now - behaviorData.lastKeyTime;
        behaviorData.keyPresses.push({ time: now, interval: timeDiff });
        behaviorData.lastKeyTime = now;
        updateMetrics();
    }

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

    function getFeatures() {
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

    function reset() {
        behaviorData.mouseMovements = [];
        behaviorData.clicks = 0;
        behaviorData.keyPresses = [];
        behaviorData.startTime = Date.now();
        behaviorData.lastKeyTime = 0;
        updateMetrics();
    }

    return {
        init,
        getFeatures,
        reset
    };
})();