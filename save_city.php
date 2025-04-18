<?php
session_start();
include 'connect.php';

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (isset($_SESSION['email']) && isset($data['city'])) {
    $email = $_SESSION['email'];
    $city = $conn->real_escape_string($data['city']);

    // Update the city in the database
    $sql = "UPDATE users SET city = '$city' WHERE email = '$email'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>