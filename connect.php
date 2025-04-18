<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'weatherwear';
$conn = mysqli_connect($host, $user, $password, $db);
if($conn->connect_error) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
}
?>

