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


    .home-container {
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

    h1 {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .content-box {
      display: flex;
      flex: 1;
      justify-content: space-between;
      align-items: stretch;
    }

    .left-section, .right-section {
      width: 48%;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .weather-input {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    input {
      width: 75%;
      padding: 0.2rem 1rem
      ;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 16px;
    }

    button {
      padding: 0.8rem 1.5rem;
      background-color: #0077ff;
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s, transform 0.2s;
    }

    button:hover {
      background-color: #005ecc;
      transform: scale(1.05);
    }

    .result {
      padding: 1rem 0 0 0;
      animation: fadeIn 1s ease;
    }

    .recommendation-text {
      text-align: center;
      margin: 1rem 0;
      font-weight: bold;
      font-size: 26px;
    }

    .right-section {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 1rem;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      text-align: center;
      overflow-y: auto;
    }

    .right-section p {
      word-wrap: break-word;
      font-size: 18px;
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
      <button class="homeBtn active">Home</button>
      <button class="aboutBtn">About</button>
      <button class="profileBtn"><?php echo $username; ?></button>
    </div>
  </div>

  <div class="home-container">
    <h1>Weather Outfit Recommender</h1>
    <div class="content-box">
      <div class="left-section">
        <div class="weather-input">
          <input type="text" id="cityInput" placeholder="Enter city name" onkeydown="handleKeyPress(event)">
          <button onclick="getWeather()">Check</button>
        </div>
        <div class="recommendation-text" id="outfitText"></div>
        <div class="result" id="result">
          <!-- Weather and outfit recommendation will be displayed here -->
        </div>
      </div>
      <div class="right-section" id="outfitBox">
        <p id="detail">Outfit suggestion will appear here.</p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    const GEMINI_API_KEY = "AIzaSyCrF1q3tgYFp60opxTm5GsAHPpGAONM7KU"; // Replace with your key
    const ENDPOINT = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent";

    async function getBotReply(userInput) {
        try {
            const response = await axios.post(
                `${ENDPOINT}?key=${GEMINI_API_KEY}`,
                {
                    contents: [
                        {
                            parts: [{ text: userInput }]
                        }
                    ]
                },
                {
                    headers: {
                        "Content-Type": "application/json"
                    }
                }
            );

            const text = response.data.candidates?.[0]?.content?.parts?.[0]?.text;
            return text;
        } catch (error) {
            console.error("Error calling Gemini API:", error.response?.data || error.message);
            return "Sorry, something went wrong.";
        }
    }


    let city = "<?php echo $city; ?>";
    if(! (city === '')) {
      document.getElementById('cityInput').value = city;
      getWeather();
    }
    
    
    function handleKeyPress(event) {
      if (event.key === 'Enter') {
        getWeather();
      }
    }

    async function getWeather() {
      if (!city) {
        city = document.getElementById('cityInput').value.trim();
        if (city === '') {
          alert('Please enter a city name.');
          document.getElementById('result').innerHTML = "";
          return;
        }
        return;
      }

      document.getElementById('result').innerHTML = `<p>Searching...</p>`;
      document.getElementById('outfitText').innerText = '';
      document.getElementById('outfitBox').innerHTML = '<p>Outfit suggestion will appear here.</p>';

      
      try {
        await fetch('save_city.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ city }),
        });
      } catch (error) {
          console.error('Error saving city to the database:', error);
      }
        
      const weatherApiKey = '73d14412e88fa1c87f89770c1ae3b238';

      const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${weatherApiKey}&units=metric`;

      try {
        const response = await fetch(url);
        const data = await response.json();

        if (data.cod !== 200) {
          document.getElementById('result').innerHTML = `<p>${data.message}</p>`;
          document.getElementById('outfitText').innerText = '';
          return;
        }
        // console.log(data.main);
        const temp = data.main.temp;
        const weather = data.weather[0].main.toLowerCase();
        const description = data.weather[0].description;
        const recommendation = await getClothingRecommendation(data.main.temp, data.name);
        const detailedRecommendation = await getDetailedRecommendation(data.main.temp, data.name);

        changeBackground(weather);

        document.getElementById('result').innerHTML = `
          <h2>${data.name}, ${data.sys.country}</h2>
          <p><strong>Temperature:</strong> ${temp}°C</p>
          <p><strong>Condition:</strong> ${description}</p>
        `;
        document.getElementById('outfitText').innerText = recommendation;
        document.getElementById('outfitBox').innerHTML = `<p>${detailedRecommendation}</p>`;
      } catch (error) {
        console.error(error);
        alert('Failed to fetch weather data.');
      }
      city = "";
    }

    async function getClothingRecommendation(temp, city) {
      let prompt = `Temp: ${temp}, city: ${city}. Answer in short what should i wear? Only give compact reply`;
      console.log(prompt);
      let botResponse = await getBotReply(prompt);
      console.log(botResponse);
      return botResponse;
    }

    async function getDetailedRecommendation(temp, city) {
      let prompt = `Temp: ${temp}, city: ${city}. Explain in about 80 words what should i wear?`;
      console.log(prompt);
      let botResponse = await getBotReply(prompt);
      console.log(botResponse);
      return botResponse;
    }

    function changeBackground(weather) {
      let body = document.body;

      if (weather.includes('clear')) {
        body.style.background = 'linear-gradient(to right, #f9d423, #ff4e50)';
      } else if (weather.includes('cloud')) {
        body.style.background = 'linear-gradient(to right, #bdc3c7, #2c3e50)';
      } else if (weather.includes('rain')) {
        body.style.background = 'linear-gradient(to right, #4b79a1, #283e51)';
      } else if (weather.includes('snow')) {
        body.style.background = 'linear-gradient(to right, #e6dada, #274046)';
      } else if (weather.includes('storm')) {
        body.style.background = 'linear-gradient(to right, #000000, #434343)';
      } else {
        body.style.background = 'linear-gradient(to right, #83a4d4, #b6fbff)';
      }
    }
    document.querySelector('.aboutBtn').addEventListener('click', function() {
      window.location.href = 'about.php';
    });
    document.querySelector('.profileBtn').addEventListener('click', function() {
      window.location.href = 'profile.php';
    });

  </script>
</body>

</html>