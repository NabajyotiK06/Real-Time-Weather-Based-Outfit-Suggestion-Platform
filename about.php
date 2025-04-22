<?php
session_start();
include 'connect.php';
if(isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $city = $row['city'];
    } else {
        header("Location: invalid.php");
        exit();
    }
} else {
    // header("Location: login.php");
    // exit();
    $email = '';
    $username = '';
    $city = '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About WeatherWear - Smart Outfit Suggestions</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #0077ff;
      --secondary-color: #06bac4;
      --text-color: #333;
      --white: #fff;
      --gradient: linear-gradient(135deg, #83a4d4, #06bac4);
      --glass-bg: rgba(255, 255, 255, 0.15);
      --glass-border: rgba(255, 255, 255, 0.2);
      --box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: var(--gradient);
      color: var(--text-color);
      min-height: 100vh;
      overflow-x: hidden;
    }

    .page-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      padding: 80px 20px 20px;
    }

    .navbar {
      position: fixed;
      top: 20px;
      width: 90%;
      max-width: 1400px;
      left: 50%;
      transform: translateX(-50%);
      padding: 1rem 2rem;
      background: var(--glass-bg);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 20px;
      box-shadow: var(--box-shadow);
      z-index: 1000;
      border: 1px solid var(--glass-border);
    }

    .navbar .logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--white);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .navbar .logo i {
      font-size: 1.8rem;
    }

    .navbar .nav-links {
      display: flex;
      gap: 15px;
    }

    .navbar .nav-links button {
      background: none;
      border: none;
      color: var(--white);
      font-size: 1rem;
      padding: 0.5rem 1.2rem;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .navbar .nav-links button i {
      font-size: 1.1rem;
    }

    .navbar .nav-links button::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 0;
      height: 0;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      transition: width 0.5s, height 0.5s;
    }

    .navbar .nav-links button:hover::before {
      width: 300px;
      height: 300px;
    }

    .navbar .nav-links .active {
      background: rgba(255, 255, 255, 0.2);
      font-weight: 600;
    }

    .about-container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      background: var(--glass-bg);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      padding: 3rem;
      border-radius: 30px;
      box-shadow: var(--box-shadow);
      border: 1px solid var(--glass-border);
      color: var(--white);
      animation: fadeIn 0.6s ease-out;
    }

    .header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .header h1 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .header p {
      font-size: 1.2rem;
      opacity: 0.9;
      max-width: 800px;
      margin: 0 auto;
      line-height: 1.6;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin-top: 3rem;
    }

    .feature-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 2rem;
      border: 1px solid var(--glass-border);
      transition: transform 0.3s ease;
    }

    .feature-card:hover {
      transform: translateY(-5px);
    }

    .feature-card i {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .feature-card h3 {
      font-size: 1.4rem;
      margin-bottom: 1rem;
      color: var(--white);
    }

    .feature-card p {
      font-size: 1rem;
      line-height: 1.6;
      opacity: 0.9;
    }

    .tech-section {
      margin-top: 4rem;
      text-align: center;
    }

    .tech-section h2 {
      font-size: 2rem;
      margin-bottom: 2rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .tech-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    .tech-item {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      padding: 1.5rem;
      border: 1px solid var(--glass-border);
      transition: transform 0.3s ease;
    }

    .tech-item:hover {
      transform: translateY(-5px);
    }

    .tech-item i {
      font-size: 2rem;
      margin-bottom: 1rem;
      color: var(--primary-color);
    }

    .tech-item h4 {
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
    }

    .tech-item p {
      font-size: 0.9rem;
      opacity: 0.9;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 768px) {
      .navbar {
        padding: 1rem;
      }

      .navbar .logo {
        font-size: 1.2rem;
      }

      .navbar .nav-links button {
        padding: 0.4rem 0.8rem;
        font-size: 0.9rem;
      }

      .about-container {
        padding: 2rem;
      }

      .header h1 {
        font-size: 2rem;
      }

      .header p {
        font-size: 1.1rem;
      }

      .features-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 480px) {
      .navbar .nav-links button span {
        display: none;
      }

      .navbar .nav-links button i {
        font-size: 1.2rem;
      }

      .header h1 {
        font-size: 1.8rem;
      }

      .feature-card {
        padding: 1.5rem;
      }
    }
  </style>
</head>

<body>
  <div class="page-wrapper">
    <div class="navbar">
      <div class="logo">
        <i class="fas fa-tshirt"></i>
        WeatherWear
      </div>
      <div class="nav-links">
        <button class="homeBtn" onclick="window.location.href='home.php'">
          <i class="fas fa-home"></i>
          <span>Home</span>
        </button>
        <button class="aboutBtn active">
          <i class="fas fa-info-circle"></i>
          <span>About</span>
        </button>
        <button class="profileBtn" style="display:<?php if(!$username){echo 'none';} ?>">
          <i class="fas fa-user"></i>
          <span><?php echo $username ?></span>
        </button>
        <button class="authBtn" style="display:<?php if($username){echo 'none';} ?>" onclick="window.location.href='login.php'">
          <i class="fas fa-sign-in-alt"></i>
          <span>Login</span>
        </button>
      </div>
    </div>

    <div class="about-container">
      <div class="header">
        <h1>About WeatherWear</h1>
        <p>Your intelligent fashion companion that helps you dress appropriately for any weather condition. We combine real-time weather data with smart AI technology to provide personalized outfit recommendations.</p>
      </div>

      <div class="features-grid">
        <div class="feature-card">
          <i class="fas fa-cloud-sun"></i>
          <h3>Real-Time Weather Data</h3>
          <p>Get accurate weather information for any city worldwide using the OpenWeatherMap API, ensuring your outfit recommendations are always weather-appropriate.</p>
        </div>

        <div class="feature-card">
          <i class="fas fa-tshirt"></i>
          <h3>Smart Outfit Suggestions</h3>
          <p>Receive personalized clothing recommendations based on current temperature, weather conditions, and seasonal factors.</p>
        </div>

        <div class="feature-card">
          <i class="fas fa-brain"></i>
          <h3>AI-Powered Insights</h3>
          <p>Utilizing Google's Gemini AI technology to provide intelligent and context-aware fashion advice tailored to your location and weather.</p>
        </div>
      </div>

      <div class="tech-section">
        <h2>Technology Stack</h2>
        <div class="tech-grid">
          <div class="tech-item">
            <i class="fas fa-robot"></i>
            <h4>Gemini 2.0 Flash</h4>
            <p>Advanced AI model for intelligent outfit recommendations</p>
          </div>

          <div class="tech-item">
            <i class="fas fa-cloud"></i>
            <h4>OpenWeatherMap</h4>
            <p>Real-time weather data from around the world</p>
          </div>

          <div class="tech-item">
            <i class="fas fa-code"></i>
            <h4>Modern Stack</h4>
            <p>Built with PHP, JavaScript, and modern web technologies</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.querySelector('.homeBtn').addEventListener('click', function() {
      window.location.href = 'home.php';
    });
    document.querySelector('.profileBtn').addEventListener('click', function() {
      window.location.href = 'profile.php';
    });
  </script>
</body>
</html>
