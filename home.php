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
      font-family: 'Poppins', sans-serif;
      background-image: url('https://images.unsplash.com/photo-1534088568595-a066f410bcda?q=80&w=2069&auto=format&fit=crop');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: var(--text-primary);
      min-height: 100vh;
      overflow-x: hidden;
      position: relative;
      transition: var(--transition);
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
    }

    .navbar .logo i {
      font-size: 2rem;
      background: linear-gradient(135deg, #fff, #f0f0f0);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .navbar .nav-links {
      display: flex;
      gap: 20px;
    }

    .navbar .nav-links button {
      font-family: 'Montserrat', sans-serif;
      background: none;
      border: none;
      color: var(--white);
      font-size: 1rem;
      padding: 0.8rem 1.5rem;
      border-radius: 12px;
      cursor: pointer;
      transition: var(--transition);
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 500;
    }

    .navbar .nav-links button i {
      font-size: 1.2rem;
      transition: var(--transition);
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
      z-index: -1;
    }

    .navbar .nav-links button:hover::before {
      width: 300px;
      height: 300px;
    }

    .navbar .nav-links button:hover i {
      transform: translateY(-2px);
    }

    .navbar .nav-links .active {
      background: rgba(255, 255, 255, 0.2);
      font-weight: 600;
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
      gap: 3rem;
      margin-top: 2rem;
    }

    .left-section {
      display: flex;
      flex-direction: column;
      gap: 2rem;
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
      font-size: 1.75rem;
      font-weight: 600;
      color: var(--white);
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
      opacity: 0;
      transform: translateY(20px);
      transition: var(--transition);
      font-family: 'Montserrat', sans-serif;
      margin-top: 1rem;
    }

    .recommendation-text.show {
      opacity: 1;
      transform: translateY(0);
    }

    .result {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 2rem;
      color: #fff;
      margin-top: 1rem;
    }

    .result h2 {
      font-size: 1.8rem;
      margin-bottom: 1.5rem;
      color: #fff;
    }

    .result p {
      font-size: 1.1rem;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .result p strong {
      color: rgba(255, 255, 255, 0.9);
    }

    .right-section {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-radius: 24px;
      padding: 2.5rem;
      border: 1px solid rgba(255, 255, 255, 0.2);
      box-shadow: 
        0 8px 32px rgba(0, 0, 0, 0.1),
        0 2px 8px rgba(0, 0, 0, 0.05);
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      height: fit-content;
      color: var(--white);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
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
      transition: opacity 0.3s ease;
    }

    .right-section:hover {
      transform: translateY(-5px);
      box-shadow: 
        0 12px 40px rgba(0, 0, 0, 0.15),
        0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .right-section:hover::before {
      opacity: 1;
    }

    .right-section > * {
      position: relative;
      z-index: 1;
    }

    .right-section h3 {
      font-size: 2rem;
      text-align: center;
      margin-bottom: 1.5rem;
      color: var(--white);
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .right-section p {
      font-size: 1.2rem;
      line-height: 1.8;
      color: rgba(255, 255, 255, 0.95);
      text-align: left;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
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

    @media (max-width: 1200px) {
      .page-wrapper {
        padding: 80px 20px 20px;
      }

      .home-container {
        padding: 2rem;
      }

      .header h1 {
        font-size: 2.5rem;
      }
    }

    @media (max-width: 1024px) {
      .content-box {
        grid-template-columns: 1fr;
        gap: 2rem;
      }
      
      .right-section {
        margin-top: 1rem;
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
    .tips-section {
      margin-top: 3rem;
      padding-top: 2rem;
      border-top: 1px solid rgba(255, 255, 255, 0.18);
      position: relative;
    }

    .tips-section h2 {
      font-family: 'Montserrat', sans-serif;
      font-size: 2rem;
      text-align: center;
      margin-bottom: 2rem;
      color: var(--white);
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
      position: relative;
      display: inline-block;
      left: 50%;
      transform: translateX(-50%);
    }

    .tips-section h2::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 0;
      width: 100%;
      height: 2px;
      background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.5),
        transparent
      );
    }

    .tip-card {
      background: linear-gradient(
        135deg,
        rgba(255, 255, 255, 0.12),
        rgba(255, 255, 255, 0.06)
      );
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-radius: 24px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 2.5rem;
      max-width: 600px;
      margin: 0 auto;
      color: var(--white);
      box-shadow: 
        0 8px 32px rgba(31, 38, 135, 0.2),
        0 4px 12px rgba(255, 255, 255, 0.05);
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .tip-card::before {
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
      transition: opacity 0.5s ease;
    }

    .tip-card:hover::before {
      opacity: 1;
    }

    .tip-card:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 
        0 15px 45px rgba(31, 38, 135, 0.25),
        0 5px 15px rgba(255, 255, 255, 0.1);
    }

    .tip-emoji {
      font-size: 3rem;
      margin-bottom: 1.5rem;
      text-align: center;
      text-shadow: 0 0 25px rgba(255, 255, 255, 0.5);
      transform-origin: center;
      transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      display: inline-block;
    }

    .tip-card:hover .tip-emoji {
      transform: scale(1.1) rotate(5deg);
    }

    .tip-title {
      font-size: 1.75rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
      text-align: center;
      color: rgba(255, 255, 255, 0.95);
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      position: relative;
    }

    .tip-content {
      font-size: 1.2rem;
      line-height: 1.8;
      margin-bottom: 2rem;
      color: rgba(255, 255, 255, 0.9);
      text-align: center;
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
    }

    .tip-controls {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .tip-btn {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      padding: 0.75rem 1.5rem;
      border-radius: 12px;
      color: rgba(255, 255, 255, 0.95);
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      font-family: 'Montserrat', sans-serif;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .tip-btn:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(31, 38, 135, 0.2);
    }

    .tip-btn:active {
      transform: translateY(0);
    }

    .tip-indicator {
      display: flex;
      justify-content: center;
      gap: 0.5rem;
      margin-top: 1.5rem;
    }

    .tip-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transition: all 0.3s ease;
    }

    .tip-dot.active {
      background: rgba(255, 255, 255, 0.9);
      transform: scale(1.2);
    }

    @media (max-width: 768px) {
      .tip-card {
        margin: 0 1rem;
        padding: 2rem;
      }

      .tip-emoji {
        font-size: 2.5rem;
      }

      .tip-title {
        font-size: 1.5rem;
      }

      .tip-content {
        font-size: 1.1rem;
      }
    }

    /* Add this to your existing script section */
    .fade-enter {
      opacity: 0;
      transform: translateY(20px);
    }

    .fade-enter-active {
      opacity: 1;
      transform: translateY(0);
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .fade-exit {
      opacity: 1;
      transform: translateY(0);
    }

    .fade-exit-active {
      opacity: 0;
      transform: translateY(-20px);
      transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Add this to your CSS section */
    .outfit-icons {
      font-size: 1.5rem;
      margin: 0.75rem 0;
      text-align: center;
      min-height: 2rem;
      display: flex;
      justify-content: center;
      gap: 0.75rem;
      flex-wrap: wrap;
      padding: 0.5rem;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 12px;
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
    }

    .outfit-icons span {
      display: inline-block;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
      cursor: pointer;
    }

    .outfit-icons span:hover {
      transform: scale(1.2) rotate(10deg);
      filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
    }

    @media (max-width: 768px) {
      .outfit-icons {
        font-size: 1.25rem;
        gap: 0.5rem;
        padding: 0.375rem;
      }
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
      <div class="logo">
        <i class="fas fa-tshirt"></i>
        WeatherWear
      </div>
      <div class="nav-links">
        <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
          <i class="fas fa-sun"></i>
        </button>
        <button class="homeBtn active">
          <i class="fas fa-home"></i>
          <span>Home</span>
        </button>
        <button class="aboutBtn" onclick="window.location.href='about.php'">
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

    <div class="home-container">
      <div class="header">
        <h1>Smart Outfit Recommendations</h1>
        <p>Get personalized clothing suggestions based on real-time weather data</p>
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
          <div class="result" id="result">
            <!-- Weather data will be displayed here -->
          </div>
        </div>
        <div class="right-section" id="outfitBox">
          <h3>Your Outfit Suggestion</h3>
          <p id="detail">Enter your city name to get personalized outfit recommendations based on the current weather conditions.</p>
        </div>
      </div>

      <div class="forecast-section" style="display: none;" id="forecastSection">
        <h2>9-Day Forecast</h2>
        <div class="forecast-grid" id="forecastGrid">
          <!-- Forecast cards will be inserted here -->
        </div>
      </div>

      <div class="tips-section">
        <h2>Style Tips & Weather Facts</h2>
        <div class="tip-card">
          <div class="tip-emoji">💡</div>
          <h3 class="tip-title">Did you know?</h3>
          <p class="tip-content" id="tipContent">Layering isn't just fashionable - it's a practical way to adapt to changing temperatures throughout the day. Start with a light base layer and add or remove pieces as needed.</p>
          <div class="tip-controls">
            <button class="tip-btn" onclick="prevTip()">
              <i class="fas fa-chevron-left"></i>
              Previous
            </button>
            <button class="tip-btn" onclick="showNextTip()">
              Next
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
          <div class="tip-indicator" id="tipIndicator"></div>
        </div>
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

    // Add loader functions at the start of your script
    function showLoader() {
      document.getElementById('loader').classList.add('show');
    }

    function hideLoader() {
      document.getElementById('loader').classList.remove('show');
    }

    // Update the getWeather function to use the loader
    async function getWeather() {
      if (!city) {
        city = document.getElementById('cityInput').value.trim();
        if (city === '') {
          alert('Please enter a city name.');
          document.getElementById('result').innerHTML = "";
          return;
        }
      }

      showLoader(); // Show loader before API calls

      try {
        // Save city to database
        await fetch('save_city.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ city }),
        });

        // Get current weather
        const weatherApiKey = '73d14412e88fa1c87f89770c1ae3b238';
        const weatherUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${weatherApiKey}&units=metric`;
        const weatherResponse = await fetch(weatherUrl);
        const weatherData = await weatherResponse.json();

        if (weatherData.cod !== 200) {
          hideLoader(); // Hide loader on error
          document.getElementById('result').innerHTML = `<p>${weatherData.message}</p>`;
          document.getElementById('outfitText').innerText = '';
          return;
        }

        // Get forecast data
        const forecastResponse = await fetch(`get_forecast.php?city=${encodeURIComponent(city)}`);
        const forecastData = await forecastResponse.json();

        if (forecastData.error) {
          hideLoader(); // Hide loader on error
          console.error('Forecast error:', forecastData.error);
          return;
        }

        // Update current weather display
        const temp = weatherData.main.temp;
        const weather = weatherData.weather[0].main.toLowerCase();
        const description = weatherData.weather[0].description;
        const recommendation = await getClothingRecommendation(temp, weatherData.name);
        const detailedRecommendation = await getDetailedRecommendation(temp, weatherData.name);

        changeBackground(weather);

        document.getElementById('result').innerHTML = `
          <h2>${weatherData.name}, ${weatherData.sys.country}</h2>
          <p><strong>Temperature:</strong> ${temp}°C</p>
          <p><strong>Condition:</strong> ${description}</p>
        `;
        
        // Animate outfit text and box
        const outfitText = document.getElementById('outfitText');
        const outfitBox = document.getElementById('outfitBox');
        
        outfitText.innerText = recommendation;
        outfitBox.innerHTML = `<p>${detailedRecommendation}</p>`;
        
        // Add animation classes
        outfitText.classList.add('animate-card');
        outfitBox.classList.add('animate-card');
        
        // Trigger animations with slight delay
        setTimeout(() => {
          outfitText.classList.add('show');
        }, 100);
        
        setTimeout(() => {
          outfitBox.classList.add('show');
        }, 200);

        // Display and animate forecast section
        const forecastSection = document.getElementById('forecastSection');
        forecastSection.style.display = 'block';
        forecastSection.classList.add('animate-card');
        
        setTimeout(() => {
          forecastSection.classList.add('show');
        }, 300);

        // Display forecast with staggered animations
        await displayForecast(forecastData.forecast);

      } catch (error) {
        console.error('Error:', error);
        alert('Failed to fetch weather data.');
      } finally {
        hideLoader(); // Hide loader when everything is done
        city = "";
      }
    }

    async function getClothingRecommendation(temp, city) {
      let prompt = `Given the temperature of ${temp}°C in ${city || 'this location'}, suggest a very brief outfit recommendation (max 10 words).`;
      let botResponse = await getBotReply(prompt);
      return botResponse || 'Light, comfortable clothing recommended.';
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
      body.classList.remove('weather-clear', 'weather-clouds', 'weather-rain', 'weather-snow', 'weather-storm');

      if (weather.includes('clear')) {
        body.classList.add('weather-clear');
      } else if (weather.includes('cloud')) {
        body.classList.add('weather-clouds');
      } else if (weather.includes('rain')) {
        body.classList.add('weather-rain');
      } else if (weather.includes('snow')) {
        body.classList.add('weather-snow');
      } else if (weather.includes('storm')) {
        body.classList.add('weather-storm');
      }
    }

    async function displayForecast(forecast) {
      const forecastGrid = document.getElementById('forecastGrid');
      forecastGrid.innerHTML = '';

      console.log('Original forecast data:', forecast); // Debug log

      // Create a map to store unique days
      const uniqueDays = new Map();
      
      // Process only the first 5 unique days
      let count = 0;
      for (const day of forecast) {
        if (count >= 5) break; // Stop after 5 days
        
        if (!uniqueDays.has(day.day)) {
          uniqueDays.set(day.day, day);
          count++;
        }
      }

      console.log('Processed unique days:', Array.from(uniqueDays.values())); // Debug log

      // Display exactly 5 days
      let index = 0;
      for (const day of uniqueDays.values()) {
        if (index >= 5) break; // Extra safety check
        
        const outfitSuggestion = await getClothingRecommendation(day.temp_max, city);
        const outfitIcons = getOutfitIcons(outfitSuggestion);
        
        const card = document.createElement('div');
        card.className = 'forecast-card';
        card.innerHTML = `
          <div class="forecast-card-header">
            <div class="day">${day.day}</div>
            <div class="date">${formatDate(day.date)}</div>
          </div>
          <div class="forecast-card-weather">
            <img class="weather-icon" src="https://openweathermap.org/img/wn/${day.icon}@2x.png" alt="${day.weather}">
            <div class="temp">${Math.round(day.temp_max)}°C / ${Math.round(day.temp_min)}°C</div>
            <div class="weather">${day.description}</div>
          </div>
          <div class="forecast-card-outfit">
            <div class="outfit-icons">${outfitIcons}</div>
            <div class="outfit">${outfitSuggestion}</div>
          </div>
        `;
        forecastGrid.appendChild(card);

        // Add staggered animation delay
        setTimeout(() => {
          card.classList.add('show');
        }, 100 * index);

        index++;
      }

      console.log('Final number of cards rendered:', index); // Debug log
    }

    function getOutfitIcons(suggestion) {
      const keywords = {
        // Tops
        't-shirt': '👕',
        'tshirt': '👕',
        'shirt': '👕',
        'top': '👕',
        'blouse': '👚',
        'tank': '🎽',
        
        // Outerwear
        'jacket': '🧥',
        'coat': '🧥',
        'sweater': '🧥',
        'hoodie': '🧥',
        'cardigan': '🧥',
        'blazer': '🧥',
        'vest': '🦺',
        
        // Weather Protection
        'umbrella': '☂️',
        'rain': '☂️',
        'raincoat': '☔',
        
        // Accessories
        'scarf': '🧣',
        'neck': '🧣',
        'cap': '🧢',
        'hat': '🧢',
        'beanie': '🧢',
        'sunglasses': '🕶️',
        'shades': '🕶️',
        'sun': '🕶️',
        'gloves': '🧤',
        'mittens': '🧤',
        'socks': '🧦',
        'boots': '👢',
        
        // Bottoms
        'pants': '👖',
        'jeans': '👖',
        'trousers': '👖',
        'shorts': '🩳',
        'skirt': '👗',
        'dress': '👗',
        
        // Footwear
        'shoes': '👟',
        'sneakers': '👟',
        'sandals': '👡',
        
        // Seasonal
        'swimsuit': '🩱',
        'swimwear': '🩱',
        'bikini': '👙',
        
        // Layers
        'layer': '👕',
        'lightweight': '👕',
        'warm': '🧥',
        'light': '👕',
        'heavy': '🧥',
        
        // Weather conditions
        'cold': '🧥',
        'hot': '👕',
        'cool': '🧥',
        'chilly': '🧥',
        'sunny': '🕶️',
        'rainy': '☔',
        'windy': '🧣'
      };

      const icons = new Set();
      const lowerSuggestion = suggestion.toLowerCase();

      // Check each keyword against the suggestion
      for (const [keyword, icon] of Object.entries(keywords)) {
        // Use word boundary check for more accurate matching
        const regex = new RegExp(`\\b${keyword}\\b`, 'i');
        if (regex.test(lowerSuggestion)) {
          icons.add(icon);
        }
      }

      // Add default clothing icon if no matches found
      if (icons.size === 0) {
        icons.add('👕');
      }

      // Limit to maximum 4 icons to prevent overcrowding
      return Array.from(icons).slice(0, 4).join(' ');
    }

    function formatDate(dateStr) {
      const date = new Date(dateStr);
      return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    document.querySelector('.aboutBtn').addEventListener('click', function() {
      window.location.href = 'about.php';
    });
    document.querySelector('.profileBtn')?.addEventListener('click', function() {
      window.location.href = 'profile.php';
    });

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

    let currentTipIndex = 0;

    function updateTipIndicators() {
      const indicatorContainer = document.getElementById('tipIndicator');
      indicatorContainer.innerHTML = '';
      
      tips.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.className = `tip-dot ${index === currentTipIndex ? 'active' : ''}`;
        indicatorContainer.appendChild(dot);
      });
    }

    function showNextTip() {
      const tipContent = document.getElementById('tipContent');
      tipContent.classList.add('fade-exit-active');
      
      setTimeout(() => {
        currentTipIndex = (currentTipIndex + 1) % tips.length;
        tipContent.textContent = tips[currentTipIndex];
        updateTipIndicators();
        
        tipContent.classList.remove('fade-exit-active');
        tipContent.classList.add('fade-enter');
        
        requestAnimationFrame(() => {
          tipContent.classList.add('fade-enter-active');
          tipContent.classList.remove('fade-enter');
        });
      }, 300);
    }

    function prevTip() {
      const tipContent = document.getElementById('tipContent');
      tipContent.classList.add('fade-exit-active');
      
      setTimeout(() => {
        currentTipIndex = (currentTipIndex - 1 + tips.length) % tips.length;
        tipContent.textContent = tips[currentTipIndex];
        updateTipIndicators();
        
        tipContent.classList.remove('fade-exit-active');
        tipContent.classList.add('fade-enter');
        
        requestAnimationFrame(() => {
          tipContent.classList.add('fade-enter-active');
          tipContent.classList.remove('fade-enter');
        });
      }, 300);
    }

    // Initialize tip indicators
    document.addEventListener('DOMContentLoaded', updateTipIndicators);

    // Auto-advance tips every 10 seconds
    setInterval(showNextTip, 10000);

    // Add this at the beginning of your script section
    function initTheme() {
      // Check for saved theme preference or default to 'light'
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
      if (theme === 'dark') {
        themeIcon.classList.remove('fa-sun');
        themeIcon.classList.add('fa-moon');
      } else {
        themeIcon.classList.remove('fa-moon');
        themeIcon.classList.add('fa-sun');
      }
    }

    // Initialize theme
    document.addEventListener('DOMContentLoaded', initTheme);

    // Add event listener for theme toggle
    document.getElementById('themeToggle').addEventListener('click', toggleTheme);

    // Add function to reset animations when starting new search
    function resetAnimations() {
      const elements = document.querySelectorAll('.animate-card, .forecast-card');
      elements.forEach(element => {
        element.classList.remove('show');
      });
    }

    // Update the cityInput event listener to reset animations
    document.getElementById('cityInput').addEventListener('keydown', function(event) {
      if (event.key === 'Enter') {
        resetAnimations();
        getWeather();
      }
    });

    // Update the check weather button click handler
    document.querySelector('.weather-input button').addEventListener('click', function() {
      resetAnimations();
      getWeather();
    });
  </script>
</body>

</html>