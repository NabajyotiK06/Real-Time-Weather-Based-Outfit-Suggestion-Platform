<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>WeatherWear - Login / Signup</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(135deg, #83a4d4, #06bac4);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: #333;
      position: relative;
      padding: 20px;
    }
    
    .navbar {
      position: fixed;
      top: 20px;
      width: 90%;
      max-width: 1200px;
      left: 50%;
      transform: translateX(-50%);
      padding: 1rem 2rem;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 20px;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
      z-index: 10;
    }

    .navbar .logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: #fff;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .navbar .logo i {
      font-size: 1.8rem;
    }

    .navbar .nav-links button {
      background: none;
      border: none;
      color: #fff;
      font-size: 1rem;
      margin-left: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      padding: 0.5rem 1rem;
      border-radius: 12px;
      position: relative;
      overflow: hidden;
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

    .navbar .nav-links button.authBtn {
      background: rgba(255, 255, 255, 0.3);
      font-weight: 600;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .container {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      padding: 2.5rem;
      border-radius: 30px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
      width: 100%;
      max-width: 420px;
      margin-top: 5rem;
      transition: all 0.5s ease;
    }

    .container form {
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
    }

    h2 {
      text-align: center;
      color: #fff;
      font-size: 2rem;
      margin-bottom: 1.5rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .input-group {
      position: relative;
      width: 100%;
    }

    .input-group i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #666;
      transition: all 0.3s ease;
    }

    input {
      width: 100%;
      padding: 1rem 1rem 1rem 3rem;
      background: rgba(255, 255, 255, 0.9);
      border: 2px solid transparent;
      border-radius: 12px;
      font-size: 1rem;
      transition: all 0.3s ease;
      color: #333;
    }

    input:focus {
      border-color: #0077ff;
      outline: none;
      background: #fff;
    }

    input:focus + i {
      color: #0077ff;
    }

    button[type="submit"] {
      width: 100%;
      padding: 1rem;
      background: linear-gradient(135deg, #0077ff, #00c6ff);
      border: none;
      color: white;
      font-weight: 600;
      font-size: 1.1rem;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0,119,255,0.3);
    }

    button[type="submit"]:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0,119,255,0.4);
    }

    button[type="submit"]:active {
      transform: translateY(0);
    }

    .error {
      color: #ff4e50;
      text-align: center;
      margin-top: 1rem;
      background: rgba(255,78,80,0.1);
      padding: 0.8rem;
      border-radius: 10px;
      backdrop-filter: blur(5px);
    }

    .login-text, .signup-text {
      text-align: center;
      margin-top: 1.5rem;
      color: #fff;
    }

    .login-text span, .signup-text span {
      color: #fff;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: underline;
      text-underline-offset: 3px;
    }

    .login-text span:hover, .signup-text span:hover {
      text-shadow: 0 0 10px rgba(255,255,255,0.8);
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

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateX(-100%);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    .signup-container, .login-container {
      animation: fadeIn 0.6s ease-out;
    }

    @media (max-width: 768px) {
      .navbar {
        padding: 0.8rem 1.5rem;
        width: 95%;
      }

      .navbar .logo {
        font-size: 1.2rem;
      }

      .navbar .nav-links button {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
        margin-left: 0.5rem;
      }

      .container {
        padding: 2rem;
        margin-top: 4rem;
      }

      h2 {
        font-size: 1.8rem;
      }

      input {
        padding: 0.9rem 0.9rem 0.9rem 2.8rem;
      }
    }

    @media (max-width: 480px) {
      .navbar .nav-links button:not(.authBtn) {
        display: none;
      }

      .container {
        padding: 1.5rem;
      }

      h2 {
        font-size: 1.6rem;
      }
    }
  </style>
</head>
<body>
  <div class="navbar">
    <div class="logo">
      <i class="fas fa-cloud-sun"></i>
      WeatherWear
    </div>
    <div class="nav-links">
      <button class="homeBtn" onclick="window.location.href='home.php'">
        <i class="fas fa-home"></i> Home
      </button>
      <button class="aboutBtn" onclick="window.location.href='about.php'">
        <i class="fas fa-info-circle"></i> About
      </button>
      <button class="authBtn">
        <i class="fas fa-user"></i> Login / Signup
      </button>
    </div>
  </div>

  <div class="signup-container container" style="display: none;">
    <form method="POST" action="register.php">
      <h2>Create Account</h2>
      <div class="input-group">
        <input type="text" name="username" placeholder="Username" required />
        <i class="fas fa-user"></i>
      </div>
      <div class="input-group">
        <input type="email" name="email" placeholder="Email" required />
        <i class="fas fa-envelope"></i>
      </div>
      <div class="input-group">
        <input type="password" name="password" placeholder="Password" required />
        <i class="fas fa-lock"></i>
      </div>
      <button type="submit" name="signUp">Create Account</button>
      <div class="login-text">
        <p>Already have an account? <span class="loginForm">Login</span></p>
      </div>
    </form>
  </div>

  <div class="login-container container">
    <form method="POST" action="register.php">
      <h2>Welcome Back</h2>
      <div class="input-group">
        <input type="email" name="email" placeholder="Email" required />
        <i class="fas fa-envelope"></i>
      </div>
      <div class="input-group">
        <input type="password" name="password" placeholder="Password" required />
        <i class="fas fa-lock"></i>
      </div>
      <button type="submit" name="login">Login</button>
      <div class="signup-text">
        <p>Don't have an account? <span class="signUpForm">Sign Up</span></p>
      </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const loginFormBtn = document.querySelector('.loginForm');
      const signUpFormBtn = document.querySelector('.signUpForm');
      const signupContainer = document.querySelector('.signup-container');
      const loginContainer = document.querySelector('.login-container');

      const switchForms = (showSignup) => {
        if (showSignup) {
          loginContainer.style.opacity = '0';
          loginContainer.style.transform = 'translateY(20px)';
          setTimeout(() => {
            loginContainer.style.display = 'none';
            signupContainer.style.display = 'block';
            setTimeout(() => {
              signupContainer.style.opacity = '1';
              signupContainer.style.transform = 'translateY(0)';
            }, 50);
          }, 300);
        } else {
          signupContainer.style.opacity = '0';
          signupContainer.style.transform = 'translateY(20px)';
          setTimeout(() => {
            signupContainer.style.display = 'none';
            loginContainer.style.display = 'block';
            setTimeout(() => {
              loginContainer.style.opacity = '1';
              loginContainer.style.transform = 'translateY(0)';
            }, 50);
          }, 300);
        }
      };

      signUpFormBtn.addEventListener('click', () => switchForms(true));
      loginFormBtn.addEventListener('click', () => switchForms(false));

      // Add ripple effect to buttons
      const buttons = document.querySelectorAll('button');
      buttons.forEach(button => {
        button.addEventListener('click', function(e) {
          const x = e.clientX - e.target.offsetLeft;
          const y = e.clientY - e.target.offsetTop;
          
          const ripple = document.createElement('span');
          ripple.style.left = `${x}px`;
          ripple.style.top = `${y}px`;
          
          this.appendChild(ripple);
          setTimeout(() => ripple.remove(), 600);
        });
      });
    });
  </script>
</body>
</html>
