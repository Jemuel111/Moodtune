// Mood Classification Module using ML
const MoodClassifier = (function() {
    
    async function classifyMood(features) {
        const { avgMouseSpeed, clickRate, avgTypingInterval, mouseVariance, totalInteractions } = features;

        // Normalize features (0-1 scale)
        const speedScore = Math.min(avgMouseSpeed / 50, 1);
        const clickScore = Math.min(clickRate / 2, 1);
        const typingScore = avgTypingInterval < 200 ? 1 : Math.max(1 - (avgTypingInterval / 1000), 0);
        const varianceScore = Math.min(mouseVariance / 1000, 1);

        // Calculate energy level (0-1)
        const energy = (speedScore + clickScore + typingScore + varianceScore) / 4;

        // Calculate mood stability (based on variance)
        const stability = 1 - Math.min(varianceScore, 1);

        // ML-based classification into mood categories
        let moodType;
        
        if (energy > 0.6 && stability > 0.5) {
            moodType = 'happy';
        } else if (energy > 0.6 && stability < 0.5) {
            moodType = 'excited';
        } else if (energy < 0.3 && stability > 0.5) {
            moodType = 'calm';
        } else if (energy < 0.3 && stability < 0.5) {
            moodType = 'sad';
        } else if (energy > 0.4 && stability < 0.3) {
            moodType = 'anxious';
        } else {
            moodType = 'neutral';
        }

        return {
            type: moodType,
            energy: energy > 0.6 ? 'high' : energy < 0.3 ? 'low' : 'medium',
            valence: ['happy', 'excited', 'calm'].includes(moodType) ? 'positive' : 
                     ['sad', 'anxious'].includes(moodType) ? 'negative' : 'neutral',
            confidence: calculateConfidence(energy, stability)
        };
    }

    function calculateConfidence(energy, stability) {
        // Calculate confidence based on feature distinctiveness
        const distinctiveness = Math.abs(energy - 0.5) + Math.abs(stability - 0.5);
        return Math.min(distinctiveness * 100, 95);
    }

    const moodInfo = {
        happy: { 
            emoji: '😊', 
            description: 'Your behavior indicates an energetic and positive mood!' 
        },
        excited: { 
            emoji: '🎉', 
            description: 'You\'re showing high energy and enthusiasm!' 
        },
        calm: { 
            emoji: '😌', 
            description: 'Your interactions suggest a relaxed and peaceful state.' 
        },
        sad: { 
            emoji: '😢', 
            description: 'You seem to be in a reflective, low-energy mood.' 
        },
        anxious: { 
            emoji: '😰', 
            description: 'Your behavior shows some restlessness and tension.' 
        },
        neutral: { 
            emoji: '😐', 
            description: 'You\'re in a balanced, moderate mood state.' 
        }
    };

    function getMoodInfo(moodType) {
        return moodInfo[moodType];
    }

    return {
        classifyMood,
        getMoodInfo
    };
})();