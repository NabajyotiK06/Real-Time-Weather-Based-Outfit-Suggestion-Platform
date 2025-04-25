<?php
header('Content-Type: application/json');
require_once 'connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// OpenWeatherMap API configuration
$api_key = '73d14412e88fa1c87f89770c1ae3b238';

function getCurrentWeather($city) {
    global $api_key;
    
    // Get current weather data
    $weather_url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=" . $api_key . "&units=metric";
    
    // Log the URL for debugging (remove in production)
    error_log("Fetching URL: " . $weather_url);
    
    $context = stream_context_create([
        'http' => [
            'ignore_errors' => true,
            'timeout' => 30
        ]
    ]);
    
    $weather_response = @file_get_contents($weather_url, false, $context);
    
    if ($weather_response === false) {
        error_log("API request failed: " . error_get_last()['message']);
        return array('error' => 'Failed to connect to weather service');
    }
    
    $weather_data = json_decode($weather_response, true);
    
    // Log the response for debugging
    error_log("API Response: " . print_r($weather_data, true));
    
    if (isset($weather_data['cod']) && $weather_data['cod'] !== 200) {
        return array('error' => isset($weather_data['message']) ? $weather_data['message'] : 'City not found');
    }
    
    // Process and return the weather data
    return array(
        'city' => $weather_data['name'],
        'country' => $weather_data['sys']['country'],
        'temp' => $weather_data['main']['temp'],
        'feels_like' => $weather_data['main']['feels_like'],
        'humidity' => $weather_data['main']['humidity'],
        'pressure' => $weather_data['main']['pressure'],
        'weather' => $weather_data['weather'][0]['main'],
        'description' => $weather_data['weather'][0]['description'],
        'icon' => $weather_data['weather'][0]['icon'],
        'wind_speed' => $weather_data['wind']['speed'],
        'clouds' => $weather_data['clouds']['all'],
        'timestamp' => $weather_data['dt']
    );
}

// Get city from request
$city = isset($_GET['city']) ? $_GET['city'] : '';

if (empty($city)) {
    echo json_encode(array('error' => 'City parameter is required'));
    exit;
}

// Try to save the city for the logged-in user (if applicable)
session_start();
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $conn = mysqli_connect($host, $user, $pass, $db);
    
    if ($conn) {
        $safe_city = mysqli_real_escape_string($conn, $city);
        $safe_username = mysqli_real_escape_string($conn, $username);
        
        // Update or insert the city preference
        $query = "INSERT INTO user_preferences (username, preferred_city) 
                  VALUES ('$safe_username', '$safe_city') 
                  ON DUPLICATE KEY UPDATE preferred_city = '$safe_city'";
        
        mysqli_query($conn, $query);
        mysqli_close($conn);
    }
}

// Get and return the weather data
$weather_data = getCurrentWeather($city);
echo json_encode($weather_data);
?> 