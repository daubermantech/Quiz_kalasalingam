<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_email'])) {
    // Redirect to the login page
    header("Location: login.html");
    exit();
}

// Logout logic
if (isset($_POST['logout'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Send a response indicating successful logout
    echo 'Logout successful';
    exit();
} else {
    // If 'logout' is not set, redirect to the login page
    header("Location: login.html");
    exit();
}
