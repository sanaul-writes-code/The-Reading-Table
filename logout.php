<?php

// Initialize the session
session_start();

// Unset all session variables
$_SESSION = array();

// Unset the session cookie by setting its expiration time to a past value
setcookie(session_name(), '', time() - 3600, '/');

// Destroy the session
session_destroy();

// Redirect the user to another page or perform other actions
header("Location: login.php");
exit;