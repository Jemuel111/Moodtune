// Music Recommendation Module
const MusicRecommender = (function() {
    
    const musicDatabase = {
        happy: [
            { title: 'Happy', artist: 'Pharrell Williams', emoji: '😊', genre: 'Pop' },
            { title: 'Good Vibrations', artist: 'The Beach Boys', emoji: '🌊', genre: 'Rock' },
            { title: 'Walking on Sunshine', artist: 'Katrina & The Waves', emoji: '☀️', genre: 'Pop' },
            { title: 'Don\'t Stop Me Now', artist: 'Queen', emoji: '👑', genre: 'Rock' }
        ],
        excited: [
            { title: 'Eye of the Tiger', artist: 'Survivor', emoji: '🐯', genre: 'Rock' },
            { title: 'Uptown Funk', artist: 'Mark Ronson ft. Bruno Mars', emoji: '🎺', genre: 'Funk' },
            { title: 'Can\'t Stop the Feeling!', artist: 'Justin Timberlake', emoji: '💃', genre: 'Pop' },
            { title: 'Shut Up and Dance', artist: 'Walk the Moon', emoji: '🕺', genre: 'Pop' }
        ],
        calm: [
            { title: 'Weightless', artist: 'Marconi Union', emoji: '🌙', genre: 'Ambient' },
            { title: 'Clair de Lune', artist: 'Claude Debussy', emoji: '🎹', genre: 'Classical' },
            { title: 'Breathe Me', artist: 'Sia', emoji: '🌬️', genre: 'Alternative' },
            { title: 'The Scientist', artist: 'Coldplay', emoji: '🔬', genre: 'Alternative' }
        ],
        sad: [
            { title: 'Someone Like You', artist: 'Adele', emoji: '💔', genre: 'Pop' },
            { title: 'Fix You', artist: 'Coldplay', emoji: '🌟', genre: 'Alternative' },
            { title: 'Skinny Love', artist: 'Bon Iver', emoji: '🍂', genre: 'Indie' },
            { title: 'The Night We Met', artist: 'Lord Huron', emoji: '🌃', genre: 'Indie' }
        ],
        anxious: [
            { title: 'Breathe', artist: 'Pink Floyd', emoji: '🌈', genre: 'Progressive Rock' },
            { title: 'Let It Be', artist: 'The Beatles', emoji: '☮️', genre: 'Rock' },
            { title: 'Three Little Birds', artist: 'Bob Marley', emoji: '🐦', genre: 'Reggae' },
            { title: 'Here Comes the Sun', artist: 'The Beatles', emoji: '🌅', genre: 'Rock' }
        ],
        neutral: [
            { title: 'Perfect Day', artist: 'Lou Reed', emoji: '🌤️', genre: 'Rock' },
            { title: 'Budapest', artist: 'George Ezra', emoji: '🏛️', genre: 'Folk' },
            { title: 'Riptide', artist: 'Vance Joy', emoji: '🌊', genre: 'Indie' },
            { title: 'Ho Hey', artist: 'The Lumineers', emoji: '🎸', genre: 'Folk' }
        ]
    };

    function getRecommendations(moodType) {
        return musicDatabase[moodType] || musicDatabase.neutral;
    }

    function displayRecommendations(songs) {
        const songList = document.getElementById('songList');
        
        songList.innerHTML = songs.map(song => `
            <div class="song-card">
                <div class="song-icon">${song.emoji}</div>
                <div class="song-info">
                    <h4>${song.title}</h4>
                    <p>${song.artist} • ${song.genre}</p>
                </div>
            </div>
        `).join('');
    }

    return {
        getRecommendations,
        displayRecommendations
    };
})();