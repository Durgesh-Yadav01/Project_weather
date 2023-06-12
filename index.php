<!DOCTYPE html>
<html>
  <head>
    <title>Weather App</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="container">
     <div class="weather">
      <form action="#" method="get">
     <input type="text" id="city-name" placeholder="Enter city name" name="place"/>
     <input type="submit" value="Submit">
    </form>
     
<?php

if(isset($_GET['place'])){
  
  $servername = "localhost";
  $username = "root";
  $password = "mysql";
  $dbname = "weatherapp";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Escape user input to avoid SQL injection
  $cityName = mysqli_real_escape_string($conn, $_GET['place']);

  // Fetch weather data from API
  $apiKey = 'f640f63f1035aa1fcf46be12edd2c122';
  $url = "https://api.openweathermap.org/data/2.5/weather?q=$cityName&appid=$apiKey";
  $response = file_get_contents($url);
  $data = json_decode($response, true);

  // Insert weather data into database
  $sql = "INSERT INTO weather_data (city_name, temperature, cloud_description, wind_speed, pressure, humidity) VALUES ('$cityName', '{$data['main']['temp']}', '{$data['weather'][0]['description']}', '{$data['wind']['speed']}', '{$data['main']['pressure']}', '{$data['main']['humidity']}')";

  if ($conn->query($sql) === TRUE) {
  
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  // Display weather data
  $weatherData = "
    <p>City: $cityName</p>
    <p>Temperature: {$data['main']['temp']} K</p>
    <p>Cloud description: {$data['weather'][0]['description']}</p>
    <p>Wind speed: {$data['wind']['speed']} mph</p>
    <p>Pressure: {$data['main']['pressure']} hPa</p>
    <p>Humidity: {$data['main']['humidity']} %</p>
  ";

  echo "<div id='weather-data'>$weatherData</div>";

  $conn->close();
}

?>

     </div>
    </div>
  </body>
</html>