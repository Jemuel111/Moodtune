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
- 👤 **User Authentication** - Secure login and registration system with role-based access
- 🔍 **Music Discovery** - Browse playlists by mood with 6 distinct categories
- 📈 **Analytics Dashboard** - Beautiful visualizations of your mood data
- 🔒 **Privacy Focused** - Your data is encrypted and never shared
- 🎨 **Modern UI/UX** - Responsive design with smooth animations

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
   git clone https://github.com/Jemuel111/moodtune.git
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
   http://localhost/music-mood-recommender/public/landing.php
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
│   │   ├── style.css         # Main demo styles
│   │   ├── landing.css       # Landing page
│   │   ├── auth.css          # Login/Register
│   │   ├── dashboard.css     # Dashboard layout
│   │   ├── analyze.css       # Mood analyzer
│   │   ├── history.css       # History view
│   │   ├── discover.css      # Music discovery
│   │   └── settings.css      # Settings page
│   ├── js/                   # JavaScript modules
│   │   ├── app.js            # Main application
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
│   ├── discover.php          # Music discovery
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

Visit: `http://localhost/music-mood-recommender/public/index.php`

- Interact with the page (move mouse, click, type)
- Click "Analyze My Mood with ML"
- Get instant mood detection and music recommendations

### 2. Full Application (With Account)

1. **Landing Page**: `http://localhost/music-mood-recommender/public/landing.php`
   - Learn about features
   - Sign up or login

2. **Register**: `http://localhost/music-mood-recommender/public/register.php`
   - Create your free account
   - Secure password validation

3. **Login**: `http://localhost/music-mood-recommender/public/login.php`
   - Access with username/email + password

4. **Dashboard**: View your mood statistics and history
   - Total sessions tracked
   - Most common mood
   - Recent mood timeline
   - Mood distribution chart

5. **Analyze**: Get real-time mood analysis
   - Interactive tracking area
   - Real-time metrics display
   - ML-powered predictions
   - Instant music recommendations

6. **Discover**: Browse music by mood
   - 6 mood-based playlists
   - Personalized recommendations
   - Featured collections
   - Search functionality

7. **History**: Track your emotional patterns
   - Complete mood timeline
   - Detailed session info
   - Export capabilities
   - Filter by date range

8. **Settings**: Manage your account
   - Update profile information
   - Change password
   - Notification preferences
   - Privacy controls


## 🔧 Technology Stack

### Backend
- **PHP 7.4+** - Server-side logic
- **Rubix ML 2.0** - Machine learning library
- **MySQL 8.0** - Relational database
- **PDO** - Database abstraction layer
- **Composer** - Dependency management

### Frontend
- **HTML5/CSS3** - Structure and styling
- **Vanilla JavaScript** - Client-side interactivity
- **No frameworks** - Pure, lightweight code
- **Responsive Design** - Mobile-first approach

### Machine Learning
- **K-Nearest Neighbors (KNN)** - Classification algorithm
- **Feature Engineering** - Behavioral pattern analysis
- **Euclidean Distance** - Similarity measurement
- **Rubix ML Framework** - PHP machine learning

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
Features are normalized and fed into a K-Nearest Neighbors classifier:

```php
Energy = (speedScore + clickScore + typingScore + varianceScore) / 4
Stability = 1 - varianceScore
Mood = KNN.predict([energy, stability, speedScore, clickScore])
```

### 4. Music Recommendation
Based on detected mood, the system recommends songs from a curated database of 24+ tracks across 6 mood categories.

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

### GET `/api.php?action=get_playlist&mood=happy`
Get all songs for a specific mood

**Response:**
```json
{
  "success": true,
  "mood": "happy",
  "songs": [
    {
      "id": 1,
      "title": "Happy",
      "artist": "Pharrell Williams",
      "genre": "Pop",
      "emoji": "😊",
      "energy_level": "high"
    }
  ]
}
```

### GET `/api.php?action=get_history`
Retrieve user's mood history

### POST `/api.php?action=toggle_favorite`
Add/remove songs from favorites

### POST `/api.php?action=feedback`
Submit user feedback on mood prediction

### POST `/api.php?action=update_profile`
Update user profile information

### POST `/api.php?action=change_password`
Change user password

## 🗄️ Database Schema

### Tables

- **users** - User authentication and profiles
- **sessions** - User session tracking
- **behavior_data** - Interaction records
- **mood_history** - Historical mood data with ML predictions
- **music_library** - Curated song database (24+ tracks)
- **user_favorites** - Saved tracks per user
- **user_feedback** - Model improvement data

## 🔐 Security Features

- ✅ Password hashing with `password_hash()`
- ✅ Prepared statements (SQL injection prevention)
- ✅ Session management with regeneration
- ✅ Role-based access control (User/Admin)
- ✅ Input validation and sanitization
- ✅ XSS prevention with `htmlspecialchars()`
- ✅ CSRF protection ready

## 🎨 UI/UX Features

- 🎭 Modern gradient designs
- ✨ Smooth animations and transitions
- 📱 Fully responsive (mobile, tablet, desktop)
- 🌈 Color-coded mood indicators
- 📊 Interactive charts and visualizations
- 🔔 Toast notifications
- 🎪 Modal dialogs
- 💫 Hover effects and micro-interactions

## 🚧 Roadmap

### Phase 1 (Current)
- [x] Core ML mood detection
- [x] User authentication system
- [x] Music recommendation engine
- [x] Dashboard with analytics
- [x] Mood history tracking
- [x] Music discovery page

### Phase 2 (Upcoming)
- [ ] Spotify API integration
- [ ] Advanced ML models (LSTM/RNN)
- [ ] Real-time collaborative filtering
- [ ] Social features (share moods)
- [ ] Export data to CSV/JSON
- [ ] Mobile app (React Native)

### Phase 3 (Future)
- [ ] Voice mood detection
- [ ] Facial expression analysis
- [ ] Music streaming integration
- [ ] Playlist generation
- [ ] Community playlists
- [ ] Advanced analytics dashboard

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

1. Fork the project
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add comments to complex logic
- Test thoroughly before submitting
- Update documentation as needed

## 📝 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👨‍💻 Author

**Jemuel Jan Ballebar**
- GitHub: [@Jemuel111](https://github.com/Jemuel111)
- Email: jemuelballebar1@gmail.com

**Sam Andrei Jimenez**
- GitHub: [@Drei12345678](https://github.com/Drei12345678)
- Email: 0323-3585@lspu.edu.ph

**Joseph Balce**
- GitHub: [@jusiip123](https://github.com/jusiip123)
- Email: 0323-3564@lspu.edu.ph

## 🙏 Acknowledgments

- [Rubix ML](https://rubixml.github.io/ML/) - Powerful PHP machine learning library
- [Packagist](https://packagist.org/) - PHP package repository
- [Composer](https://getcomposer.org/) - Dependency management
- Music metadata from various open sources
- Emoji graphics from Unicode Consortium
- Inspiration from Spotify, Apple Music, and other music platforms

## 📧 Support

For support, email support@moodtune.com or open an issue on GitHub.


## 📸 Screenshots

### Landing Page
Beautiful marketing page showcasing features and benefits

### Dashboard
- Real-time mood statistics
- Recent mood history timeline
- Mood distribution charts
- Quick action buttons

### Mood Analyzer
- Interactive tracking area
- Real-time behavior metrics
- ML-powered mood detection
- Instant music recommendations

### Music Discovery
- 6 mood-based playlists
- Personalized recommendations
- Featured collections
- Search and filter

### History
- Complete mood timeline
- Detailed session information
- Export and filter options
- Visual analytics

### Settings
- Profile management
- Password security
- Notification preferences
- Privacy controls

---

**Made with ❤️ and powered by Machine Learning**

⭐ **Star this repo if you find it helpful!**

---

## 💡 Tips for Best Experience

1. **Interact naturally** - Don't try to "game" the system
2. **Use regularly** - More data = better mood insights
3. **Explore different moods** - Try the analyzer in various emotional states
4. **Save favorites** - Build your personalized music library
5. **Check history** - Review patterns to understand yourself better
6. **Give feedback** - Help improve the ML model

## 🐛 Known Issues

- ML model accuracy improves with more training data
- Some browsers may throttle mouse movement tracking
- Mobile gesture tracking is limited compared to desktop

## 🔄 Recent Updates

### v1.0.0 (2025-01-12)
- ✨ Initial release
- 🧠 ML-powered mood detection
- 🎵 24+ curated songs across 6 moods
- 👤 Full user authentication
- 📊 Analytics dashboard
- 🎼 Music discovery page
- 📈 Mood history tracking
- ⚙️ User settings
- 🔒 Security features

---

**Thank you for using MoodTune! 🎵**