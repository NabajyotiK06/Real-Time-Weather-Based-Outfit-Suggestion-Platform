<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Not Found</title>
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
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #333;
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

    .container {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(15px);
      padding: 3rem;
      border-radius: 30px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
      text-align: center;
      max-width: 400px;
      animation: fadeIn 0.6s ease-in-out;
    }

    h2 {
      color: #fff;
      margin-bottom: 1rem;
    }

    p {
      color: #fff;
      font-size: 1rem;
      margin-bottom: 2rem;
    }

    .back-btn {
      background-color: #0077ff;
      border: none;
      padding: 0.8rem 1.5rem;
      color: white;
      font-weight: bold;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s, transform 0.2s;
    }

    .back-btn:hover {
      background-color: #005ecc;
      transform: scale(1.05);
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
      <button class="homeBtn" style="display: none;">Home</button>
      <button class="aboutBtn" style="display: none;">About</button>
      <button class="authBtn active">Login / Signup</button>
    </div>
  </div>

  <div class="container">
    <h2>Email already exists</h2>
    <p>The credentials you entered are already available in our records.</p>
    <a href="login.php">
      <button class="back-btn">Back to Login</button>
    </a>
  </div>
</body>
</html>
