-- Create Database
CREATE DATABASE IF NOT EXISTS moodtune_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE moodtune_db;

-- ============================================
-- USERS TABLE (MISSING IN YOUR VERSION!)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('user', 'admin') DEFAULT 'user',
    avatar_url VARCHAR(255) DEFAULT 'https://ui-avatars.com/api/?name=User&background=667eea&color=fff',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- ============================================
-- SESSIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_session_id (session_id)
) ENGINE=InnoDB;

-- ============================================
-- BEHAVIOR DATA TABLE
-- ============================================
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
    FOREIGN KEY (session_id) REFERENCES sessions(session_id) ON DELETE CASCADE,
    INDEX idx_session_mood (session_id, detected_mood)
) ENGINE=InnoDB;

-- ============================================
-- MOOD HISTORY TABLE (FOR USER TRACKING)
-- ============================================
CREATE TABLE IF NOT EXISTS mood_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_id VARCHAR(255) NOT NULL,
    detected_mood VARCHAR(50) NOT NULL,
    energy_level ENUM('low', 'medium', 'high') NOT NULL,
    confidence_score DECIMAL(5,2) NOT NULL,
    mouse_speed FLOAT,
    click_rate FLOAT,
    typing_speed FLOAT,
    total_interactions INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (session_id) REFERENCES sessions(session_id) ON DELETE CASCADE,
    INDEX idx_user_date (user_id, created_at),
    INDEX idx_mood (detected_mood)
) ENGINE=InnoDB;

-- ============================================
-- MUSIC LIBRARY TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS music_library (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    artist VARCHAR(255) NOT NULL,
    genre VARCHAR(100) NOT NULL,
    mood_category VARCHAR(50) NOT NULL,
    emoji VARCHAR(10),
    energy_level ENUM('low', 'medium', 'high') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_mood_category (mood_category),
    INDEX idx_en