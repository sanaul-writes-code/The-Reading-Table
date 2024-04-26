<?php
session_start();
// Set session cookie parameters
$sessionLifetime = 0; // expire when the browser is closed
$sessionPath = '/';
$sessionDomain = ''; // optional, specify your domain
$sessionSecure = true; // if using HTTPS
$sessionHttpOnly = true; // prevent JavaScript access to the session cookie

session_set_cookie_params($sessionLifetime, $sessionPath, $sessionDomain, $sessionSecure, $sessionHttpOnly);

// Database connection variables
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Validate username and password
$_SESSION['uname'] = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$password = md5($password); // Hash the password using md5

$sql = "SELECT * FROM users WHERE username='{$_SESSION["uname"]}' AND password='$password'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {
    // User authenticated, set session variable
    $_SESSION['authenticated'] = true;
    header('Location: my-profile.php'); // Redirect to the form page
} else {
    // Invalid credentials, redirect to login page
    $error_message = "Incorrect username or password. Please try again.";
    // header('Location: login.php');
    header('Location: login.php?error=' . urlencode($error_message));
}

mysqli_close($conn);
?>
