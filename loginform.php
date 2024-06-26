<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    $email = isset($_POST['email']) ? sanitizeInput($_POST['email'], $con) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Check if the user exists in the database
    $sql = "SELECT user_id, user_name, user_password FROM users WHERE user_email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, verify password
        $row = $result->fetch_assoc();
        $storedPassword = $row['user_password'];

        if (password_verify($password, $storedPassword)) {
            // Password is correct, retrieve the user's ID and name
            $userId = $row['user_id'];
            $name = $row['user_name'];
            $email = $row['user_email'];

            // Start session and store the user's ID and name in session variables
            session_start();
            $_SESSION['userId'] = $userId;
            $_SESSION['userName'] = $name;
            $_SESSION['email'] = $email;

            // Redirect to dashboard
            header('Location: dashboard.php');
            exit();
        } else {
            $errorMessage = 'Invalid password';
        }
    } else {
        $errorMessage = 'User not found';
    }

    $stmt->close();
}

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CALORIE COUNTER: FOOD DIARY</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #6CBB3C;
            color: #fff;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
        }
        .card {
            border: none;
            border-radius: 20px;
            background-color: #6c757d;
            color: #fff;
        }
        .card-header {
            background-color: #495057;
            color: #fff;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }
        .icon {
            padding: 10px;
        }
        .form-group {
            position: relative;
        }
        .form-group input {
            padding-left: 40px;
        }
        .form-group .icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 10px;
            color: #495057;
        }
        .bottom-text {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }
        .separated-text {
            margin-top: 20px;
            text-align: center;
        }
        .title {
            text-align: center;
            color: #fff;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container login-container">
    <h2 class="title">CALORIE COUNTER: FOOD DIARY</h2>
    
    <div class="card">
        <div class="card-body">
            <?php if (isset($errorMessage)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                </div>
                <div class="form-group">
                    <div class="icon"><i class="fas fa-lock"></i></div>
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>

    <p class="separated-text">Don't have an account? <a href="registerform.php">Sign up now</a></p>
    <p class="separated-text">By signing up, you agree to Terms of Service.</p>
</div>

<!-- Bootstrap JS and Popper.js (for Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
