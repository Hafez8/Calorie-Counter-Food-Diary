<?php

// Database configuration
$host = "localhost";  // Your database host (e.g., "localhost")
$username = "root";  // Your database username
$password = "";  // Your database password
$database = "caloriecounter";  // Your database name

// Create a database connection
$con = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

?>