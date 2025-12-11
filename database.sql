-- Create Database
CREATE DATABASE IF NOT EXISTS moodtune_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE moodtune_db;

-- User Sessions Table
CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Behavior Data Table
CREATE TABLE IF NOT EXISTS behavior_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) NOT NULL,
    mouse_speed FLOAT NOT NULL,
    click_rate FLOAT NOT NULL,
    typing_speed FLOAT NOT NULL,
    mouse_variance FLOAT NOT NULL,
    total_interactions INT NOT NULL,
    detected_mood VARCHAR(50),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES sessions(session_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Music Recommendations Table
CREATE TABLE IF NOT EXISTS music_library (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    artist VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    mood_category VARCHAR(50) NOT NULL,
    emoji VARCHAR(10),
    energy_level ENUM('low', 'medium', 'high') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert Sample Music Data
INSERT INTO music_library (title, artist, genre, mood_category, emoji, energy_level) VALUES
-- Happy Songs
('Happy', 'Pharrell Williams', 'Pop', 'happy', '😊', 'high'),
('Good Vibrations', 'The Beach Boys', 'Rock', 'happy', '🌊', 'high'),
('Walking on Sunshine', 'Katrina & The Waves', 'Pop', 'happy', '☀️', 'high'),
('Don\'t Stop Me Now', 'Queen', 'Rock', 'happy', '👑', 'high'),

-- Excited Songs
('Eye of the Tiger', 'Survivor', 'Rock', 'excited', '🐯', 'high'),
('Uptown Funk', 'Mark Ronson ft. Bruno Mars', 'Funk', 'excited', '🎺', 'high'),
('Can\'t Stop the Feeling!', 'Justin Timberlake', 'Pop', 'excited', '💃', 'high'),
('Shut Up and Dance', 'Walk the Moon', 'Pop', 'excited', '🕺', 'high'),

-- Calm Songs
('Weightless', 'Marconi Union', 'Ambient', 'calm', '🌙', 'low'),
('Clair de Lune', 'Claude Debussy', 'Classical', 'calm', '🎹', 'low'),
('Breathe Me', 'Sia', 'Alternative', 'calm', '🌬️', 'low'),
('The Scientist', 'Coldplay', 'Alternative', 'calm', '🔬', 'medium'),

-- Sad Songs
('Someone Like You', 'Adele', 'Pop', 'sad', '💔', 'low'),
('Fix You', 'Coldplay', 'Alternative', 'sad', '🌟', 'medium'),
('Skinny Love', 'Bon Iver', 'Indie', 'sad', '🍂', 'low'),
('The Night We Met', 'Lord Huron', 'Indie', 'sad', '🌃', 'low'),

-- Anxious Songs
('Breathe', 'Pink Floyd', 'Progressive Rock', 'anxious', '🌈', 'medium'),
('Let It Be', 'The Beatles', 'Rock', 'anxious', '☮️', 'medium'),
('Three Little Birds', 'Bob Marley', 'Reggae', 'anxious', '🐦', 'low'),
('Here Comes the Sun', 'The Beatles', 'Rock', 'anxious', '🌅', 'medium'),

-- Neutral Songs
('Perfect Day', 'Lou Reed', 'Rock', 'neutral', '🌤️', 'medium'),
('Budapest', 'George Ezra', 'Folk', 'neutral', '🏛️', 'medium'),
('Riptide', 'Vance Joy', 'Indie', 'neutral', '🌊', 'medium'),
('Ho Hey', 'The Lumineers', 'Folk', 'neutral', '🎸', 'medium');

-- User Feedback Table (Optional for improving ML model)
CREATE TABLE IF NOT EXISTS user_feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) NOT NULL,
    predicted_mood VARCHAR(50) NOT NULL,
    actual_mood VARCHAR(50),
    rating INT CHECK (rating BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES sessions(session_id) ON DELETE CASCADE
) ENGINE=InnoDB;