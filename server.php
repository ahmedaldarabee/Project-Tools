<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Start session
session_start();

$response = array();

// Establish database connection
$connection = mysqli_connect("localhost", "root", "", "VentureLink");

if ($connection) {
// Handle login
if (!isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM `users` WHERE `Email`='$email' AND `Password`='$password'";
    $connectionQuery = mysqli_query($connection, $query);

    if ($connectionQuery && mysqli_num_rows($connectionQuery) > 0) {
        // Fetch user data
        $userData = mysqli_fetch_assoc($connectionQuery);
        
        // Set session variables
        $_SESSION['email'] = $email;
        $_SESSION['loggedin'] = true;
        $_SESSION['name'] = $userData['Name'];
        $_SESSION['type'] = $userData['Type'];

        $response['success'] = true;
        $response['message'] = "Login successful!";
        $response['name'] = $userData['Name'];
        $response['type'] = $userData['Type'];
    } else {
        $response['success'] = false;
        $response['message'] = "Incorrect email or password!";
    }
}
    
    // Handle registration
    elseif (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $type = $_POST['type'];

        $query = "INSERT INTO `users` (Name, Email, Password, Type) VALUES ('$name', '$email', '$password', '$type')";
        $connectionQuery = mysqli_query($connection, $query);

        if ($connectionQuery) {
            $response['success'] = true;
            $response['message'] = "Added successfully!";
        } else {
            $response['success'] = false;
            $response['message'] = "Error occurred, try again.";
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Invalid request!";
    }   
    mysqli_close($connection);
} else {
    $response['success'] = false;
    $response['message'] = "Error connecting to MySQL server!";
}

echo json_encode($response);
?>
