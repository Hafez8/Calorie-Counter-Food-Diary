<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summarize Meals</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-image: url('images/bg_1.jpg'); /* Add the URL to your background image */
            background-size: cover; /* Ensure the background image covers the entire page */
            background-repeat: no-repeat; /* Prevent the image from repeating */
            background-attachment: fixed; /* Fix the background image in place */
            color: #000; /* Black text color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 600px; /* Adjust container width */
            margin: auto; /* Center container horizontally and vertically */
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 20px;
            background-color: #6c757d; /* Grey card background color */
            color: #fff; /* White text color */
            text-align: center; /* Center text inside the card */
            padding: 20px; /* Add padding inside the card */
        }

        .card-header {
            background-color: #495057; /* Dark grey card header background color */
            color: #fff; /* White text color */
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            text-align: center;
            padding: 10px 0; /* Add padding to the card header */
        }

        .form-control {
            color: #000; /* Black text color for form inputs */
        }

        .btn {
            width: 100%; /* Make buttons take the full width of the form */
            margin-top: 10px; /* Add some margin on top of the buttons */
        }

        .card-body .card-title,
        .card-body .card-text {
            color: #fff; /* White text color for results */
        }

        .done-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Summarize Your Meals</h2>
        <form action="" method="post">
            <label for="day">Select Day:</label>
            <select name="day" id="day" class="form-control">
                <!-- PHP logic to populate options from user's diary -->
                <?php
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

                // Fetch available days from the user's diary
                session_start();
                $userId = $_SESSION['userId'];
                $diarySql = "SELECT DISTINCT date FROM meals WHERE diary_id IN (SELECT diary_id FROM diary WHERE user_id = ?)";
                $stmt = $con->prepare($diarySql);
                $stmt->bind_param('i', $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                // Populate options in the dropdown menu
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['date'] . "'>" . $row['date'] . "</option>";
                }

                // Close statement and connection
                $stmt->close();
                ?>
            </select>
            <button type="submit" class="btn btn-primary mt-2" name="summarize">Summarize Now</button>
        </form>

        <!-- PHP logic to summarize meals -->
        <?php
        if (isset($_POST['summarize'])) {
            // Retrieve selected day from the form
            $selectedDay = $_POST['day'];

            // Fetch meals data from the database for the selected day
            $con = new mysqli($host, $username, $password, $database);
            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }

            $sql = "SELECT * FROM meals WHERE diary_id IN (SELECT diary_id FROM diary WHERE user_id = ?) AND date = ?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param('is', $userId, $selectedDay);
            $stmt->execute();
            $result = $stmt->get_result();

            // Initialize variables to store totals for each meal type and overall totals
            $totals = array(
                'breakfast' => array('calories' => 0, 'protein' => 0, 'carbohydrates' => 0, 'foods' => array()),
                'lunch' => array('calories' => 0, 'protein' => 0, 'carbohydrates' => 0, 'foods' => array()),
                'dinner' => array('calories' => 0, 'protein' => 0, 'carbohydrates' => 0, 'foods' => array()),
                'snacks' => array('calories' => 0, 'protein' => 0, 'carbohydrates' => 0, 'foods' => array())
            );
            $overallTotals = array('calories' => 0, 'protein' => 0, 'carbohydrates' => 0);

            // Calculate total calories, protein, and carbohydrate for each meal type and overall totals
            while ($row = $result->fetch_assoc()) {
                $mealType = $row['meal_type'];
                if (array_key_exists($mealType, $totals)) {
                    $totals[$mealType]['calories'] += $row['calories'];
                    $totals[$mealType]['protein'] += $row['protein'];
                    $totals[$mealType]['carbohydrates'] += $row['carbohydrates'];
                    $totals[$mealType]['foods'][] = $row['food_name'];

                    // Add to overall totals
                    $overallTotals['calories'] += $row['calories'];
                    $overallTotals['protein'] += $row['protein'];
                    $overallTotals['carbohydrates'] += $row['carbohydrates'];
                }
            }

            // Close the statement and connection
            $stmt->close();
            $con->close();

            // Display the results in a card format
            echo "<div class='card mt-3'>";
            echo "<div class='card-header'>YOU'VE MADE IT, USER!</div>";
            echo "<div class='card-body'>";
            foreach ($totals as $mealType => $total) {
                echo "<h5 class='card-title'>" . ucfirst($mealType) . "</h5>";
                echo "<p class='card-text'>Foods: " . implode(', ', $total['foods']) . "</p>";
                echo "<p class='card-text'>Total Calories: " . $total['calories'] . "</p>";
                echo "<p class='card-text'>Total Protein: " . $total['protein'] . "</p>";
                echo "<p class='card-text'>Total Carbohydrates: " . $total['carbohydrates'] . "</p>";
                echo "<hr>";
            }
            echo "<h4>Overall Totals for the Day</h4>";
            echo "<p class='card-text'>Total Calories: " . $overallTotals['calories'] . "</p>";
            echo "<p class='card-text'>Total Protein: " . $overallTotals['protein'] . "</p>";
            echo "<p class='card-text'>Total Carbohydrates: " . $overallTotals['carbohydrates'] . "</p>";
            echo "</div>";
            echo "</div>";
            echo "<form action='dashboard.php' method='post'>";
            echo "<button type='submit' class='btn btn-secondary done-btn'>Done</button>";
            echo "</form>";
        }
        ?>
    </div>
</body>
</html>
