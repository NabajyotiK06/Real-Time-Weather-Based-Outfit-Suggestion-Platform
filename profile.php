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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile - WeatherWear</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: linear-gradient(to right, #83a4d4, #06bac4);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding-top: 100px;
    }

    .profile-container {
      width: 400px;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(15px);
      border-radius: 25px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
      padding: 2rem;
      color: white;
      animation: fadeIn 0.6s ease-in-out;
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .profile-info {
      margin-bottom: 1rem;
    }

    .profile-info strong {
      display: inline-block;
      width: 100px;
    }

    .logout-btn {
      width: 100%;
      margin-top: 1.5rem;
      padding: 0.8rem;
      background-color: #ff5e5e;
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s, transform 0.2s;
    }

    .logout-btn:hover {
      background-color: #e04040;
      transform: scale(1.05);
    }

    .navbar {
      position: fixed;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: 90%;
      /* max-width: 900px; */
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

    .navbar .nav-links {
      display: flex;
      gap: 1rem;
    }

    .navbar .nav-links button {
      background: none;
      border: none;
      color: #fff;
      font-size: 1rem;
      cursor: pointer;
      transition: transform 0.2s, background 0.3s;
      padding: 0.4rem 1rem;
      border-radius: 10px;
    }

    .navbar .nav-links .active {
      background-color: rgba(255, 255, 255, 0.3);
      font-weight: bold;
    }
  

    @media (max-width: 600px) {
      .navbar {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
      }

      body {
        padding-top: 160px;
      }

      .profile-container {
        width: 90%;
      }
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
      <button class="aboutBtn">About</button>
      <button class="profileBtn active"><?php echo $username ?></button>
    </div>
  </div>

  <div class="profile-container">
    <h2>User Profile</h2>
    <div class="profile-info"><strong>Username:</strong> <?php echo $username ?></div>
    <div class="profile-info"><strong>Email:</strong> <?php echo $email ?></div>
    <div class="profile-info"><strong>City:</strong> <?php echo $city ?></div>

    <button class="logout-btn">Logout</button>
  </div>

  <script>
    document.querySelector('.logout-btn').addEventListener('click', function() {
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = 'logout.php';
      }
    });
    document.querySelector('.homeBtn').addEventListener('click', function() {
      window.location.href = 'home.php';
    });
    document.querySelector('.aboutBtn').addEventListener('click', function() {
      window.location.href = 'about.php';
    });
  </script>
</body>
</html>
