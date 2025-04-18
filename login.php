<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login / Signup</title>
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

    .navbar .nav-links button:nth-child(3) {
      background-color: rgba(255, 255, 255, 0.3);
      font-weight: bold;
    }

    .signup-container, .login-container {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(15px);
      padding: 2.5rem;
      border-radius: 30px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
      width: 100%;
      max-width: 400px;
      animation: fadeIn 0.6s ease-in-out;
      margin-top: 3rem;
    }

    .login-container form, .signup-container form {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }
    

    h2 {
      text-align: center;
      color: #fff;
      margin-bottom: 1.5rem;
    }

    input {
      width: 100%;
      padding: 0.8rem;
      margin-bottom: 1rem;
      border: 1px solid #ccc;
      border-radius: 10px;
    }

    button[type="submit"] {
      width: 100%;
      padding: 0.8rem;
      background-color: #0077ff;
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 10px;
      cursor: pointer;
      transition: background 0.3s, transform 0.2s;
    }

    button[type="submit"]:hover {
      background-color: #005ecc;
      transform: scale(1.05);
    }

    .error {
      color: #ff4e50;
      text-align: center;
      margin-top: 1rem;
    }
    .login-text, .signup-text {
      text-align: center;
      margin-top: 1rem;
    }

    .login-text span, .signup-text span {
      color: #0077ff;
      text-decoration: none;
      font-weight: bold;
      cursor: pointer;
      transition: color 0.3s;
    }

    .login-text span:hover, .signup-text span:hover {
      color: #005ecc;
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
      <button class="authBtn">Login / Signup</button>
    </div>
  </div>

  <div class="signup-container" style="display: none;">
    <!-- Simple signup form -->
    <form method="POST" action="register.php">
      <h2>Signup</h2>
      <input type="text" name="username" placeholder="Username" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" name="signUp">Register</button>
      <div class="login-text">
        <p>Already have an account? <span class="loginForm">Login</span></p>
      </div>
    </form>
  </div>

  <div class="login-container">
    <form method="POST" action="register.php">
      <h2>Login</h2>
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit" name="login">Login</button>
      <div class="signup-text">
        <p>Don't have an account? <span class="signUpForm">Sign Up</span></p>
      </div>
    </form>
  </div>
</body>
  <script>

    let loginFormBtn = document.querySelector('.loginForm');
    let signUpFormBtn = document.querySelector('.signUpForm');
    signUpFormBtn.addEventListener("click", () => {
      document.querySelector('.signup-container').style.display = "block";
      document.querySelector('.login-container').style.display = "none";
    });
    loginFormBtn.addEventListener("click", () => {
      document.querySelector('.signup-container').style.display = "none";
      document.querySelector('.login-container').style.display = "block";
    });
  </script>
</html>
