<?php
session_start(); // Start the session

// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "caloriecounter";

// Create a database connection
$con = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Function to sanitize and validate input
function sanitizeInput($input, $conn) {
    $input = htmlspecialchars(trim($input));
    return $conn->real_escape_string($input);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $diaryName = sanitizeInput($_POST['diary_name'], $con);
    $currentWeight = sanitizeInput($_POST['current_weight'], $con);
    $targetWeight = sanitizeInput($_POST['target_weight'], $con);
    $height = sanitizeInput($_POST['height'], $con);
    $gender = sanitizeInput($_POST['gender'], $con);
    $startDate = sanitizeInput($_POST['start_date'], $con);
    $selfQuote = sanitizeInput($_POST['self_quote'], $con);
    $userId = $_SESSION['userId']; // Get the user ID from session

    // Insert diary information into the database
    $sql = "INSERT INTO diary (diary_name, current_weight, target_weight, height, gender, start_date, self_quote, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sidssssi", $diaryName, $currentWeight, $targetWeight, $height, $gender, $startDate, $selfQuote, $userId);

    if ($stmt->execute()) {
        // Redirect to the dashboard after successful insertion
        header('Location: dashboard.php');
        exit();
    } else {
        $errorMessage = 'Error: ' . $sql . '<br>' . $con->error;
    }

    // Close the statement and database connection
    $stmt->close();
}

// Close the database connection
$con->close();
?>
