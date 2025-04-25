<?php
// This is a helper file to link between pages

// Link to landing page
function getLandingPage() {
  return "index.php";
}

// Link to main application (home page)
function getHomePage() {
  return "home.php";
}

// Link to the forecast page
function getForecastPage() {
  return "forecast.php";
}

// Link to the about page
function getAboutPage() {
  return "about.php";
}

// Link to login page
function getLoginPage() {
  return "login.php";
}

// Link to logout page
function getLogoutPage() {
  return "logout.php";
}

// Check if user is logged in
function isLoggedIn() {
  return isset($_SESSION['user_id']) || isset($_SESSION['email']);
}
?> 