<?php
session_start();
$loggedIn = isset($_SESSION['user_id']) || isset($_SESSION['email']);

// Debug message
echo "<!-- Landing page loaded -->";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WeatherWear - Weather-Based Outfit Suggestions</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary-color: #4361ee;
      --secondary-color: #7209b7;
      --accent-color: #4cc9f0;
      --dark-color: #18151f;
      --light-color: #f7f7f9;
      --success-color: #06d6a0;
      --warning-color: #ff9e00;
      --danger-color: #ef476f;
      --gradient-1: linear-gradient(135deg, #4361ee, #3a0ca3);
      --gradient-2: linear-gradient(135deg, #7209b7, #4361ee);
      --gradient-3: linear-gradient(135deg, #4cc9f0, #4361ee);
      --bg-gradient: linear-gradient(135deg, #4cc9f0 0%, #3a0ca3 100%);
      --card-gradient: linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.05));
      --box-shadow: 0 10px 30px -15px rgba(0, 0, 0, 0.3);
      
      /* Weather theme colors */
      --sunny-gradient: linear-gradient(135deg, #f9c846 0%, #f86f03 100%);
      --rainy-gradient: linear-gradient(135deg, #4c83b6 0%, #2155cd 100%);
      --cloudy-gradient: linear-gradient(135deg, #94a3b8 0%, #475569 100%);
      --cold-gradient: linear-gradient(135deg, #a5f3fc 0%, #0284c7 100%);
      --stormy-gradient: linear-gradient(135deg, #312e81 0%, #1e1b4b 100%);
    }
    
    body {
      font-family: 'Poppins', sans-serif;
      background: var(--bg-gradient);
      background-attachment: fixed;
      color: var(--light-color);
      line-height: 1.6;
      position: relative;
    }
    
    /* Weather background styles */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background-image: url('https://images.unsplash.com/photo-1536514498073-50e69d39c6cf?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80');
      background-size: cover;
      background-position: center;
      opacity: 0;
      transition: opacity 2s ease;
    }
    
    /* Create separate overlay elements for each weather type instead of changing background-image property */
    .weather-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background-size: cover;
      background-position: center;
      opacity: 0;
      transition: opacity 2s ease;
      pointer-events: none;
    }
    
    .weather-overlay.sunny {
      background-image: url('https://images.unsplash.com/photo-1536514498073-50e69d39c6cf?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80');
    }
    
    .weather-overlay.rainy {
      background-image: url('https://images.unsplash.com/photo-1519692933481-e162a57d6721?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80');
    }
    
    .weather-overlay.cloudy {
      background-image: url('https://images.unsplash.com/photo-1534088568595-a066f410bcda?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2071&q=80');
    }
    
    .weather-overlay.snowy {
      background-image: url('https://images.unsplash.com/photo-1551582045-6ec9c11d8697?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2071&q=80');
    }
    
    .weather-overlay.stormy {
      background-image: url('https://images.unsplash.com/photo-1605727216801-e27ce1d0cc28?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2071&q=80');
    }
    
    .weather-overlay.active {
      opacity: 1;
    }
    
    /* Remove old weather-bg classes since we'll be using a different approach */
    body.weather-bg-sunny::before,
    body.weather-bg-rainy::before,
    body.weather-bg-cloudy::before,
    body.weather-bg-snowy::before,
    body.weather-bg-stormy::before {
      opacity: 0;
    }
    
    /* Dark overlay for better readability */
    body::after {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background-color: rgba(0, 0, 0, 0.4);
    }
    
    /* Weather theme classes - kept for compatibility with JS */
    body.theme-sunny {}
    body.theme-rainy {}
    body.theme-cloudy {}
    body.theme-cold {}
    body.theme-stormy {}
    
    /* Weather indicator in the header */
    .weather-theme-indicator {
      display: inline-flex;
      align-items: center;
      margin-left: 1rem;
      padding: 0.25rem 0.75rem;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 1rem;
      font-size: 0.875rem;
      transition: all 0.5s ease;
    }
    
    .weather-theme-indicator i {
      margin-right: 0.5rem;
      font-size: 1rem;
    }
    
    .glass {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 1.5rem;
      border: 1px solid rgba(255, 255, 255, 0.18);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      overflow: hidden;
    }
    
    .glass:hover {
      transform: translateY(-10px) scale(1.02);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .nav-glass {
      background: rgba(23, 23, 33, 0.8);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .hero-gradient {
      background: var(--gradient-1);
    }
    
    .image-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(to right, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.3) 100%);
    }
    
    .feature-icon {
      font-size: 2.5rem;
      background: var(--gradient-3);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 1.5rem;
      position: relative;
      z-index: 1;
    }
    
    .feature-icon::after {
      content: '';
      position: absolute;
      width: 50px;
      height: 50px;
      background: rgba(76, 201, 240, 0.1);
      border-radius: 50%;
      z-index: -1;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    
    .btn-primary {
      background: var(--gradient-2);
      color: white;
      transition: all 0.4s ease;
      position: relative;
      z-index: 1;
      overflow: hidden;
      border: none;
    }
    
    .btn-primary:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 0;
      height: 100%;
      background: var(--gradient-3);
      transition: all 0.5s ease-in-out;
      z-index: -1;
    }
    
    .btn-primary:hover:before {
      width: 100%;
    }
    
    .btn-primary:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    }
    
    .step-number {
      width: 50px;
      height: 50px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      background: var(--gradient-2);
      color: white;
      font-weight: bold;
      font-size: 1.2rem;
      box-shadow: 0 5px 15px rgba(114, 9, 183, 0.3);
    }
    
    /* Animations */
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes float {
      0% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
      100% { transform: translateY(0px); }
    }
    
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
    
    .animate-fadeIn {
      animation: fadeInUp 1s ease forwards;
    }
    
    .animate-float {
      animation: float 6s ease-in-out infinite;
    }
    
    .animate-pulse {
      animation: pulse 3s ease-in-out infinite;
    }
    
    .delay-1 { animation-delay: 0.2s; }
    .delay-2 { animation-delay: 0.4s; }
    .delay-3 { animation-delay: 0.6s; }
    .delay-4 { animation-delay: 0.8s; }
    
    /* Modal */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.7);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }
    
    .modal-content {
      background: rgba(255, 255, 255, 0.95);
      padding: 2.5rem;
      border-radius: 1.5rem;
      max-width: 500px;
      text-align: center;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
      animation: fadeInUp 0.4s ease forwards;
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: var(--dark-color);
    }
    
    h1, h2, h3, h4, h5, h6 {
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 1rem;
    }
    
    .text-gradient {
      background: var(--gradient-3);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    
    .section-divider {
      height: 100px;
      margin-top: -50px;
      margin-bottom: -50px;
      position: relative;
      z-index: 1;
    }
    
    .weather-card {
      position: relative;
      overflow: hidden;
    }
    
    .weather-card:before {
      content: '';
      position: absolute;
      top: -10px;
      right: -10px;
      width: 120px;
      height: 120px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 50%;
      pointer-events: none;
    }
    
    .weather-icon-bg {
      position: absolute;
      bottom: -30px;
      right: -30px;
      font-size: 8rem;
      opacity: 0.1;
      transform: rotate(-15deg);
    }
    
    /* Improved scrollbar */
    ::-webkit-scrollbar {
      width: 10px;
    }
    
    ::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.1);
    }
    
    ::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.3);
      border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.5);
    }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen">
  <!-- Weather background overlays -->
  <div id="weatherBg-sunny" class="weather-overlay sunny"></div>
  <div id="weatherBg-rainy" class="weather-overlay rainy"></div>
  <div id="weatherBg-cloudy" class="weather-overlay cloudy"></div>
  <div id="weatherBg-snowy" class="weather-overlay snowy"></div>
  <div id="weatherBg-stormy" class="weather-overlay stormy"></div>
  
  <!-- Navigation -->
  <nav class="nav-glass fixed w-full top-0 z-50 py-4 shadow-md">
    <div class="container mx-auto px-6 flex items-center justify-between">
      <a href="#" class="text-2xl font-bold flex items-center">
        <span class="mr-2 text-accent-color text-3xl"><i class="fas fa-tshirt"></i></span>
        <span class="text-gradient">WeatherWear</span>
      </a>
      
      <div class="flex items-center">
        <div class="weather-theme-indicator" id="themeIndicator">
          <i class="fas fa-sun"></i>
          <span>Sunny</span>
        </div>
        
        <div class="hidden md:flex space-x-8 ml-8">
          <a href="index.php" class="font-medium text-white hover:text-accent-color transition-colors duration-300">Home</a>
          <a href="forecast.php" class="font-medium text-white hover:text-accent-color transition-colors duration-300 <?php echo $loggedIn ? '' : 'nav-login-required'; ?>">Forecast</a>
          <a href="about.php" class="font-medium text-white hover:text-accent-color transition-colors duration-300">About</a>
          <?php if ($loggedIn): ?>
            <a href="home.php" class="font-medium text-white hover:text-accent-color transition-colors duration-300">Weather App</a>
            <a href="logout.php" class="font-medium text-white hover:text-accent-color transition-colors duration-300">Logout</a>
          <?php else: ?>
            <a href="login.php" class="font-medium text-white hover:text-accent-color transition-colors duration-300">Login</a>
          <?php endif; ?>
        </div>
      </div>
      
      <!-- Mobile menu button -->
      <div class="md:hidden">
        <button id="mobile-menu-button" class="text-white focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </div>
    
    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-dark-color bg-opacity-95 py-4 shadow-lg mt-4">
      <div class="container mx-auto px-6 py-2 space-y-4">
        <a href="index.php" class="block font-medium text-white hover:text-accent-color transition-colors duration-300">Home</a>
        <a href="forecast.php" class="block font-medium text-white hover:text-accent-color transition-colors duration-300 <?php echo $loggedIn ? '' : 'nav-login-required'; ?>">Forecast</a>
        <a href="about.php" class="block font-medium text-white hover:text-accent-color transition-colors duration-300">About</a>
        <?php if ($loggedIn): ?>
          <a href="home.php" class="block font-medium text-white hover:text-accent-color transition-colors duration-300">Weather App</a>
          <a href="logout.php" class="block font-medium text-white hover:text-accent-color transition-colors duration-300">Logout</a>
        <?php else: ?>
          <a href="login.php" class="block font-medium text-white hover:text-accent-color transition-colors duration-300">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>
  
  <!-- Login Required Modal -->
  <div id="login-modal" class="modal">
    <div class="modal-content">
      <h3 class="text-xl font-bold mb-4">Please login to continue</h3>
      <p class="mb-6 text-gray-600">You need to be logged in to access this feature.</p>
      <div class="flex justify-center space-x-4">
        <button id="close-modal" class="px-4 py-2 bg-gray-200 rounded-lg font-medium">Close</button>
        <a href="login.php" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors">Login Now</a>
      </div>
    </div>
  </div>
  
  <main class="pt-20">
    <!-- Weather background overlays -->
    <div id="weatherBg-sunny" class="weather-overlay sunny"></div>
    <div id="weatherBg-rainy" class="weather-overlay rainy"></div>
    <div id="weatherBg-cloudy" class="weather-overlay cloudy"></div>
    <div id="weatherBg-snowy" class="weather-overlay snowy"></div>
    <div id="weatherBg-stormy" class="weather-overlay stormy"></div>
    
    <!-- Hero Section -->
    <section class="py-20 md:py-28 overflow-hidden">
      <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row items-center">
          <div class="w-full lg:w-1/2 text-center lg:text-left animate-fadeIn">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
              <span>Dress Smart with </span>
              <span class="text-gradient">Weather-Driven</span>
              <span> Fashion</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 text-white/80 max-w-xl mx-auto lg:mx-0">
              Get personalized outfit suggestions based on real-time weather data and never be unprepared again.
            </p>
            <div class="flex flex-col sm:flex-row justify-center lg:justify-start space-y-4 sm:space-y-0 sm:space-x-6">
              <a href="login.php" class="btn-primary px-8 py-4 rounded-full text-lg font-semibold inline-block">Get Started</a>
              <a href="#how-it-works" class="inline-flex items-center justify-center px-8 py-4 rounded-full text-lg font-semibold bg-white/10 hover:bg-white/20 transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                How It Works
              </a>
            </div>
          </div>
          
          <div class="w-full lg:w-1/2 mt-16 lg:mt-0 animate-fadeIn delay-1">
            <div class="relative max-w-lg mx-auto lg:ml-auto animate-float">
              <div class="absolute -top-10 -left-10 w-24 h-24 rounded-full bg-accent-color/20 backdrop-blur-xl z-0 animate-pulse"></div>
              <div class="absolute -bottom-14 -right-14 w-32 h-32 rounded-full bg-primary-color/20 backdrop-blur-xl z-0 animate-pulse"></div>
              
              <div class="glass overflow-hidden rounded-3xl shadow-2xl relative z-10">
                <div class="aspect-w-4 aspect-h-3 bg-gradient-to-br from-secondary-color to-primary-color">
                  <div class="p-8 flex flex-col items-center justify-center">
                    <div class="weather-card p-6 glass backdrop-blur-2xl rounded-xl w-full max-w-xs mx-auto mb-8">
                      <div class="flex justify-between items-start">
                        <div>
                          <h3 class="text-2xl font-bold">New York</h3>
                          <p class="text-white/70">Partly Cloudy</p>
                        </div>
                        <div class="text-4xl font-bold">22°C</div>
                      </div>
                      <div class="flex justify-center my-4">
                        <span class="text-5xl text-white">
                          <i class="fas fa-cloud-sun"></i>
                        </span>
                      </div>
                      <div class="text-center mt-4 pb-2 border-b border-white/10">
                        <p class="text-xs uppercase tracking-wide text-white/70">Today's Outfit Suggestion</p>
                      </div>
                      <div class="flex justify-center mt-4 space-x-3 text-2xl">
                        <span>👕</span>
                        <span>👖</span>
                        <span>🧥</span>
                      </div>
                      <p class="text-sm text-center mt-3 text-white/80">Light jacket, t-shirt, jeans</p>
                      <div class="weather-icon-bg">
                        <i class="fas fa-cloud-sun"></i>
                      </div>
                    </div>
                    <p class="text-white/90 text-center text-lg">
                      "Perfect outfit recommendation for this weather!"
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Storyboard Video Section -->
    <section id="how-it-works" class="py-20 relative bg-gradient-to-b from-[#3a0ca3]/30 to-transparent">
      <div class="section-divider bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDBweCIgdmlld0JveD0iMCAwIDEyODAgMTQwIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxnIGZpbGw9IiMzYTBjYTMiPjxwYXRoIGQ9Ik0xMjgwIDBsLTI2Mi4xIDExNi4yNkw5MDguNCA5MS4yNmwtMjIzLjggNDMuNUw0NzQuMSA5MC4xM2wtMTU2LjMgNTguNUwxNjguMiA4NC45MSAwIDB2MTQwaDEyODB6Ii8+PC9nPjwvc3ZnPg==')]"></div>
      
      <div class="container mx-auto px-6">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-5xl font-bold mb-6">
            <span class="text-gradient">How WeatherWear</span> Transforms Your Day
          </h2>
          <p class="text-lg text-white/80 max-w-2xl mx-auto">
            See how our app helps you dress appropriately for any weather condition, saving you from unexpected weather surprises.
          </p>
        </div>
        
        <div class="max-w-4xl mx-auto mb-16">
          <div class="relative overflow-hidden rounded-3xl shadow-2xl">
            <!-- Video placeholder with modern gradient overlay -->
            <div class="aspect-w-16 aspect-h-9 relative">
              <div class="absolute inset-0 bg-gradient-to-br from-primary-color to-secondary-color opacity-90"></div>
              <div class="absolute inset-0 flex items-center justify-center z-10">
                <div class="text-center">
                  <div class="relative inline-block group cursor-pointer mb-6">
                    <div class="absolute inset-0 rounded-full bg-white/30 animate-ping group-hover:animate-none"></div>
                    <div class="relative w-20 h-20 flex items-center justify-center rounded-full bg-white text-primary-color shadow-lg group-hover:bg-primary-color group-hover:text-white transition-all duration-300">
                      <i class="fas fa-play text-2xl ml-1"></i>
                    </div>
                  </div>
                  <h3 class="text-2xl font-bold text-white mb-2">WeatherWear in Action</h3>
                  <p class="text-white/80 max-w-md mx-auto">Watch how our app helps you make smart clothing choices</p>
                </div>
              </div>
              
              <!-- Video thumbnail background (blurred) -->
              <div class="absolute inset-0 z-0">
                <div class="w-full h-full bg-[url('https://images.unsplash.com/photo-1520013817300-1f4c1cb245ef?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2370&q=80')] bg-cover bg-center brightness-50 filter blur-sm"></div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Scene breakdown cards with enhanced design -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="glass p-8 rounded-3xl animate-fadeIn delay-1 group hover:bg-white/20">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-color to-secondary-color mb-6 flex items-center justify-center transform group-hover:rotate-3 transition-all duration-300">
              <i class="fas fa-cloud-sun-rain text-white text-2xl"></i>
            </div>
            <div class="text-xl font-bold mb-3">Morning Decision</div>
            <p class="text-white/80">Person wakes up and checks WeatherWear. The app shows "18°C, Rainy" with a warm, comfortable outfit suggestion.</p>
          </div>
          
          <div class="glass p-8 rounded-3xl animate-fadeIn delay-2 group hover:bg-white/20">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-secondary-color to-accent-color mb-6 flex items-center justify-center transform group-hover:rotate-3 transition-all duration-300">
              <i class="fas fa-umbrella text-white text-2xl"></i>
            </div>
            <div class="text-xl font-bold mb-3">Perfect Preparation</div>
            <p class="text-white/80">Following the app's advice, they pick a jacket and umbrella. They head outside prepared and comfortable despite the rain.</p>
          </div>
          
          <div class="glass p-8 rounded-3xl animate-fadeIn delay-3 group hover:bg-white/20">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-accent-color to-primary-color mb-6 flex items-center justify-center transform group-hover:rotate-3 transition-all duration-300">
              <i class="fas fa-smile-beam text-white text-2xl"></i>
            </div>
            <div class="text-xl font-bold mb-3">Weather Confidence</div>
            <p class="text-white/80">While others get soaked, our user stays dry and stylish. "Be weather-smart. Dress right with WeatherWear."</p>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Features Section -->
    <section class="py-24 relative">
      <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-primary-color/10 filter blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-secondary-color/10 filter blur-3xl"></div>
      </div>
      
      <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-20">
          <h2 class="text-4xl md:text-5xl font-bold mb-6">
            Powerful <span class="text-gradient">Features</span> for Your Style
          </h2>
          <p class="text-lg text-white/80 max-w-2xl mx-auto">
            Discover all the ways WeatherWear can help you dress confidently for any weather condition
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
          <!-- Feature 1 -->
          <div class="glass h-full p-8 rounded-3xl border border-white/10 hover:border-white/30 transition-all duration-300 animate-fadeIn delay-1">
            <div class="feature-icon">
              <i class="fas fa-cloud-sun"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Live Weather + Outfit Tips</h3>
            <p class="text-white/70 mb-6">Get real-time weather data and instant outfit suggestions tailored to current conditions, temperature, and forecast.</p>
            <div class="mt-auto pt-4 border-t border-white/10">
              <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-white/10 text-white/90">
                Real-Time Updates
              </span>
            </div>
          </div>
          
          <!-- Feature 2 -->
          <div class="glass h-full p-8 rounded-3xl border border-white/10 hover:border-white/30 transition-all duration-300 animate-fadeIn delay-2">
            <div class="feature-icon">
              <i class="fas fa-tshirt"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Outfit Gallery</h3>
            <p class="text-white/70 mb-6">Browse a curated collection of outfits specifically designed for different weather conditions and style preferences.</p>
            <div class="mt-auto pt-4 border-t border-white/10">
              <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-white/10 text-white/90">
                Style Inspiration
              </span>
            </div>
          </div>
          
          <!-- Feature 3 -->
          <div class="glass h-full p-8 rounded-3xl border border-white/10 hover:border-white/30 transition-all duration-300 animate-fadeIn delay-3">
            <div class="feature-icon">
              <i class="fas fa-save"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Save Outfits</h3>
            <p class="text-white/70 mb-6">Like an outfit suggestion? Save it to your personal collection for future reference and quick access when you need it.</p>
            <div class="mt-auto pt-4 border-t border-white/10">
              <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-white/10 text-white/90">
                Personal Collection
              </span>
            </div>
          </div>
          
          <!-- Feature 4 -->
          <div class="glass h-full p-8 rounded-3xl border border-white/10 hover:border-white/30 transition-all duration-300 animate-fadeIn delay-1">
            <div class="feature-icon">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Weekly Planner</h3>
            <p class="text-white/70 mb-6">Plan your outfits for the entire week based on weather forecasts and never be surprised by unexpected weather changes.</p>
            <div class="mt-auto pt-4 border-t border-white/10">
              <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-white/10 text-white/90">
                7-Day Forecast
              </span>
            </div>
          </div>
          
          <!-- Feature 5 -->
          <div class="glass h-full p-8 rounded-3xl border border-white/10 hover:border-white/30 transition-all duration-300 animate-fadeIn delay-2">
            <div class="feature-icon">
              <i class="fas fa-robot"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Gemini AI Style Tips</h3>
            <p class="text-white/70 mb-6">Receive personalized style advice powered by advanced AI recommendations based on weather, location, and your preferences.</p>
            <div class="mt-auto pt-4 border-t border-white/10">
              <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-white/10 text-white/90">
                AI-Powered
              </span>
            </div>
          </div>
          
          <!-- Feature 6 -->
          <div class="glass h-full p-8 rounded-3xl border border-white/10 hover:border-white/30 transition-all duration-300 animate-fadeIn delay-3">
            <div class="feature-icon">
              <i class="fas fa-bell"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">Weather Alerts</h3>
            <p class="text-white/70 mb-6">Get notified of significant weather changes so you can adjust your outfit accordingly and stay comfortable all day.</p>
            <div class="mt-auto pt-4 border-t border-white/10">
              <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold bg-white/10 text-white/90">
                Timely Notifications
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- How it Works Section -->
    <section class="py-24 bg-gradient-to-b from-[#3a0ca3]/10 to-transparent relative">
      <div class="absolute inset-0 overflow-hidden">
        <svg class="absolute bottom-0 left-0 w-full h-64 text-[#4cc9f0]/10 transform rotate-180" fill="currentColor" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"></path>
        </svg>
      </div>
      
      <div class="container mx-auto px-6 relative">
        <div class="text-center mb-16">
          <h2 class="text-4xl md:text-5xl font-bold mb-6">
            How <span class="text-gradient">It Works</span>
          </h2>
          <p class="text-lg text-white/80 max-w-2xl mx-auto">
            WeatherWear simplifies your daily outfit decisions with these easy steps
          </p>
        </div>
        
        <div class="max-w-5xl mx-auto">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-16">
            <!-- Step 1 -->
            <div class="flex items-start space-x-6 animate-fadeIn delay-1">
              <div class="step-number relative">
                <span>1</span>
                <div class="absolute h-full w-px bg-gradient-to-b from-secondary-color to-transparent top-full left-1/2 transform -translate-x-1/2 mt-4 hidden md:block"></div>
              </div>
              <div>
                <h3 class="text-2xl font-bold mb-4">Enter your location</h3>
                <p class="text-white/70 mb-4">Simply type in your city and our app will instantly fetch the latest weather data for your location.</p>
                <div class="glass p-4 rounded-2xl bg-white/5">
                  <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-primary-color/20 flex items-center justify-center text-white mr-4">
                      <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <span class="text-white/80 text-sm">New York, London, Tokyo, or any city worldwide</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Step 2 -->
            <div class="flex items-start space-x-6 animate-fadeIn delay-2">
              <div class="step-number relative">
                <span>2</span>
                <div class="absolute h-full w-px bg-gradient-to-b from-secondary-color to-transparent top-full left-1/2 transform -translate-x-1/2 mt-4 hidden md:block"></div>
              </div>
              <div>
                <h3 class="text-2xl font-bold mb-4">Get real-time weather</h3>
                <p class="text-white/70 mb-4">We fetch the most current temperature, conditions, and forecasts to provide accurate outfit recommendations.</p>
                <div class="glass p-4 rounded-2xl bg-white/5">
                  <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-primary-color/20 flex items-center justify-center text-white mr-4">
                      <i class="fas fa-temperature-high"></i>
                    </div>
                    <span class="text-white/80 text-sm">Temperature, humidity, wind, and precipitation data</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Step 3 -->
            <div class="flex items-start space-x-6 animate-fadeIn delay-3">
              <div class="step-number relative">
                <span>3</span>
                <div class="absolute h-full w-px bg-gradient-to-b from-secondary-color to-transparent top-full left-1/2 transform -translate-x-1/2 mt-4 hidden md:block"></div>
              </div>
              <div>
                <h3 class="text-2xl font-bold mb-4">Receive outfit suggestions</h3>
                <p class="text-white/70 mb-4">Get tailored clothing recommendations based on current conditions, with specific items and style advice.</p>
                <div class="glass p-4 rounded-2xl bg-white/5">
                  <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-primary-color/20 flex items-center justify-center text-white mr-4">
                      <i class="fas fa-tshirt"></i>
                    </div>
                    <span class="text-white/80 text-sm">Jacket, umbrella, sunglasses, or whatever you need</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Step 4 -->
            <div class="flex items-start space-x-6 animate-fadeIn delay-4">
              <div class="step-number">
                <span>4</span>
              </div>
              <div>
                <h3 class="text-2xl font-bold mb-4">Plan your week ahead</h3>
                <p class="text-white/70 mb-4">Use our 7-day forecast to plan your outfits for the entire week, saving time and eliminating morning stress.</p>
                <div class="glass p-4 rounded-2xl bg-white/5">
                  <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-primary-color/20 flex items-center justify-center text-white mr-4">
                      <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span class="text-white/80 text-sm">Plan for the workweek or upcoming travel</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Sneak Peek: Outfit Gallery -->
    <section class="py-16 md:py-24">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl md:text-4xl font-bold mb-6 text-center">Sneak Peek: Outfit Gallery</h2>
        <p class="text-center max-w-2xl mx-auto mb-16 text-lg">Preview our smart outfit suggestions for different weather conditions</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Sunny Day Outfit -->
          <div class="glass overflow-hidden">
            <div class="h-64 bg-gradient-to-br from-yellow-400 to-orange-500 relative">
              <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-sun text-white text-5xl"></i>
              </div>
            </div>
            <div class="p-6">
              <div class="flex items-center mb-4">
                <i class="fas fa-sun text-yellow-500 mr-3"></i>
                <h3 class="text-xl font-bold">Sunny Day Outfit</h3>
              </div>
              <p class="text-gray-700 mb-6">Light, breathable fabrics with UV protection. Perfect for hot weather while staying protected.</p>
              <button class="w-full py-3 btn-primary rounded-lg disabled opacity-75" disabled>Save this Outfit</button>
            </div>
          </div>
          
          <!-- Rainy Day Outfit -->
          <div class="glass overflow-hidden">
            <div class="h-64 bg-gradient-to-br from-blue-500 to-indigo-600 relative">
              <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-cloud-rain text-white text-5xl"></i>
              </div>
            </div>
            <div class="p-6">
              <div class="flex items-center mb-4">
                <i class="fas fa-cloud-rain text-blue-500 mr-3"></i>
                <h3 class="text-xl font-bold">Rainy Day Outfit</h3>
              </div>
              <p class="text-gray-700 mb-6">Waterproof outer layer with warm, comfortable clothing underneath. Stay dry and stylish.</p>
              <button class="w-full py-3 btn-primary rounded-lg disabled opacity-75" disabled>Save this Outfit</button>
            </div>
          </div>
          
          <!-- Cold Day Outfit -->
          <div class="glass overflow-hidden">
            <div class="h-64 bg-gradient-to-br from-blue-300 to-indigo-400 relative">
              <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas fa-snowflake text-white text-5xl"></i>
              </div>
            </div>
            <div class="p-6">
              <div class="flex items-center mb-4">
                <i class="fas fa-snowflake text-blue-300 mr-3"></i>
                <h3 class="text-xl font-bold">Cold Day Outfit</h3>
              </div>
              <p class="text-gray-700 mb-6">Layered clothing with insulated outer coat. Perfect for staying warm while maintaining style.</p>
              <button class="w-full py-3 btn-primary rounded-lg disabled opacity-75" disabled>Save this Outfit</button>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Login CTA Section -->
    <section class="py-24 relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-b from-[#4361ee]/30 to-[#7209b7]/30 backdrop-blur-sm"></div>
      
      <div class="container mx-auto px-6 relative">
        <div class="max-w-4xl mx-auto text-center bg-white/5 backdrop-blur-md px-8 py-16 rounded-3xl border border-white/10 shadow-2xl">
          <span class="px-4 py-2 rounded-full text-sm font-semibold bg-white/10 text-white inline-block mb-6">
            START NOW
          </span>
          <h2 class="text-3xl md:text-5xl font-bold mb-6">
            Dress With <span class="text-gradient">Confidence</span> Every Day
          </h2>
          <p class="text-xl text-white/80 mb-10 max-w-2xl mx-auto">
            Create an account to access your personal weather planner, saved outfits, and AI-powered style suggestions.
          </p>
          <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
            <a href="login.php" class="btn-primary px-8 py-4 rounded-full text-lg font-semibold inline-flex items-center justify-center">
              <i class="fas fa-user mr-2"></i>
              <span>Create Your Account</span>
            </a>
            <a href="#how-it-works" class="inline-flex items-center justify-center px-8 py-4 rounded-full text-lg font-semibold bg-white/10 hover:bg-white/20 transition-all duration-300">
              <i class="fas fa-info-circle mr-2"></i>
              <span>Learn More</span>
            </a>
          </div>
          <div class="mt-12 grid grid-cols-1 sm:grid-cols-3 gap-6 max-w-3xl mx-auto">
            <div class="flex flex-col items-center">
              <div class="text-4xl text-white mb-2">
                <i class="fas fa-bolt text-warning-color"></i>
              </div>
              <div class="text-lg font-bold">Fast Setup</div>
              <p class="text-white/70 text-sm">Ready in 60 seconds</p>
            </div>
            <div class="flex flex-col items-center">
              <div class="text-4xl text-white mb-2">
                <i class="fas fa-cloud text-accent-color"></i>
              </div>
              <div class="text-lg font-bold">Global Weather</div>
              <p class="text-white/70 text-sm">Works worldwide</p>
            </div>
            <div class="flex flex-col items-center">
              <div class="text-4xl text-white mb-2">
                <i class="fas fa-magic text-success-color"></i>
              </div>
              <div class="text-lg font-bold">Smart AI</div>
              <p class="text-white/70 text-sm">Personalized for you</p>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Footer -->
    <footer class="pt-16 pb-8 relative overflow-hidden">
      <div class="absolute inset-0 bg-dark-color/70 backdrop-blur-sm z-0"></div>
      
      <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-6xl mx-auto">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
            <div class="md:col-span-5">
              <a href="#" class="text-2xl font-bold flex items-center mb-6">
                <span class="mr-2 text-accent-color text-3xl"><i class="fas fa-tshirt"></i></span>
                <span class="text-gradient">WeatherWear</span>
              </a>
              <p class="text-white/70 mb-6">
                Your personal weather-based outfit assistant, helping you dress confidently for any conditions.
              </p>
              <div class="flex space-x-4">
                <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-primary-color transition-colors duration-300">
                  <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-primary-color transition-colors duration-300">
                  <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-primary-color transition-colors duration-300">
                  <i class="fab fa-facebook"></i>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-primary-color transition-colors duration-300">
                  <i class="fab fa-github"></i>
                </a>
              </div>
            </div>
            
            <div class="md:col-span-2">
              <h3 class="text-lg font-bold mb-4">Navigation</h3>
              <ul class="space-y-2">
                <li><a href="index.php" class="text-white/70 hover:text-white transition-colors">Home</a></li>
                <li><a href="forecast.php" class="text-white/70 hover:text-white transition-colors">Forecast</a></li>
                <li><a href="about.php" class="text-white/70 hover:text-white transition-colors">About</a></li>
                <li><a href="login.php" class="text-white/70 hover:text-white transition-colors">Login</a></li>
              </ul>
            </div>
            
            <div class="md:col-span-2">
              <h3 class="text-lg font-bold mb-4">Features</h3>
              <ul class="space-y-2">
                <li><a href="#" class="text-white/70 hover:text-white transition-colors">Outfit Gallery</a></li>
                <li><a href="#" class="text-white/70 hover:text-white transition-colors">Weekly Planner</a></li>
                <li><a href="#" class="text-white/70 hover:text-white transition-colors">Style Tips</a></li>
                <li><a href="#" class="text-white/70 hover:text-white transition-colors">Weather Alerts</a></li>
              </ul>
            </div>
            
            <div class="md:col-span-3">
              <h3 class="text-lg font-bold mb-4">Powered By</h3>
              <div class="bg-white/5 rounded-xl p-4">
                <div class="flex items-center mb-3">
                  <div class="w-8 h-8 rounded-full bg-primary-color/20 flex items-center justify-center mr-3">
                    <i class="fas fa-robot text-accent-color"></i>
                  </div>
                  <span class="text-white">Gemini AI Technology</span>
                </div>
                <div class="flex items-center">
                  <div class="w-8 h-8 rounded-full bg-primary-color/20 flex items-center justify-center mr-3">
                    <i class="fas fa-cloud-sun text-accent-color"></i>
                  </div>
                  <span class="text-white">OpenWeather API</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-white/70 mb-4 md:mb-0">© 2024 WeatherWear. All rights reserved.</p>
            <p class="text-white/70">Developed with <span class="text-danger-color">❤</span> by Abhilash</p>
          </div>
        </div>
      </div>
    </footer>
  </main>
  
  <script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });
    
    // Login required modal
    const loginModal = document.getElementById('login-modal');
    const closeModalBtn = document.getElementById('close-modal');
    const loginRequiredLinks = document.querySelectorAll('.nav-login-required');
    
    loginRequiredLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        loginModal.style.display = 'flex';
      });
    });
    
    closeModalBtn.addEventListener('click', () => {
      loginModal.style.display = 'none';
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', (e) => {
      if (e.target === loginModal) {
        loginModal.style.display = 'none';
      }
    });
    
    // For fade-in animations on scroll
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('opacity-100');
          entry.target.classList.remove('opacity-0');
        }
      });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.animate-fadeIn').forEach(element => {
      element.classList.add('opacity-0');
      observer.observe(element);
    });
    
    // Weather theme cycling functionality
    const themes = [
      { name: 'sunny', icon: 'fa-sun', label: 'Sunny', bgId: 'weatherBg-sunny' },
      { name: 'cloudy', icon: 'fa-cloud', label: 'Cloudy', bgId: 'weatherBg-cloudy' },
      { name: 'rainy', icon: 'fa-cloud-rain', label: 'Rainy', bgId: 'weatherBg-rainy' },
      { name: 'cold', icon: 'fa-snowflake', label: 'Cold', bgId: 'weatherBg-snowy' },
      { name: 'stormy', icon: 'fa-bolt', label: 'Stormy', bgId: 'weatherBg-stormy' }
    ];
    
    let currentThemeIndex = 0;
    let previousThemeIndex = null;
    const themeIndicator = document.getElementById('themeIndicator');
    
    // Function to preload images for smoother transitions
    function preloadBackgroundImages() {
      const imageUrls = [
        'https://images.unsplash.com/photo-1536514498073-50e69d39c6cf?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80',
        'https://images.unsplash.com/photo-1519692933481-e162a57d6721?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2070&q=80',
        'https://images.unsplash.com/photo-1534088568595-a066f410bcda?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2071&q=80',
        'https://images.unsplash.com/photo-1551582045-6ec9c11d8697?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2071&q=80',
        'https://images.unsplash.com/photo-1605727216801-e27ce1d0cc28?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2071&q=80'
      ];
      
      imageUrls.forEach(url => {
        const img = new Image();
        img.src = url;
      });
    }
    
    // Function to update the theme
    function setTheme(index) {
      // Keep track of the previous theme
      if (previousThemeIndex !== null) {
        const prevElement = document.getElementById(themes[previousThemeIndex].bgId);
        prevElement.classList.remove('active');
      }
      
      // Show current theme
      const currentTheme = themes[index];
      const currentElement = document.getElementById(currentTheme.bgId);
      currentElement.classList.add('active');
      
      // Keep track of this theme as the previous theme for next time
      previousThemeIndex = index;
      
      // Update the theme indicator in the navigation
      themeIndicator.innerHTML = `
        <i class="fas ${currentTheme.icon}"></i>
        <span>${currentTheme.label}</span>
      `;
      
      // Add a subtle animation to the theme indicator
      themeIndicator.classList.add('animate-pulse');
      setTimeout(() => {
        themeIndicator.classList.remove('animate-pulse');
      }, 1000);
    }
    
    // Preload background images for smoother transitions
    preloadBackgroundImages();
    
    // Initialize with first theme
    setTheme(currentThemeIndex);
    
    // Function to cycle through themes
    function cycleThemes() {
      currentThemeIndex = (currentThemeIndex + 1) % themes.length;
      setTheme(currentThemeIndex);
    }
    
    // Set interval to cycle themes every 5 seconds
    let themeInterval = setInterval(cycleThemes, 5000);
    
    // Optional: Pause theme cycling when user hovers over the indicator
    themeIndicator.addEventListener('mouseenter', () => {
      clearInterval(themeInterval);
    });
    
    // Resume theme cycling when user leaves
    themeIndicator.addEventListener('mouseleave', () => {
      clearInterval(themeInterval);
      themeInterval = setInterval(cycleThemes, 5000);
    });
    
    // Allow manual theme change when clicking on the indicator
    themeIndicator.addEventListener('click', () => {
      cycleThemes();
    });
  </script>
</body>
</html> 