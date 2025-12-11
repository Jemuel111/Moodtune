# 🎵 MoodTune

> AI-Powered Music Recommendations Based on User Behavior Using Machine Learning

MoodTune is an intelligent music recommendation system that analyzes your behavioral patterns (mouse movements, clicks, typing speed) to detect your current mood and recommend the perfect music to match your emotional state.

![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat&logo=mysql&logoColor=white)
![Rubix ML](https://img.shields.io/badge/Rubix%20ML-2.0+-FF6B6B?style=flat)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## ✨ Features

- 🧠 **Machine Learning Mood Detection** - Powered by Rubix ML for accurate behavioral analysis
- 🎯 **Real-time Analysis** - Instant mood detection from user interactions
- 🎼 **Smart Music Recommendations** - Curated playlists matched to your emotional state
- 📊 **Mood History Tracking** - Visualize your emotional patterns over time
- 👤 **User Authentication** - Secure login and registration system
- ❤️ **Favorites System** - Save your favorite tracks
- 📈 **Analytics Dashboard** - Beautiful visualizations of your mood data
- 🔒 **Privacy Focused** - Your data is encrypted and never shared

## 🎯 Mood Categories

MoodTune can detect 6 distinct mood states:

| Mood | Emoji | Energy Level | Characteristics |
|------|-------|--------------|-----------------|
| Happy | 😊 | High | High energy, stable interactions |
| Excited | 🎉 | High | High energy, variable patterns |
| Calm | 😌 | Low | Low energy, stable interactions |
| Sad | 😢 | Low | Low energy, variable patterns |
| Anxious | 😰 | Medium | Medium energy, high variability |
| Neutral | 😐 | Medium | Balanced, moderate patterns |

## 🚀 Quick Start

### Prerequisites

- PHP 7.4 or higher
- MySQL 8.0 or higher
- Composer (PHP package manager)
- Web server (XAMPP, WAMP, MAMP, or similar)

### Installation

1. **Clone or download the project**
   ```bash
   git clone https://github.com/yourusername/moodtune.git
   cd moodtune
   ```

2. **Install dependencies with Composer**
   ```bash
   composer install
   ```

3. **Set up the database**
   - Create a MySQL database named `moodtune_db`
   - Import the SQL schema:
     ```bash
     mysql -u root -p moodtune_db < database.sql
     ```

4. **Configure database connection**
   
   Edit `config/database.php`:
   ```php
   return [
       'host' => 'localhost',
       'database' => 'moodtune_db',
       'username' => 'root',
       'password' => 'your_password',
       'charset' => 'utf8mb4',
   ];
   ```

5. **Place project in web server directory**
   
   For XAMPP:
   ```bash
   # Move to: C:\xampp\htdocs\moodtune\
   ```
   
   For WAMP:
   ```bash
   # Move to: C:\wamp64\www\moodtune\
   ```

6. **Access in browser**
   ```
   http://localhost/moodtune/public/landing.php
   ```

## 📂 Project Structure

```
moodtune/
├── config/
│   └── database.php          # Database configuration
├── data/
│   └── training_data.json    # ML training data
├── public/
│   ├── css/                  # Stylesheets
│   │   ├── style.css
│   │   ├── landing.css
│   │   ├── auth.css
│   │   ├── dashboard.css
│   │   ├── analyze.css
│   │   └── history.css
│   ├── js/                   # JavaScript modules
│   │   ├── app.js
│   │   ├── behaviorTracker.js
│   │   ├── moodClassifier.js
│   │   └── musicRecommender.js
│   ├── index.php             # Demo page (no login)
│   ├── landing.php           # Marketing landing page
│   ├── login.php             # User login
│   ├── register.php          # User registration
│   ├── dashboard.php         # Main dashboard
│   ├── analyze.php           # Mood analyzer
│   ├── history.php           # Mood history
│   ├── favorites.php         # Favorite songs
│   ├── settings.php          # User settings
│   ├── logout.php            # Logout handler
│   └── api.php               # REST API endpoint
├── src/
│   ├── Auth.php              # Authentication logic
│   ├── Database.php          # Database connection
│   ├── BehaviorAnalyzer.php  # Behavior analysis
│   ├── MoodClassifier.php    # ML mood classification
│   └── MusicRecommender.php  # Music recommendation
├── vendor/                   # Composer dependencies
├── composer.json             # PHP dependencies
├── composer.lock            
├── database.sql              # Database schema
└── README.md
```

## 🎮 Usage

### 1. Quick Demo (No Login Required)

Visit: `http://localhost/moodtune/public/index.php`

- Interact with the page (move mouse, click, type)
- Click "Analyze My Mood with ML"
- Get instant mood detection and music recommendations

### 2. Full Application (With Account)

1. **Register**: `http://localhost/moodtune/public/register.php`
2. **Login**: `http://localhost/moodtune/public/login.php`
3. **Dashboard**: View your mood history and statistics
4. **Analyze**: Get real-time mood analysis
5. **History**: Track your emotional patterns
6. **Favorites**: Save songs you love

### Demo Accounts

For testing purposes:

| Username | Password | Role |
|----------|----------|------|
| demo | admin123 | User |
| admin | admin123 | Admin |

## 🔧 Technology Stack

### Backend
- **PHP 7.4+** - Server-side logic
- **Rubix ML 2.0** - Machine learning library
- **MySQL 8.0** - Database
- **PDO** - Database abstraction

### Frontend
- **HTML5/CSS3** - Structure and styling
- **Vanilla JavaScript** - Client-side interactivity
- **No frameworks** - Pure, lightweight code

### Machine Learning
- **K-Nearest Neighbors (KNN)** - Classification algorithm
- **Feature Engineering** - Behavioral pattern analysis
- **Euclidean Distance** - Similarity measurement

## 🧠 How It Works

### 1. Behavior Tracking
The system tracks three types of user interactions:
- **Mouse movements**: Speed and patterns
- **Clicks**: Frequency and timing
- **Typing**: Speed and intervals

### 2. Feature Extraction
Raw behavioral data is transformed into ML features:
```php
- avgMouseSpeed    // Average mouse movement speed
- clickRate        // Clicks per second
- avgTypingInterval // Time between keystrokes
- mouseVariance    // Movement pattern consistency
- totalInteractions // Overall activity level
```

### 3. Mood Classification
Features are normalized and fed into a K-Nearest Neighbors classifier trained on behavioral patterns:

```php
Energy = (speedScore + clickScore + typingScore + varianceScore) / 4
Stability = 1 - varianceScore
Mood = KNN.predict([energy, stability, speedScore, clickScore])
```

### 4. Music Recommendation
Based on detected mood, the system recommends 4 songs from a curated database of 24 tracks across 6 mood categories.

## 📊 API Endpoints

### POST `/api.php?action=analyze`
Analyze user behavior and get mood prediction

**Request:**
```json
{
  "avgMouseSpeed": 25.5,
  "clickRate": 1.2,
  "avgTypingInterval": 150,
  "mouseVariance": 320,
  "totalInteractions": 145
}
```

**Response:**
```json
{
  "success": true,
  "mood": {
    "type": "happy",
    "emoji": "😊",
    "energy": "high",
    "stability": "high",
    "confidence": 87.5,
    "description": "Your behavior indicates an energetic and positive mood!"
  },
  "recommendations": [
    {
      "title": "Happy",
      "artist": "Pharrell Williams",
      "genre": "Pop",
      "emoji": "😊"
    }
  ]
}
```

### POST `/api.php?action=feedback`
Submit user feedback on mood prediction

### GET `/api.php?action=get_history`
Retrieve user's mood history

### POST `/api.php?action=toggle_favorite`
Add/remove songs from favorites

## 🎨 Screenshots

### Landing Page
Beautiful marketing page showcasing features

### Dashboard
![Dashboard Preview]
- Statistics overview
- Recent mood history
- Mood distribution chart
- Quick actions

### Mood Analyzer
![Analyzer Preview]
- Interactive tracking area
- Real-time metrics
- ML-powered mood detection
- Music recommendations

## 🛠️ Development

### Running Locally

```bash
# Install dependencies
composer install

# Start PHP built-in server
php -S localhost:8000 -t public/

# Or use XAMPP/WAMP and visit:
http://localhost/moodtune/public/landing.php
```

### Database Schema

**Users Table**: Authentication and profile
**Sessions Table**: User session tracking
**Behavior Data**: Interaction records
**Mood History**: Historical mood data
**Music Library**: Curated song database
**User Favorites**: Saved tracks
**User Feedback**: Model improvement data

## 🔐 Security Features

- ✅ Password hashing with `password_hash()`
- ✅ Prepared statements (SQL injection prevention)
- ✅ Session management
- ✅ CSRF protection ready
- ✅ Input validation and sanitization
- ✅ XSS prevention with `htmlspecialchars()`

## 🚧 Roadmap

- [ ] Spotify API integration
- [ ] Deep learning models (LSTM/RNN)
- [ ] Real-time mood updates
- [ ] Social features (share moods)
- [ ] Mobile app (React Native)
- [ ] Advanced analytics
- [ ] Mood prediction history graphs
- [ ] Export data to CSV/JSON

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👨‍💻 Author

**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

## 🙏 Acknowledgments

- [Rubix ML](https://rubixml.github.io/ML/) - Machine Learning library
- [Packagist](https://packagist.org/) - PHP package repository
- Music metadata from various sources
- Emoji graphics from Unicode Consortium

## 📧 Support

For support, email support@moodtune.com or open an issue on GitHub.

---

**Made with ❤️ and powered by Machine Learning**

⭐ Star this repo if you find it helpful!