<?php
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weather Forecast - WeatherWear</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --dark-bg: linear-gradient(135deg, #30343F 0%, #1D1E2C 100%);
      --light-text: #ffffff;
      --dark-text: #333333;
      --white: #ffffff;
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: var(--primary-bg);
      background-size: cover;
      color: var(--light-text);
      min-height: 100vh;
      transition: background 0.5s ease;
    }

    body.dark-theme {
      background: var(--dark-bg);
    }

    /* Header & Navbar */
    .header {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.18);
      padding: 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .logo img {
      width: 40px;
      height: 40px;
    }

    .logo-text {
      font-size: 1.5rem;
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    .navbar {
      display: flex;
      gap: 1.5rem;
    }

    .nav-link {
      color: var(--white);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 0.75rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .nav-link:hover, .nav-link.active {
      background: rgba(255, 255, 255, 0.2);
    }

    .icon {
      width: 24px;
      height: 24px;
    }

    /* Main Container */
    .main-container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
    }

    /* Title and Search Section */
    .page-title {
      text-align: center;
      margin-bottom: 2rem;
    }

    .page-title h1 {
      font-size: 2.5rem;
      margin-bottom: 0.5rem;
    }

    .page-title p {
      font-size: 1.2rem;
      opacity: 0.9;
    }

    .search-section {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-radius: 16px;
      padding: 2rem;
      margin-bottom: 3rem;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .search-input {
      display: flex;
      max-width: 600px;
      margin: 0 auto;
    }

    .search-input input {
      flex: 1;
      padding: 1rem 1.5rem;
      border: none;
      border-radius: 8px 0 0 8px;
      background: rgba(255, 255, 255, 0.2);
      color: var(--white);
      font-size: 1rem;
      outline: none;
      transition: all 0.3s ease;
    }

    .search-input input::placeholder {
      color: rgba(255, 255, 255, 0.7);
    }

    .search-input input:focus {
      background: rgba(255, 255, 255, 0.3);
    }

    .search-button {
      padding: 1rem 1.5rem;
      background: rgba(255, 255, 255, 0.3);
      color: var(--white);
      border: none;
      border-radius: 0 8px 8px 0;
      cursor: pointer;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    .search-button:hover {
      background: rgba(255, 255, 255, 0.4);
    }

    /* Forecast Section */
    .forecast-section {
      margin-top: 2rem;
      display: none;
    }

    .forecast-section h2 {
      font-size: 1.75rem;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .forecast-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 0.75rem;
      margin-top: 1rem;
      overflow-x: auto;
      padding: 0.5rem;
      scroll-snap-type: x mandatory;
      -webkit-overflow-scrolling: touch;
    }

    .forecast-card {
      min-width: 160px;
      padding: 1.25rem;
      border-radius: 16px;
      scroll-snap-align: start;
      height: 100%;
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .forecast-card:hover {
      transform: translateY(-5px);
      background: rgba(255, 255, 255, 0.15);
      border-color: rgba(255, 255, 255, 0.3);
      box-shadow: 0 8px 32px rgba(31, 38, 135, 0.2);
    }

    /* Card Header Section */
    .forecast-card-header {
      padding-bottom: 0.75rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      text-align: center;
    }

    .forecast-card .day {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
      color: rgba(255, 255, 255, 0.95);
    }

    .forecast-card .date {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.8);
    }

    /* Weather Section */
    .forecast-card-weather {
      padding: 0.75rem 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      text-align: center;
    }

    .forecast-card .weather-icon {
      width: 50px;
      height: 50px;
      margin: 0 auto 0.5rem;
      transition: transform 0.3s ease;
    }

    .forecast-card:hover .weather-icon {
      transform: scale(1.1);
    }

    .forecast-card .temp {
      font-size: 1.1rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
      color: rgba(255, 255, 255, 0.95);
    }

    .forecast-card .weather {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.9);
    }

    /* Outfit Section */
    .forecast-card-outfit {
      padding-top: 0.75rem;
      text-align: center;
    }

    .forecast-card .outfit-icons {
      display: flex;
      justify-content: center;
      gap: 0.5rem;
      margin-bottom: 0.75rem;
      min-height: 1.5rem;
      padding: 0.5rem;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .forecast-card:hover .outfit-icons {
      background: rgba(255, 255, 255, 0.1);
      transform: scale(1.05);
    }

    .forecast-card .outfit-icons span {
      transition: transform 0.3s ease;
    }

    .forecast-card .outfit-icons span:hover {
      transform: scale(1.2) rotate(10deg);
    }

    .forecast-card .outfit {
      font-size: 0.9rem;
      line-height: 1.4;
      color: rgba(255, 255, 255, 0.85);
      padding: 0.75rem;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .forecast-card:hover .outfit {
      background: rgba(255, 255, 255, 0.08);
    }

    /* Card Animation */
    @keyframes cardEntrance {
      from {
        opacity: 0;
        transform: translateY(25px) scale(0.9);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .forecast-card {
      animation: cardEntrance 0.6s cubic-bezier(0.4, 0, 0.2, 1) both;
      animation-play-state: paused;
    }

    .forecast-card.show {
      animation-play-state: running;
    }

    /* Staggered animations for card contents */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .forecast-card-header,
    .forecast-card-weather,
    .forecast-card-outfit {
      opacity: 0;
      animation: fadeInUp 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
      animation-play-state: paused;
    }

    .forecast-card.show .forecast-card-header {
      animation-delay: 0.2s;
      animation-play-state: running;
    }

    .forecast-card.show .forecast-card-weather {
      animation-delay: 0.4s;
      animation-play-state: running;
    }

    .forecast-card.show .forecast-card-outfit {
      animation-delay: 0.6s;
      animation-play-state: running;
    }

    /* Add smooth scrolling for the forecast grid */
    .forecast-grid::-webkit-scrollbar {
      height: 6px;
    }

    .forecast-grid::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 3px;
    }

    .forecast-grid::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.3);
      border-radius: 3px;
    }

    .forecast-grid::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.4);
    }

    /* Loading Spinner */
    .loading-spinner {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .loading-spinner.show {
      opacity: 1;
      visibility: visible;
    }

    .spinner {
      width: 60px;
      height: 60px;
      border: 4px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      border-top-color: #fff;
      animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Media Queries */
    @media (max-width: 1200px) {
      .forecast-grid {
        grid-template-columns: repeat(5, 1fr);
        overflow-x: auto;
      }
    }

    @media (max-width: 768px) {
      .forecast-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
      }

      .forecast-card {
        min-width: 140px;
        padding: 0.75rem;
      }
    }

    @media (max-width: 480px) {
      .forecast-grid {
        grid-template-columns: repeat(2, 1fr);
      }

      .forecast-card {
        min-width: 130px;
      }

      .forecast-section h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body class="<?php echo isset($_COOKIE['theme']) && $_COOKIE['theme'] === 'dark' ? 'dark-theme' : ''; ?>">
  <div class="header">
    <div class="logo">
      <span class="logo-text">WeatherWear</span>
    </div>
    <div class="navbar">
      <a href="home.php" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
          <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
        </svg>
        Home
      </a>
      <a href="forecast.php" class="nav-link active">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
          <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" />
        </svg>
        Forecast
      </a>
      <a href="about.php" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
        </svg>
        About
      </a>
      <?php if ($username): ?>
      <a href="profile.php" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
        </svg>
        <?php echo $username; ?>
      </a>
      <?php else: ?>
      <a href="login.php" class="nav-link">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
          <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
        </svg>
        Login
      </a>
      <?php endif; ?>
    </div>
  </div>

  <div class="main-container">
    <div class="page-title">
      <h1>5-Day Weather Forecast</h1>
      <p>Check the weather forecast and get personalized outfit recommendations</p>
    </div>

    <div class="search-section">
      <h2>Enter a city to get started</h2>
      <div class="search-input">
        <input type="text" id="cityInput" placeholder="Enter city name (e.g., London, Tokyo, New York)">
        <button id="searchBtn" class="search-button">
          <i class="fas fa-search"></i> Search
        </button>
      </div>
    </div>

    <div class="forecast-section" id="forecastSection">
      <h2>5-Day Forecast</h2>
      <div class="forecast-grid" id="forecastGrid">
        <!-- Forecast cards will be inserted here -->
      </div>
    </div>
  </div>

  <div class="loading-spinner" id="loadingSpinner">
    <div class="spinner"></div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const cityInput = document.getElementById('cityInput');
      const searchBtn = document.getElementById('searchBtn');
      const forecastSection = document.getElementById('forecastSection');
      const forecastGrid = document.getElementById('forecastGrid');
      const loadingSpinner = document.getElementById('loadingSpinner');

      // Check for theme preference
      const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
      if (prefersDarkScheme.matches && !document.cookie.includes('theme=light')) {
        document.body.classList.add('dark-theme');
      }

      // Function to show the loading spinner
      function showLoader() {
        loadingSpinner.classList.add('show');
      }

      // Function to hide the loading spinner
      function hideLoader() {
        loadingSpinner.classList.remove('show');
      }

      // Format date function
      function formatDate(dateString) {
        const options = { month: 'short', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('en-US', options);
      }

      // Reset animations for multiple searches
      function resetAnimations() {
        const cards = document.querySelectorAll('.forecast-card');
        cards.forEach(card => {
          card.classList.remove('show');
          void card.offsetWidth; // Trigger reflow
          card.classList.add('show');
        });
      }

      // Function to get outfit suggestion based on temperature
      function getOutfitSuggestion(temp, weatherDescription) {
        let suggestion = '';
        const weatherLower = weatherDescription.toLowerCase();
        
        if (temp >= 30) {
          suggestion = 'Light, loose cotton clothing, sunglasses, and a hat. Stay hydrated!';
        } else if (temp >= 25) {
          suggestion = 'Light cotton clothing, shorts/skirt, and a t-shirt. Sunscreen recommended!';
        } else if (temp >= 20) {
          suggestion = 'Light pants or shorts, short-sleeve shirt, light jacket for evening.';
        } else if (temp >= 15) {
          suggestion = 'Long pants, long-sleeve shirt, light jacket or sweater.';
        } else if (temp >= 10) {
          suggestion = 'Layers, medium-weight jacket, scarf, and closed shoes.';
        } else if (temp >= 5) {
          suggestion = 'Warm layers, jacket, hat, scarf, and gloves recommended.';
        } else if (temp >= 0) {
          suggestion = 'Heavy coat, warm layers, hat, scarf, gloves, and warm boots.';
        } else {
          suggestion = 'Very warm winter coat, thermal layers, hat, scarf, gloves, and insulated boots.';
        }
        
        // Add rain-specific suggestions
        if (weatherLower.includes('rain') || weatherLower.includes('drizzle') || weatherLower.includes('shower')) {
          suggestion += ' Bring an umbrella and wear waterproof shoes.';
        }
        
        // Add snow-specific suggestions
        if (weatherLower.includes('snow') || weatherLower.includes('sleet')) {
          suggestion += ' Wear waterproof boots and warm, water-resistant outerwear.';
        }
        
        return suggestion;
      }

      // Function to generate outfit icons based on temperature and weather
      function getOutfitIcons(temp, weather) {
        let icons = '';
        const weatherLower = weather.toLowerCase();
        
        // Add clothing based on temperature
        if (temp >= 30) {
          icons += '👕 👒 🕶️ ';  // T-shirt, hat, sunglasses
        } else if (temp >= 25) {
          icons += '👕 🩳 ';  // T-shirt, shorts
        } else if (temp >= 20) {
          icons += '👕 👖 ';  // T-shirt, pants
        } else if (temp >= 15) {
          icons += '👕 👖 ';  // Long sleeve, pants
        } else if (temp >= 10) {
          icons += '👕 👖 🧥 ';  // Layers, jacket
        } else if (temp >= 5) {
          icons += '🧥 🧣 ';  // Coat, scarf
        } else if (temp >= 0) {
          icons += '🧥 🧣 🧤 ';  // Heavy coat, scarf, gloves
        } else {
          icons += '🧥 🧣 🧤 🥾 ';  // Winter gear
        }
        
        // Add weather-specific items
        if (weatherLower.includes('rain') || weatherLower.includes('drizzle')) {
          icons += '☔ 🥾 ';  // Umbrella, boots
        }
        
        if (weatherLower.includes('snow')) {
          icons += '🥾 ';  // Snow boots
        }
        
        if (weatherLower.includes('clear') && temp > 20) {
          icons += '🕶️ ';  // Sunglasses for sunny days
        }
        
        if (weatherLower.includes('wind') || weatherLower.includes('breez')) {
          icons += '🧢 ';  // Cap for windy days
        }
        
        return icons;
      }
  
      // Function to get weather forecast
      async function getForecast() {
        showLoader();
        
        const city = cityInput.value.trim();
        if (!city) {
          hideLoader();
          alert('Please enter a city name');
          return;
        }
        
        try {
          const response = await fetch(`get_forecast.php?city=${encodeURIComponent(city)}`);
          const data = await response.json();
          
          if (data.error) {
            hideLoader();
            alert(data.error);
            return;
          }
          
          // Clear previous forecast
          forecastGrid.innerHTML = '';
          
          // Display forecast with unique days
          let uniqueDays = new Set();
          let count = 0;
          
          for (const day of data) {
            // Get the day name (e.g., Monday)
            const dayName = new Date(day.date).toLocaleDateString('en-US', { weekday: 'long' });
            
            // Check if we've already displayed this day
            if (!uniqueDays.has(dayName)) {
              uniqueDays.add(dayName);
              
              // Generate outfit icons and suggestion
              const outfitIcons = getOutfitIcons(day.temp_max, day.description);
              const outfitSuggestion = getOutfitSuggestion(day.temp_max, day.description);
              
              // Create forecast card
              const card = document.createElement('div');
              card.className = 'forecast-card';
              card.innerHTML = `
                <div class="forecast-card-header">
                  <div class="day">${dayName}</div>
                  <div class="date">${formatDate(day.date)}</div>
                </div>
                <div class="forecast-card-weather">
                  <img class="weather-icon" src="https://openweathermap.org/img/wn/${day.icon}@2x.png" alt="${day.description}">
                  <div class="temp">${Math.round(day.temp_max)}°C / ${Math.round(day.temp_min)}°C</div>
                  <div class="weather">${day.description}</div>
                </div>
                <div class="forecast-card-outfit">
                  <div class="outfit-icons">${outfitIcons}</div>
                  <div class="outfit">${outfitSuggestion}</div>
                </div>
              `;
              forecastGrid.appendChild(card);
              
              count++;
              if (count >= 5) break; // Stop after 5 days
            }
          }
          
          // Show forecast section
          forecastSection.style.display = 'block';
          
          // Reset animations for multiple searches
          resetAnimations();
          
          hideLoader();
        } catch (error) {
          console.error('Error fetching forecast:', error);
          hideLoader();
          alert('Failed to fetch forecast data. Please try again.');
        }
      }
      
      // Event listeners
      searchBtn.addEventListener('click', getForecast);
      cityInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          getForecast();
        }
      });
    });
  </script>
</body>
</html> 