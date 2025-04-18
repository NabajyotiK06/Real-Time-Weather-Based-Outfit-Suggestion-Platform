<?php
include 'connect.php';
if(isset($_POST['signUp'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check_email);
    if($result->num_rows > 0) {
        header("Location: exists.php");
        exit();
    } else {
        // Insert the new user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        if($conn->query($sql) === TRUE) {
            header("Location: login.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the user exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if(password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['email'] = $row['email'];
            header("Location: home.php");
            exit();
        } else {
            header("Location: invalid.php");
        }
    } else {
        header("Location: invalid.php");
    }
}
?>