# WeatherWear - Smart Outfit Suggestions

WeatherWear is a web application that provides users with personalized clothing recommendations based on current weather conditions. The app integrates with weather APIs to deliver accurate outfit suggestions tailored to local weather forecasts.

## Project Overview

WeatherWear aims to solve the daily challenge of choosing appropriate clothing for the weather. By analyzing temperature, precipitation, wind, and other weather factors, the application suggests suitable outfits, helping users dress comfortably and appropriately for the day's conditions.

## Features

- **Real-time Weather Data**: Fetches current weather information for any city globally
- **Personalized Outfit Recommendations**: Provides clothing suggestions based on temperature and weather conditions
- **5-Day Weather Forecast**: Shows extended weather predictions with corresponding outfit suggestions for each day
- **User Accounts**: Allows users to save preferences and location for quicker access
- **Style Tips**: Offers fashion advice and weather-related clothing information
- **Responsive Design**: Optimized for desktop and mobile devices

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **APIs**: OpenWeatherMap API
- **Styling**: Custom CSS with responsive design
- **Icons**: Font Awesome

## Installation and Setup

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/weather-wear.git
   ```

2. Move the files to your web server directory (e.g., XAMPP's htdocs folder)

3. Create a MySQL database and import the included SQL file

4. Configure the database connection in `connect.php`

5. Obtain an API key from [OpenWeatherMap](https://openweathermap.org/api) and update it in the configuration

6. Access the application through your web browser

## Usage

1. Enter a city name in the search box
2. View current weather conditions for that location
3. Check the recommended outfit based on the current weather
4. Explore the 5-day forecast with corresponding outfit suggestions
5. Create an account to save your preferences and location

## Project Structure

- `home.php` - Main application page with weather search and display
- `forecast.php` - Extended forecast view
- `login.php` & `register.php` - User authentication
- `get_weather.php` - API handler for current weather data
- `get_forecast.php` - API handler for forecast data
- `connect.php` - Database connection configuration

## Requirements Specification

### Functional Requirements

1. The system shall provide users with clothing suggestions based on current weather conditions
2. The system shall allow users to search for weather information by city name
3. The system shall display temperature, humidity, wind speed, and general weather conditions
4. The system shall provide a 5-day weather forecast with corresponding outfit recommendations
5. The system shall support user registration and profile management
6. The system shall remember user preferences and location when logged in

### Non-Functional Requirements

1. The application shall be responsive and accessible on desktop and mobile devices
2. The application shall provide accurate weather data with a maximum 3-hour refresh rate
3. The application shall load search results within 3 seconds under normal network conditions
4. The system shall handle at least 1000 concurrent users
5. The user interface shall be intuitive and user-friendly

## Future Enhancements

- Integration with clothing retailers for purchasing recommendations
- Machine learning for more personalized recommendations based on user feedback
- Social sharing features for outfit suggestions
- Push notifications for weather alerts and daily outfit recommendations
- Location-based services for automatic weather detection

## License

[Add your license information here]

## Contact

[Add your contact information here] 