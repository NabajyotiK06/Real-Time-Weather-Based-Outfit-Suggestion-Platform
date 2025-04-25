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
    // Comment out redirect to allow non-logged in users to view the home page
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
  <title>WeatherWear - Smart Outfit Suggestions</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #6495ED;
      --secondary-color: #FF8C69;
      --text-dark: #333;
      --text-light: #666;
      --white: #fff;
      --off-white: #F8F8F8;
      --glass-bg: rgba(255, 255, 255, 0.15);
      --glass-border: rgba(255, 255, 255, 0.2);
      --box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s ease;

      /* Theme specific variables */
      --bg-gradient-light: linear-gradient(135deg, rgba(100, 149, 237, 0.9), rgba(255, 140, 105, 0.9));
      --bg-gradient-dark: linear-gradient(135deg, rgba(44, 62, 80, 0.95), rgba(52, 73, 94, 0.95));
      --card-bg-light: rgba(255, 255, 255, 0.1);
      --card-bg-dark: rgba(0, 0, 0, 0.2);
      --text-primary: var(--white);
      --text-secondary: rgba(255, 255, 255, 0.9);
      --border-color: rgba(255, 255, 255, 0.2);
    }

    [data-theme="dark"] {
      --bg-gradient: var(--bg-gradient-dark);
      --card-bg: var(--card-bg-dark);
    }

    [data-theme="light"] {
      --bg-gradient: var(--bg-gradient-light);
      --card-bg: var(--card-bg-light);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: var(--white);
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: var(--bg-gradient);
      z-index: -1;
      transition: var(--transition);
    }

    .page-wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      padding: 100px 20px 40px;
      max-width: 1400px;
      margin: 0 auto;
      position: relative;
    }

    .navbar {
      position: fixed;
      top: 20px;
      width: 90%;
      max-width: 1400px;
      left: 50%;
      transform: translateX(-50%);
      padding: 1rem 2rem;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 20px;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      z-index: 1000;
    }

    .navbar .logo {
      font-family: 'Montserrat', sans-serif;
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--white);
      display: flex;
      align-items: center;
      gap: 12px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
      text-decoration: none;
    }

    .navbar .logo i {
      font-size: 2rem;
      background: linear-gradient(135deg, #fff, #f0f0f0);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .navbar .nav-links {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .nav-link {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 10px 18px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      font-weight: 500;
      font-size: 0.95rem;
      color: var(--white);
      text-decoration: none;
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .nav-link:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
      border-color: rgba(255, 255, 255, 0.2);
    }

    .nav-link.active {
      background: rgba(255, 255, 255, 0.2);
      font-weight: 600;
      border-color: rgba(255, 255, 255, 0.3);
    }

    .nav-link .icon {
      width: 20px;
      height: 20px;
      opacity: 0.9;
    }

    .profileBtn, .authBtn {
      font-family: 'Montserrat', sans-serif;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: var(--white);
      font-size: 0.95rem;
      padding: 10px 18px;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 500;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .profileBtn:hover, .authBtn:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
      border-color: rgba(255, 255, 255, 0.2);
    }

    .profileBtn i, .authBtn i, .nav-link i {
      font-size: 1.1rem;
      transition: transform 0.3s ease;
    }

    .profileBtn:hover i, .authBtn:hover i, .nav-link:hover i {
      transform: translateY(-1px);
    }

    /* Custom style for the theme toggle button */
    .theme-toggle {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      color: white;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-left: 15px;
    }

    .theme-toggle:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: rotate(15deg);
    }

    /* Responsive styles for the navbar */
    @media (max-width: 768px) {
      .navbar {
        padding: 0.75rem 1.25rem;
      }

      .navbar .logo {
        font-size: 1.4rem;
      }

      .nav-link {
        padding: 8px 12px;
        font-size: 0.85rem;
      }

      .nav-link .icon {
        width: 18px;
        height: 18px;
      }
      
      .profileBtn, .authBtn {
        padding: 8px 12px;
        font-size: 0.85rem;
      }
    }

    @media (max-width: 640px) {
      .nav-link span {
        display: none;
      }
      
      .nav-link, .profileBtn, .authBtn {
        padding: 8px;
      }
      
      .nav-link .icon {
        margin: 0;
      }
    }

    .home-container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      padding: 3rem;
      border-radius: 30px;
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .header {
      text-align: center;
      margin-bottom: 3rem;
      color: var(--white);
    }

    .header h1 {
      font-family: 'Montserrat', sans-serif;
      font-size: 3rem;
      margin-bottom: 1rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
      font-weight: 700;
      background: linear-gradient(135deg, #fff, #f0f0f0);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .header p {
      font-size: 1.2rem;
      opacity: 0.9;
      max-width: 600px;
      margin: 0 auto;
      line-height: 1.6;
    }

    .content-box {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2.5rem;
      margin-top: 2rem;
    }

    .left-section {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .weather-input {
      position: relative;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .weather-input-wrapper {
      flex: 1;
      position: relative;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 15px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      overflow: hidden;
    }

    .weather-input i {
      position: absolute;
      left: 1.2rem;
      top: 50%;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.8);
      font-size: 1.1rem;
      z-index: 1;
    }

    .weather-input input {
      width: 100%;
      background: transparent;
      border: none;
      padding: 1rem 1rem 1rem 3rem;
      font-size: 1rem;
      color: #fff;
      font-family: 'Poppins', sans-serif;
    }

    .weather-input input::placeholder {
      color: rgba(255, 255, 255, 0.6);
    }

    .weather-input input:focus {
      outline: none;
    }

    .weather-input-wrapper:focus-within {
      border-color: rgba(255, 255, 255, 0.4);
      box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
    }

    .weather-input button {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 1rem 1.5rem;
      border-radius: 12px;
      color: #fff;
      font-size: 0.95rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      cursor: pointer;
      transition: all 0.3s ease;
      font-family: 'Montserrat', sans-serif;
      white-space: nowrap;
    }

    .weather-input button:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .weather-input button i {
      position: static;
      transform: none;
    }

    .recommendation-text {
      text-align: center;
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--white);
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      font-family: 'Montserrat', sans-serif;
      letter-spacing: 0.5px;
      opacity: 0;
      transform: translateY(10px);
      padding: 0.75rem;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .recommendation-text.show {
      opacity: 1;
      transform: translateY(0);
    }

    .result-section {
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-radius: 24px;
      border: 1px solid rgba(255, 255, 255, 0.18);
      padding: 2rem;
      color: #fff;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      transform: translateY(10px);
      opacity: 0;
    }

    .result-section.show {
      transform: translateY(0);
      opacity: 1;
    }

    .current-weather {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1.5rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .weather-left {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .city-name {
      font-size: 1.75rem;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.95);
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
      letter-spacing: 0.5px;
    }

    .weather-description {
      font-size: 1.1rem;
      color: rgba(255, 255, 255, 0.85);
      text-transform: capitalize;
      font-weight: 500;
    }

    .temperature {
      font-size: 2.5rem;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.98);
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      margin-top: 0.25rem;
    }

    .weather-right img {
      width: 80px;
      height: 80px;
      filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.4));
      transition: transform 0.4s ease;
    }

    .outfit-section {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .outfit-section h3 {
      font-size: 1.25rem;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.9);
      position: relative;
      padding-bottom: 0.75rem;
    }

    .outfit-section h3:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 40px;
      height: 3px;
      background: linear-gradient(90deg, rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.1));
      border-radius: 2px;
    }

    .outfit-section .outfit-icons {
      display: flex;
      gap: 0.75rem;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 16px;
      padding: 0.75rem;
      font-size: 1.5rem;
      justify-content: center;
      border: 1px solid rgba(255, 255, 255, 0.1);
      margin: 0.25rem 0 0.75rem;
    }

    .outfit-section p {
      font-size: 1rem;
      line-height: 1.6;
      color: rgba(255, 255, 255, 0.85);
      background: rgba(255, 255, 255, 0.06);
      padding: 1rem;
      border-radius: 12px;
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Right section - Weather details */
    .right-section {
      background: rgba(0, 0, 30, 0.25);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-radius: 24px;
      padding: 1.75rem;
      border: 1px solid rgba(255, 255, 255, 0.18);
      box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.1),
        0 2px 8px rgba(0, 0, 0, 0.05);
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
      color: var(--white);
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      transform: translateY(10px);
      opacity: 0;
      max-width: 100%;
      height: 100%;  /* Make height 100% to match left section */
      min-height: 454px; /* Minimum height to ensure consistency */
      justify-content: space-between; /* Distribute content evenly */
    }

    .right-section.show {
      transform: translateY(0);
      opacity: 1;
    }

    .right-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(
        135deg,
        rgba(255, 255, 255, 0.1),
        rgba(255, 255, 255, 0.05)
      );
      opacity: 0;
      transition: opacity 0.4s ease;
      z-index: 0;
    }

    .right-section:hover::before {
      opacity: 1;
    }

    .right-section > * {
      position: relative;
      z-index: 1;
    }

    .right-section h3 {
      font-size: 1.5rem;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.95);
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
      text-align: center;
      margin-bottom: 0.5rem;
      position: relative;
      padding-bottom: 0.75rem;
    }

    .right-section h3:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 3px;
      background: linear-gradient(90deg, rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.6), rgba(255, 255, 255, 0.1));
      border-radius: 2px;
    }

    .weather-details-container {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      flex: 1;
    }

    /* Weather info boxes in right section */
    .weather-info-box {
      display: flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.06);
      border-radius: 16px;
      padding: 0.85rem;
      border: 1px solid rgba(255, 255, 255, 0.1);
      margin-bottom: 0.5rem;
      gap: 1rem;
      transition: all 0.3s ease;
    }

    .weather-info-box:last-child {
      margin-bottom: 0;
    }

    .weather-info-box:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .weather-info-icon {
      font-size: 1.5rem;
      color: rgba(255, 255, 255, 0.9);
      background: rgba(255, 255, 255, 0.1);
      height: 45px;
      width: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .weather-info-box:hover .weather-info-icon {
      background: rgba(255, 255, 255, 0.15);
      transform: scale(1.05);
    }

    .weather-info-text {
      flex: 1;
    }

    .weather-info-title {
      font-size: 0.85rem;
      color: rgba(255, 255, 255, 0.7);
      margin-bottom: 0.25rem;
    }

    .weather-info-value {
      font-size: 1.15rem;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.95);
    }

    @media (max-width: 1024px) {
      .content-box {
        grid-template-columns: 1fr;
        gap: 2rem;
      }
      
      .right-section {
        margin-top: 0;
      }
    }

    @media (max-width: 768px) {
      .navbar {
      padding: 1rem;
      }

      .navbar .logo {
        font-size: 1.4rem;
      }

      .navbar .nav-links button {
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
      }

      .header h1 {
        font-size: 2rem;
      }

      .header p {
        font-size: 1rem;
      }

      .weather-input {
        flex-direction: column;
        gap: 1rem;
      }

      .weather-input button {
        width: 100%;
        justify-content: center;
      }

      .home-container {
        padding: 1.5rem;
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

      .recommendation-text {
        font-size: 1.5rem;
      }

      .left-section {
        gap: 1rem;
      }

      .weather-input {
        border-radius: 15px;
      }

      .weather-input input {
        padding: 0.7rem 1rem 0.7rem 2.25rem;
        font-size: 0.9rem;
      }

      .weather-input i {
        font-size: 1rem;
        left: 0.875rem;
      }
    }

    /* Weather-specific background gradients */
    .weather-clear {
      background-image: linear-gradient(135deg, rgba(249, 212, 35, 0.9), rgba(255, 78, 80, 0.9));
    }

    .weather-clouds {
      background-image: linear-gradient(135deg, rgba(189, 195, 199, 0.9), rgba(44, 62, 80, 0.9));
    }

    .weather-rain {
      background-image: linear-gradient(135deg, rgba(75, 121, 161, 0.9), rgba(40, 62, 81, 0.9));
    }

    .weather-snow {
      background-image: linear-gradient(135deg, rgba(230, 218, 218, 0.9), rgba(39, 64, 70, 0.9));
    }

    .weather-storm {
      background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.9), rgba(67, 67, 67, 0.9));
    }

    /* Update forecast section styles */
    .forecast-section {
      margin-top: 2rem;
      padding-top: 1.5rem;
      border-top: 1px solid rgba(255, 255, 255, 0.18);
    }

    .forecast-section h2 {
      font-size: 1.75rem;
      margin-bottom: 1.5rem;
    }

    .forecast-grid {
      display: grid;
      grid-template-columns: repeat(9, 1fr);
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

    /* Enhanced Style Tips section styles */
    .style-tips-section {
      max-width: 1000px;
      margin: 40px auto 0;
      padding: 20px;
      display: block;
    }
    
    .style-tips-section h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #fff;
    }
    
    .tips-container {
      display: flex;
      gap: 20px;
      overflow-x: auto;
      padding: 10px 5px 30px;
      scroll-behavior: smooth;
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    
    .tips-container::-webkit-scrollbar {
      display: none;
    }
    
    .tip-card {
      background: rgba(255, 255, 255, 0.10);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.18);
      padding: 25px;
      min-width: 300px;
      max-width: 300px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      display: flex;
      gap: 15px;
      flex-shrink: 0;
      transition: all 0.3s ease;
    }
    
    .tip-card:hover {
      transform: translateY(-5px) scale(1.02) !important;
      border-color: rgba(255, 255, 255, 0.3);
    }
    
    .tip-emoji {
      font-size: 50px;
      margin-bottom: 15px;
      flex-shrink: 0;
    }
    
    .tip-content {
      flex-grow: 1;
    }
    
    .tip-content h3 {
      margin-top: 0;
      margin-bottom: 10px;
      font-size: 18px;
      color: #fff;
    }
    
    .tip-content p {
      margin: 0;
      font-size: 14px;
      line-height: 1.5;
      color: rgba(255, 255, 255, 0.8);
    }
    
    .tip-carousel-controls {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-top: 20px;
      gap: 15px;
    }
    
    .carousel-btn {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      color: white;
    }
    
    .carousel-btn:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: scale(1.1);
    }
    
    .carousel-indicator {
      display: flex;
      gap: 8px;
    }
    
    .indicator-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .indicator-dot.active {
      background: white;
      transform: scale(1.2);
    }
    
    .tip-category {
      display: inline-block;
      padding: 4px 8px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 20px;
      font-size: 12px;
      color: #fff;
      margin-bottom: 8px;
    }

    /* Add theme toggle styles */
    .theme-toggle {
      background: none;
      border: none;
      padding: 0.8rem;
      cursor: pointer;
      border-radius: 50%;
      transition: var(--transition);
      position: relative;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
    }

    .theme-toggle:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateY(-2px);
    }

    .theme-toggle i {
      font-size: 1.2rem;
      color: var(--white);
      transition: var(--transition);
    }

    .theme-toggle:hover i {
      transform: rotate(15deg);
    }

    /* Update existing styles for dark mode */
    [data-theme="dark"] .home-container,
    [data-theme="dark"] .forecast-card,
    [data-theme="dark"] .tip-card {
      background: var(--card-bg);
    }

    [data-theme="dark"] .weather-input input::placeholder {
      color: rgba(255, 255, 255, 0.5);
    }

    [data-theme="dark"] .result,
    [data-theme="dark"] .right-section {
      background: rgba(0, 0, 0, 0.2);
    }

    /* Enhanced loader styles */
    .loader-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.85);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      opacity: 0;
      visibility: hidden;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .loader-container.show {
      opacity: 1;
      visibility: visible;
    }

    .loader-wrapper {
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 2rem;
      transform: scale(0.8);
      opacity: 0;
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .loader-container.show .loader-wrapper {
      transform: scale(1);
      opacity: 1;
    }

    .loader {
      position: relative;
      width: 100px;
      height: 100px;
      transform: rotate(45deg);
      animation: loader 2s infinite cubic-bezier(0.4, 0, 0.2, 1);
    }

    .loader-item {
      position: absolute;
      width: 24px;
      height: 24px;
      background: var(--white);
      border-radius: 50%;
      animation: loaderItem 1.5s infinite cubic-bezier(0.4, 0, 0.2, 1);
      filter: drop-shadow(0 0 12px rgba(255, 255, 255, 0.3));
    }

    .loader .loader-item:nth-child(1) {
      top: 0;
      left: 0;
      animation-delay: 0.2s;
      background: linear-gradient(135deg, #6495ED, #4169E1);
    }

    .loader .loader-item:nth-child(2) {
      top: 0;
      right: 0;
      animation-delay: 0.4s;
      background: linear-gradient(135deg, #FF8C69, #FF6347);
    }

    .loader .loader-item:nth-child(3) {
      bottom: 0;
      left: 0;
      animation-delay: 0.6s;
      background: linear-gradient(135deg, #98FB98, #3CB371);
    }

    .loader .loader-item:nth-child(4) {
      bottom: 0;
      right: 0;
      animation-delay: 0.8s;
      background: linear-gradient(135deg, #DDA0DD, #9370DB);
    }

    @keyframes loader {
      0% {
        transform: rotate(45deg) scale(1);
      }
      50% {
        transform: rotate(225deg) scale(1.1);
      }
      100% {
        transform: rotate(405deg) scale(1);
      }
    }

    @keyframes loaderItem {
      0%, 100% {
        transform: scale(0.6);
        opacity: 0.6;
      }
      50% {
        transform: scale(1.2);
        opacity: 1;
      }
    }

    .loader-content {
      text-align: center;
      color: var(--white);
    }

    .loader-text {
      font-size: 1.25rem;
      font-weight: 500;
      margin-bottom: 0.5rem;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
      animation: pulse 2s infinite;
    }

    .loader-subtext {
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.8);
      max-width: 300px;
      text-align: center;
      line-height: 1.5;
    }

    @keyframes pulse {
      0%, 100% {
        opacity: 0.8;
        transform: scale(1);
      }
      50% {
        opacity: 1;
        transform: scale(1.05);
      }
    }

    .loader-progress {
      width: 200px;
      height: 4px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 2px;
      margin-top: 1rem;
      overflow: hidden;
    }

    .loader-progress-bar {
      height: 100%;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
      width: 0%;
      transition: width 0.3s ease;
      animation: progress 2s infinite;
    }

    @keyframes progress {
      0% {
        width: 0%;
        opacity: 1;
      }
      50% {
        width: 100%;
        opacity: 0.5;
      }
      100% {
        width: 0%;
        opacity: 1;
      }
    }

    @media (max-width: 768px) {
      .loader {
        width: 80px;
        height: 80px;
      }

      .loader-item {
        width: 20px;
        height: 20px;
      }

      .loader-text {
        font-size: 1.1rem;
      }

      .loader-subtext {
        font-size: 0.8rem;
      }

      .loader-progress {
        width: 160px;
      }
    }

    /* Add these animation styles before the closing style tag */
    .animate-card {
      opacity: 0;
      transform: translateY(24px);
      transition: all 700ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .animate-card.show {
      opacity: 1;
      transform: translateY(0);
    }

    .forecast-card {
      opacity: 0;
      transform: translateY(24px);
      transition: all 700ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .forecast-card.show {
      opacity: 1;
      transform: translateY(0);
    }

    .right-section {
      opacity: 0;
      transform: translateY(24px);
      transition: all 700ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    .right-section.show {
      opacity: 1;
      transform: translateY(0);
    }

    /* Add typewriter animation styles */
    @keyframes typing {
      from { width: 0 }
      to { width: 100% }
    }

    @keyframes blink {
      50% { border-color: transparent }
    }

    .title-container {
      text-align: center;
      margin-bottom: 2rem;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 0.5rem;
    }

    .typewriter {
      display: inline-block;
      overflow: hidden;
      white-space: nowrap;
      border-right: 3px solid var(--white);
      margin: 0 auto;
      letter-spacing: 0.15em;
    }

    .main-title {
      font-size: 2.5rem;
      font-weight: 700;
      animation: 
        typing 3.5s steps(40, end),
        blink 0.75s step-end infinite;
    }

    .subtitle {
      font-size: 1.2rem;
      color: rgba(255, 255, 255, 0.9);
      animation: 
        typing 3.5s steps(40, end) 3.5s,
        blink 0.75s step-end infinite;
      animation-fill-mode: both;
    }

    .weather-footer {
      margin-top: auto;
      padding-top: 1rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      font-size: 0.9rem;
      color: rgba(255, 255, 255, 0.7);
      text-align: center;
    }

    .weather-date {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .weather-date i {
      font-size: 1rem;
      color: rgba(255, 255, 255, 0.8);
    }

    /* Placeholder text for right section */
    #detail {
      font-size: 1rem;
      line-height: 1.6;
      color: rgba(255, 255, 255, 0.7);
      text-align: center;
      padding: 1.5rem;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>
</head>

<body data-theme="light">
  <!-- Enhanced loader HTML -->
  <div class="loader-container" id="loader">
    <div class="loader-wrapper">
      <div class="loader">
        <div class="loader-item"></div>
        <div class="loader-item"></div>
        <div class="loader-item"></div>
        <div class="loader-item"></div>
      </div>
      <div class="loader-content">
        <div class="loader-text">Fetching Weather Data</div>
        <div class="loader-subtext">Getting the latest weather information and preparing your outfit suggestions...</div>
        <div class="loader-progress">
          <div class="loader-progress-bar"></div>
        </div>
      </div>
    </div>
  </div>

  <div class="page-wrapper">
  <div class="navbar">
      <a href="#" class="logo">
        <i class="fas fa-tshirt"></i>
        WeatherWear
      </a>
      
      <div class="nav-links">
        <a href="index.php" class="theme-toggle" id="themeToggle">
          <i class="fas fa-sun"></i>
        </a>
        <a href="landing.php" class="nav-link">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
          </svg>
          <span>Landing</span>
        </a>
        <a href="home.php" class="nav-link active">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
          </svg>
          <span>Home</span>
        </a>
        <a href="forecast.php" class="nav-link">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
            <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z" />
          </svg>
          <span>Forecast</span>
        </a>
        <a href="about.php" class="nav-link">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 20 20" fill="currentColor" width="24" height="24">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>
          <span>About</span>
        </a>
        <?php if($username): ?>
          <button class="profileBtn">
            <i class="fas fa-user"></i>
            <span><?php echo $username ?></span>
          </button>
        <?php else: ?>
          <button class="authBtn" onclick="window.location.href='login.php'">
            <i class="fas fa-sign-in-alt"></i>
            <span>Login</span>
          </button>
        <?php endif; ?>
      </div>
    </div>

  <div class="home-container">
      <div class="title-container">
        <h1 class="typewriter main-title">WeatherWear</h1>
        <p class="typewriter subtitle">Your Intelligent Clothing Companion</p>
      </div>
      
    <div class="content-box">
      <div class="left-section">
        <div class="weather-input">
            <div class="weather-input-wrapper">
              <input type="text" id="cityInput" placeholder="Enter your city name" onkeydown="handleKeyPress(event)">
              <i class="fas fa-map-marker-alt"></i>
            </div>
            <button onclick="getWeather()">
              <i class="fas fa-search"></i>
              Check Weather
            </button>
        </div>
        <div class="recommendation-text" id="outfitText"></div>
          <div class="result-section" id="result" style="display: none;">
            <!-- Weather results will be displayed here -->
        </div>
      </div>
      <div class="right-section" id="outfitBox">
          <h3>Weather Details</h3>
          <div id="detail">
            Enter your city name to get detailed weather information and personalized outfit recommendations.
          </div>
          <div class="weather-footer">
            <div class="weather-date">
              <i class="far fa-calendar"></i> Today, <?php echo date('F j, Y'); ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Style Tips Section -->
      <div class="style-tips-section" id="styleTipsSection">
        <h2>Style Tips & Weather Facts</h2>
        <div class="tips-container" id="tipsContainer"></div>
        <div class="tip-carousel-controls">
          <button class="carousel-btn prev-btn" id="prevTip">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
          </button>
          <div class="carousel-indicator" id="tipIndicator"></div>
          <button class="carousel-btn next-btn" id="nextTip">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // DOM Element references
      const cityInput = document.getElementById('cityInput');
      const searchBtn = document.querySelector('.weather-input button');
      const resultSection = document.getElementById('result');
      const styleSection = document.getElementById('styleTipsSection');
      const loadingSpinner = document.getElementById('loader');
      const themeToggle = document.getElementById('themeToggle');
      const tipText = document.getElementById('tipText');
      const nextTipBtn = document.getElementById('nextTip');
      const prevTipBtn = document.getElementById('prevTip');
      const outfitText = document.getElementById('outfitText');
      const outfitBox = document.getElementById('outfitBox');
      
      // Initialize theme
      initTheme();
      
      // Initialize style tips on page load instead of waiting for a search
      initStyleTips();
      
      // Tip management
      let currentTipIndex = 0;
      const tips = [
        "Layering isn't just fashionable - it's a practical way to adapt to changing temperatures throughout the day. Start with a light base layer and add or remove pieces as needed.",
        "Dark colors absorb more heat from the sun, while light colors reflect it. Choose your outfit colors strategically based on the weather!",
        "Natural fibers like cotton and linen are more breathable than synthetic materials, making them perfect for hot summer days.",
        "The UV index is highest between 10 AM and 4 PM, even on cloudy days. Don't forget your sunscreen and protective clothing!",
        "Wool isn't just for winter - merino wool is naturally moisture-wicking and temperature-regulating, making it great for all seasons.",
        "Humidity affects how we perceive temperature - in humid conditions, opt for loose-fitting clothes that allow air circulation.",
        "Rain-resistant doesn't always mean waterproof. Look for items labeled 'waterproof' for complete protection in heavy rain.",
        "Your body loses up to 50% of its heat through your head - wear a hat in cold weather to stay warm effectively.",
        "UV rays can penetrate clouds, so sun protection is important even on overcast days.",
        "The 'wind chill factor' can make it feel much colder than the actual temperature - dress accordingly!",
        "Synthetic fabrics like polyester and nylon are better for workouts as they wick away sweat more effectively.",
        "Light-colored, loose-fitting clothes are best for desert climates as they reflect sunlight and allow air circulation."
      ];
      
      function showNextTip() {
        currentTipIndex = (currentTipIndex + 1) % tips.length;
        if (tipText) {
          tipText.style.opacity = 0;
          setTimeout(() => {
            tipText.textContent = tips[currentTipIndex];
            tipText.style.opacity = 1;
          }, 300);
        }
      }
      
      function showPrevTip() {
        currentTipIndex = (currentTipIndex - 1 + tips.length) % tips.length;
        if (tipText) {
          tipText.style.opacity = 0;
          setTimeout(() => {
            tipText.textContent = tips[currentTipIndex];
            tipText.style.opacity = 1;
          }, 300);
        }
      }
      
      // Add tip navigation event listeners
      if (nextTipBtn) {
        nextTipBtn.addEventListener('click', showNextTip);
      }
      
      if (prevTipBtn) {
        prevTipBtn.addEventListener('click', showPrevTip);
      }
      
      // Handle key press events
    function handleKeyPress(event) {
      if (event.key === 'Enter') {
        getWeather();
      }
    }

      // Initialize theme
      function initTheme() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.body.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);
      }
      
      function toggleTheme() {
        const currentTheme = document.body.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        document.body.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
      }
      
      function updateThemeIcon(theme) {
        const themeIcon = document.querySelector('#themeToggle i');
        if (themeIcon) {
          if (theme === 'dark') {
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
          } else {
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
          }
        }
      }
      
      // Add event listener for theme toggle
      if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
      }
      
      // Function to show the loading spinner
      function showLoader() {
        if (loadingSpinner) {
          loadingSpinner.classList.add('show');
        }
      }
      
      // Function to hide the loading spinner
      function hideLoader() {
        if (loadingSpinner) {
          loadingSpinner.classList.remove('show');
        }
      }
      
      // Reset animations for multiple searches
      function resetAnimations() {
        const elements = document.querySelectorAll('.animate-card, .forecast-card, .right-section');
        elements.forEach(element => {
          element.classList.remove('show');
          void element.offsetWidth; // Trigger reflow
          element.classList.add('show');
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
      
      // Function to get outfit icons
      function getOutfitIcons(temp, weatherDescription) {
        let icons = '';
        const weatherLower = weatherDescription.toLowerCase();
        
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
      
      // Change background based on weather
      function changeBackground(weather) {
        const body = document.body;
        const weatherLower = weather.toLowerCase();
        
        body.classList.remove('weather-clear', 'weather-clouds', 'weather-rain', 'weather-snow', 'weather-storm');
        
        if (weatherLower.includes('clear')) {
          body.classList.add('weather-clear');
        } else if (weatherLower.includes('cloud')) {
          body.classList.add('weather-clouds');
        } else if (weatherLower.includes('rain') || weatherLower.includes('drizzle')) {
          body.classList.add('weather-rain');
        } else if (weatherLower.includes('snow')) {
          body.classList.add('weather-snow');
        } else if (weatherLower.includes('storm') || weatherLower.includes('thunder')) {
          body.classList.add('weather-storm');
        }
      }
      
      // Function to get weather
      function getWeather() {
        console.log("getWeather function called");
        showLoader();
        
        const city = cityInput ? cityInput.value.trim() : '';
        console.log("Searching for city:", city);
        
        if (!city) {
          hideLoader();
          alert('Please enter a city name');
          return;
        }
        
        const url = `get_weather.php?city=${encodeURIComponent(city)}`;
        console.log("Fetching URL:", url);
        
        fetch(url)
          .then(response => {
            console.log("Response status:", response.status);
            if (!response.ok) {
              throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            hideLoader();
            console.log("Weather data received:", data);
            
            if (data.error) {
              alert(data.error);
              return;
            }
            
            const weather = data.weather;
            const temp = Math.round(data.temp);
            const feelsLike = Math.round(data.feels_like);
            const humidity = data.humidity;
            const windSpeed = data.wind_speed;
            const description = data.description;
            const weatherIcon = data.icon;
            
            // Change background based on weather
            changeBackground(weather);

            // Get outfit recommendation
            const outfit = getOutfitSuggestion(temp, description);
            const outfitIcons = getOutfitIcons(temp, description);
            
            // Create current weather HTML
            const currentWeatherHtml = `
              <div class="current-weather">
                <div class="weather-left">
                  <div class="city-name">${city}</div>
                  <div class="weather-description">${description}</div>
                  <div class="temperature">${temp}°C</div>
                </div>
                <div class="weather-right">
                  <img src="https://openweathermap.org/img/wn/${weatherIcon}@2x.png" alt="${description}" class="weather-icon">
                </div>
              </div>
              <div class="outfit-section">
                <h3>Today's Outfit Recommendation</h3>
                <div class="outfit-icons">${outfitIcons}</div>
                <p>${outfit}</p>
              </div>
            `;
            
            // Update detailed outfit text
            if (outfitText) {
              outfitText.innerText = `${temp}°C - ${description}`;
              outfitText.classList.add('show');
            }
            
            // Update right section with only weather details (no outfit suggestion)
            if (outfitBox) {
              outfitBox.innerHTML = `
                <h3>Weather Details</h3>
                
                <div class="weather-details-container">
                  <div class="weather-info-box">
                    <div class="weather-info-icon">
                      <i class="fas fa-temperature-high"></i>
                    </div>
                    <div class="weather-info-text">
                      <div class="weather-info-title">Temperature</div>
                      <div class="weather-info-value">${temp}°C (Feels like ${feelsLike}°C)</div>
                    </div>
                  </div>
                  
                  <div class="weather-info-box">
                    <div class="weather-info-icon">
                      <i class="fas fa-cloud"></i>
                    </div>
                    <div class="weather-info-text">
                      <div class="weather-info-title">Conditions</div>
                      <div class="weather-info-value">${description}</div>
                    </div>
                  </div>
                  
                  <div class="weather-info-box">
                    <div class="weather-info-icon">
                      <i class="fas fa-wind"></i>
                    </div>
                    <div class="weather-info-text">
                      <div class="weather-info-title">Wind Speed</div>
                      <div class="weather-info-value">${windSpeed} m/s</div>
                    </div>
                  </div>
                  
                  <div class="weather-info-box">
                    <div class="weather-info-icon">
                      <i class="fas fa-tint"></i>
                    </div>
                    <div class="weather-info-text">
                      <div class="weather-info-title">Humidity</div>
                      <div class="weather-info-value">${humidity}%</div>
                    </div>
                  </div>
                </div>
                
                <div class="weather-footer">
                  <div class="weather-date">
                    <i class="far fa-calendar"></i> ${new Date().toLocaleDateString('en-US', {weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'})}
                  </div>
                </div>
              `;
              outfitBox.classList.add('show');
            }
            
            // Update result section
            if (resultSection) {
              resultSection.innerHTML = currentWeatherHtml;
              resultSection.style.display = 'block';
              resultSection.classList.add('show');
            }
            
            // Reset animations for multiple searches
            resetAnimations();
            
            // Get 5-day forecast
            getForecast(city);
          })
          .catch(error => {
            hideLoader();
            console.error('Error:', error);
            alert('Could not get weather. Please check if the city name is correct and try again.');
          });
      }
      
      // Event listeners
      if (cityInput) {
        cityInput.addEventListener('keypress', handleKeyPress);
      }
      
      if (searchBtn) {
        searchBtn.addEventListener('click', getWeather);
      }
      
      // Check if there's a predefined city to fetch weather for
      let city = "<?php echo $city; ?>";
      if (city && city !== '') {
        if (cityInput) {
          cityInput.value = city;
        }
        getWeather();
      } else {
        // Initialize style tips even without a city search
        initStyleTips();
      }
    });

    function displayForecast(data) {
      if (!data || !data.list || data.list.length === 0) {
        console.log("No forecast data available");
        return;
      }
      
      // Get forecast section or create it if it doesn't exist
      let forecastSection = document.getElementById('forecastSection');
      if (!forecastSection) {
        forecastSection = document.createElement('div');
        forecastSection.id = 'forecastSection';
        forecastSection.className = 'forecast-section';
        forecastSection.innerHTML = '<h2>5-Day Weather Forecast</h2>';
        
        // Insert after style tips section
        const styleTipsSection = document.getElementById('styleTipsSection');
        if (styleTipsSection) {
          styleTipsSection.parentNode.insertBefore(forecastSection, styleTipsSection.nextSibling);
        } else {
          document.querySelector('.home-container').appendChild(forecastSection);
        }
      } else {
        // Clear existing forecast
        forecastSection.innerHTML = '<h2>5-Day Weather Forecast</h2>';
      }
      
      console.log("Original forecast data length:", data.list.length);
      
      // Group forecast by day
      const dailyForecasts = {};
      const uniqueDays = new Set();
      
      for (const item of data.list) {
        const date = new Date(item.dt * 1000);
        const dayKey = date.toISOString().split('T')[0]; // YYYY-MM-DD format
        
        if (!dailyForecasts[dayKey]) {
          dailyForecasts[dayKey] = {
            date: date,
            day: date.toLocaleDateString('en-US', { weekday: 'long' }),
            temp_min: item.main.temp_min,
            temp_max: item.main.temp_max,
            weather: item.weather[0].main,
            icon: item.weather[0].icon,
            items: []
          };
          uniqueDays.add(dayKey);
        }
        
        // Update temperature extremes
        if (item.main.temp_max > dailyForecasts[dayKey].temp_max) {
          dailyForecasts[dayKey].temp_max = item.main.temp_max;
        }
        if (item.main.temp_min < dailyForecasts[dayKey].temp_min) {
          dailyForecasts[dayKey].temp_min = item.main.temp_min;
        }
        
        // Add this forecast to the day's items
        dailyForecasts[dayKey].items.push(item);
      }
      
      // Convert to array and sort by date
      const processedForecast = Object.values(dailyForecasts).sort((a, b) => a.date - b.date);
      
      console.log("Processed unique days:", processedForecast);
      console.log("Number of unique days:", uniqueDays.size);
      
      // STRICT LIMIT: Only create cards for 5 days maximum
      const forecastGrid = document.createElement('div');
      forecastGrid.className = 'forecast-grid';
      
      // Counter to ensure we only render 5 days
      let renderedDays = 0;
      
      for (const day of processedForecast) {
        // Safety check to ensure we never exceed 5 days
        if (renderedDays >= 5) {
          break;
        }
        
        const card = document.createElement('div');
        card.className = 'forecast-card animate-card';
        card.innerHTML = `
          <div class="forecast-card-header">
            <div class="day">${day.day}</div>
            <div class="date">${formatDate(day.date)}</div>
          </div>
          <div class="forecast-card-weather">
            <img class="weather-icon" src="https://openweathermap.org/img/wn/${day.icon}@2x.png" alt="${day.weather}">
            <div class="temp">${Math.round(day.temp_max)}°C / ${Math.round(day.temp_min)}°C</div>
            <div class="weather">${day.weather}</div>
          </div>
          <div class="forecast-card-outfit">
            <div class="outfit-icons">${getOutfitIcons(day.temp_max, day.weather)}</div>
            <div class="outfit">${getOutfitSuggestion(day.temp_max, day.weather)}</div>
          </div>
        `;
        
        forecastGrid.appendChild(card);
        renderedDays++;
      }
      
      forecastSection.appendChild(forecastGrid);
      console.log("Final number of cards rendered:", renderedDays);
      
      // Reset animations for the cards
      resetAnimations();
    }
    
    // Helper function to format date
    function formatDate(date) {
      return date.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric'
      });
    }

    // Style Tips Carousel
    const styleTips = [
      {
        emoji: "💡",
        title: "Layering for Weather Changes",
        content: "Layering clothes is key for unpredictable weather. Start with a moisture-wicking base layer, add an insulating middle layer, and finish with a weather-protective outer layer that you can remove as needed.",
        category: "Style Tip"
      },
      {
        emoji: "🌈",
        title: "Color Choices & Temperature",
        content: "Darker colors absorb more heat from the sun, while lighter colors reflect it. On hot days, light-colored clothing can help keep you cooler, and on cold days, darker colors can provide more warmth.",
        category: "Fashion Fact"
      },
      {
        emoji: "🧵",
        title: "Natural vs. Synthetic Fabrics",
        content: "Natural fabrics like cotton and linen are more breathable for hot weather, while wool is naturally insulating for cold. Synthetic performance fabrics are best for active wear as they wick moisture away quickly.",
        category: "Material Guide"
      },
      {
        emoji: "☀️",
        title: "UV Protection Beyond Sunscreen",
        content: "Tightly woven fabrics provide better sun protection. Look for clothing with UPF (Ultraviolet Protection Factor) ratings for the best defense against harmful UV rays.",
        category: "Health Tip"
      },
      {
        emoji: "🌧️",
        title: "Rain-Ready Fashion",
        content: "Water-resistant and waterproof are different! Water-resistant items can handle light rain for a short time, while waterproof items have sealed seams and can withstand heavy rain for longer periods.",
        category: "Weather Guide"
      },
      {
        emoji: "❄️",
        title: "The Magic of Wool",
        content: "Wool can absorb up to 30% of its weight in moisture without feeling wet, making it an excellent choice for cold, damp conditions. It also naturally regulates temperature and resists odors.",
        category: "Material Fact"
      },
      {
        emoji: "👟",
        title: "Footwear for the Forecast",
        content: "Your shoes should match the weather conditions. For rain, choose waterproof boots with good traction. For heat, breathable sandals or mesh sneakers. For cold, insulated boots with non-slip soles.",
        category: "Accessory Guide"
      }
    ];
    
    let currentTipIndex = 0;
    
    function initStyleTips() {
      const tipsContainer = document.getElementById('tipsContainer');
      const tipIndicator = document.getElementById('tipIndicator');
      
      // Clear any existing content
      tipsContainer.innerHTML = '';
      tipIndicator.innerHTML = '';
      
      // Create indicator dots
      styleTips.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.className = `indicator-dot ${index === 0 ? 'active' : ''}`;
        dot.dataset.index = index;
        dot.addEventListener('click', () => goToTip(index));
        tipIndicator.appendChild(dot);
      });
      
      // Create all tip cards but only show the first one
      styleTips.forEach((tip, index) => {
        const card = createTipCard(tip, index);
        tipsContainer.appendChild(card);
      });
      
      // Setup navigation buttons
      document.getElementById('prevTip').addEventListener('click', showPreviousTip);
      document.getElementById('nextTip').addEventListener('click', showNextTip);
      
      // Show the section
      document.getElementById('styleTipsSection').style.display = 'block';
      
      // Set initial scroll position
      updateCarouselDisplay();
    }
    
    function createTipCard(tip, index) {
      const card = document.createElement('div');
      card.className = 'tip-card';
      card.id = `tipCard${index}`;
      card.style.opacity = index === currentTipIndex ? '1' : '0.5';
      card.style.transform = index === currentTipIndex ? 'scale(1)' : 'scale(0.95)';
      
      const categorySpan = document.createElement('span');
      categorySpan.className = 'tip-category';
      categorySpan.textContent = tip.category;
      
      const emoji = document.createElement('div');
      emoji.className = 'tip-emoji';
      emoji.textContent = tip.emoji;
      
      const content = document.createElement('div');
      content.className = 'tip-content';
      
      const title = document.createElement('h3');
      title.textContent = tip.title;
      
      const paragraph = document.createElement('p');
      paragraph.textContent = tip.content;
      
      content.appendChild(title);
      content.appendChild(paragraph);
      
      card.appendChild(emoji);
      card.appendChild(content);
      
      return card;
    }
    
    function updateCarouselDisplay() {
      // Update indicator dots
      document.querySelectorAll('.indicator-dot').forEach((dot, index) => {
        dot.classList.toggle('active', index === currentTipIndex);
      });
      
      // Update card visibility and styling
      document.querySelectorAll('.tip-card').forEach((card, index) => {
        if (index === currentTipIndex) {
          card.style.opacity = '1';
          card.style.transform = 'scale(1)';
        } else {
          card.style.opacity = '0.5';
          card.style.transform = 'scale(0.95)';
        }
      });
      
      // Scroll to current card
      const currentCard = document.getElementById(`tipCard${currentTipIndex}`);
      if (currentCard) {
        currentCard.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      }
    }
    
    function showNextTip() {
      currentTipIndex = (currentTipIndex + 1) % styleTips.length;
      updateCarouselDisplay();
    }
    
    function showPreviousTip() {
      currentTipIndex = (currentTipIndex - 1 + styleTips.length) % styleTips.length;
      updateCarouselDisplay();
    }
    
    function goToTip(index) {
      currentTipIndex = index;
      updateCarouselDisplay();
    }
    
    // Initialize tips when weather data is loaded
    function getWeather(city) {
      // ... existing code ...
      
      // Show loader
      if (showLoader) showLoader();
      
      fetch('get_weather.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `city=${encodeURIComponent(city)}`
      })
      .then(response => response.json())
      .then(data => {
        // ... existing code ...
        
        // Initialize style tips after weather data is loaded
        initStyleTips();
        
        // Hide loader
        if (hideLoader) hideLoader();
      })
      .catch(error => {
        console.error('Error fetching weather data:', error);
        alert('Error fetching weather data. Please try again.');
        
        // Hide loader even on error
        if (hideLoader) hideLoader();
      });
      
      return false;
    }
    
    // ... existing code ...

    // Get forecast for a city
    function getForecast(city) {
      if (!city) return;
      
      console.log("Getting forecast for:", city);
      
      fetch(`get_forecast.php?city=${encodeURIComponent(city)}`)
        .then(response => {
          if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
          }
          return response.json();
        })
        .then(data => {
          console.log("Forecast data received:", data);
          
          if (data.error) {
            console.error("Forecast error:", data.error);
            return;
          }
          
          // Display forecast data
          displayForecast(data);
        })
        .catch(error => {
          console.error("Error fetching forecast:", error);
        });
    }
  </script>
</body>

</html>