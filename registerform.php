<?php
// Database configuration
$host = "localhost";  // Your database host (e.g., "localhost")
$username = "root";   // Your database username
$password = "";       // Your database password
$database = "caloriecounter";  // Your database name

// Create a database connection
$con = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to sanitize and validate input
function sanitizeInput($input, $conn)
{
    $input = htmlspecialchars(trim($input));
    return $conn->real_escape_string($input);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user input
    $name = sanitizeInput($_POST['name'], $con);
    $email = sanitizeInput($_POST['email'], $con);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirm_password = sanitizeInput($_POST['confirm_password'], $con);

    // Check if password matches confirmation password
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $errorMessage = 'Error: Passwords do not match.';
    } else {
        // Insert user information into the database
        $sql = "INSERT INTO users (user_name, user_email, user_password) VALUES ('$name', '$email', '$password')";

        if ($con->query($sql) === TRUE) {
            // Redirect to a success page or perform other actions as needed
            header('Location: loginform.php');
            exit();
        } else {
            $errorMessage = 'Error: ' . $sql . '<br>' . $con->error;
        }
    }
}

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CALORIE COUNTER: FOOD DIARY</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    
    <style>
        body {
            background-color: #6CBB3C;
            color: #fff;
        }

        .register-container {
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
    </style>
</head>
<body>

<div class="container register-container">
    <h2 class="text-center mb-4">CALORIE COUNTER: FOOD DIARY</h2>
    
    <div class="card">
        <div class="card-body">
            <?php if (isset($errorMessage)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <div class="icon"><i class="fas fa-user"></i></div>
                    <input type="text" class="form-control" placeholder="Name" name="name" required>
                </div>
                <div class="form-group">
                    <div class="icon"><i class="fas fa-envelope"></i></div>
                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                </div>
                <div class="form-group">
                    <div class="icon"><i class="fas fa-lock"></i></div>
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
                <div class="form-group">
                    <div class="icon"><i class="fas fa-lock"></i></div>
                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-success btn-block">Sign Up</button>
            </form>

        </div>
    </div>

    <p class="separated-text">Already have an account? <a href="loginform.php">Login now</a></p>
    <p class="separated-text">By signing up, you agree to Terms of Service.</p>
</div>

<!-- Bootstrap JS and Popper.js (for Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>