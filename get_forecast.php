<?php
header('Content-Type: application/json');
require_once 'connect.php';

// OpenWeatherMap API configuration
$api_key = '73d14412e88fa1c87f89770c1ae3b238';

function getForecast($city) {
    global $api_key;
    
    // First, get coordinates for the city
    $geocoding_url = "http://api.openweathermap.org/geo/1.0/direct?q=" . urlencode($city) . "&limit=1&appid=" . $api_key;
    
    $geo_response = file_get_contents($geocoding_url);
    $geo_data = json_decode($geo_response, true);
    
    if (empty($geo_data)) {
        return array('error' => 'City not found');
    }
    
    $lat = $geo_data[0]['lat'];
    $lon = $geo_data[0]['lon'];
    
    // Now get the 7-day forecast using One Call API
    $forecast_url = "https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$api_key}&units=metric&cnt=40"; // 5 days, 3-hour intervals
    
    $forecast_response = file_get_contents($forecast_url);
    $forecast_data = json_decode($forecast_response, true);
    
    if (!$forecast_data || isset($forecast_data['cod']) && $forecast_data['cod'] !== "200") {
        return array('error' => 'Failed to fetch forecast data');
    }
    
    // Process and format the forecast data
    $processed_forecast = array();
    $current_date = '';
    $daily_data = null;
    
    foreach ($forecast_data['list'] as $item) {
        $date = date('Y-m-d', $item['dt']);
        
        if ($date !== $current_date) {
            // If we have collected data for a day, save it
            if ($daily_data !== null) {
                $processed_forecast[] = $daily_data;
            }
            
            // Start collecting data for new day
            $current_date = $date;
            $daily_data = array(
                'date' => $date,
                'day' => date('l', $item['dt']),
                'temp_max' => $item['main']['temp_max'],
                'temp_min' => $item['main']['temp_max'],
                'weather' => $item['weather'][0]['main'],
                'description' => $item['weather'][0]['description'],
                'icon' => $item['weather'][0]['icon']
            );
        } else {
            // Update min/max temperatures
            $daily_data['temp_max'] = max($daily_data['temp_max'], $item['main']['temp_max']);
            $daily_data['temp_min'] = min($daily_data['temp_min'], $item['main']['temp_min']);
        }
    }
    
    // Add the last day if we have it
    if ($daily_data !== null) {
        $processed_forecast[] = $daily_data;
    }
    
    // Limit to 5 days (as that's what the free API provides)
    $processed_forecast = array_slice($processed_forecast, 0, 5);
    
    return array(
        'city' => $forecast_data['city']['name'],
        'country' => $forecast_data['city']['country'],
        'forecast' => $processed_forecast
    );
}

// Get city from request
$city = isset($_GET['city']) ? $_GET['city'] : '';

if (empty($city)) {
    echo json_encode(array('error' => 'City parameter is required'));
    exit;
}

$forecast_data = getForecast($city);
echo json_encode($forecast_data); 