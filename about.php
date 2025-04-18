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
        echo "No user found!";
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Weather Outfit Recommender</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(to right, #83a4d4, #06bac4);
      color: #333;
      display: flex;
      flex-direction: column;
      align-items: center;
      height: 100vh;
      overflow: hidden;
      transition: all 1s ease;
    }

    .navbar {
      position: absolute;
      top: 20px;
      width: 90%;
      left: 5%;
      padding: 1rem 2rem;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 20px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      z-index: 10;
    }

    .navbar .logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: #fff;
    }

    .navbar .nav-links button {
      background: none;
      border: none;
      color: #fff;
      font-size: 1rem;
      margin-left: 1rem;
      cursor: pointer;
      transition: transform 0.2s, background 0.3s;
      padding: 0.3rem 0.8rem;
      border-radius: 10px;
    }

    .navbar .nav-links button:hover {
      transform: scale(1.1);
      background-color: rgba(255, 255, 255, 0.1);
    }

    .navbar .nav-links .active {
      background-color: rgba(255, 255, 255, 0.3);
      font-weight: bold;
    }

    .navbar .nav-links button:nth-child(3) {
      border: 1px solid #fff;
    }

    .about-container {
      width: 1000px;
      height: 100%;
      margin-top: 7rem;
      margin-bottom: 2rem;
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      padding: 2rem;
      border-radius: 30px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
      display: flex;
      flex-direction: column;
      animation: fadeIn 0.6s ease-in-out;
    }

    .about-container {
      font-size: 1.1rem;
      line-height: 1.8;
      padding: 1rem 2rem;
      overflow-y: auto;
    }

    .about-container h2 {
      margin-top: 1.5rem;
      color: #0077ff;
    }

    ul {
      margin-left: 1.5rem;
      margin-top: 1rem;
    }

    li {
      margin-bottom: 0.8rem;
    }


    h1 {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>
  <div class="navbar">
    <div class="logo">WeatherWear</div>
    <div class="nav-links">
      <button class="homeBtn">Home</button>
      <button class="aboutBtn active">About</button>
      <button class="profileBtn"><?php echo $username; ?></button>
    </div>
  </div>

  <div class="about-container">
    <h1>About WeatherWear</h1>
    <div class="about-section">
      <p><strong>WeatherWear</strong> is your smart clothing assistant that helps you decide what to wear based on the weather.</p>

      <h2>🌤️ How It Works</h2>
      <ul>
        <li><strong>City-Based Weather Fetching:</strong> Enter your city name and we fetch the latest weather using the OpenWeatherMap API.</li>
        <li><strong>AI-Powered Outfit Recommendations:</strong> Our system sends your city's temperature to Google Gemini AI to generate clothing suggestions.</li>
        <li><strong>Two Levels of Suggestions:</strong> You receive a short, to-the-point outfit tip and a more detailed fashion explanation (around 80 words).</li>
        <li><strong>Dynamic UI:</strong> The background of the page changes to match the current weather conditions for a more immersive feel.</li>
        <li><strong>Fast Interaction:</strong> Press "Enter" or click the "Check" button to get suggestions instantly—no reload needed.</li>
      </ul>

      <h2>🧠 AI Integration Highlights</h2>
      <ul>
        <li>We use the <strong>Gemini 2.0 Flash API</strong> by Google to power outfit recommendations.</li>
        <li>The AI understands temperature and city context to deliver smart, context-aware suggestions.</li>
        <li>Each result is unique, human-like, and tailored to your query—just like getting advice from a personal fashion assistant!</li>
      </ul>
    </div>
  </div>


</body>
<script>
  document.querySelector('.homeBtn').addEventListener('click', function() {
    window.location.href = 'home.php';
  });
  document.querySelector('.profileBtn').addEventListener('click', function() {
    window.location.href = 'profile.php';
  });
</script>
</html>
