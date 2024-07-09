<?php
// Database connection
$host = "localhost";  // Your database host (e.g., "localhost")
$username = "root";    // Your database username
$password = "";        // Your database password
$database = "caloriecounter";  // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input data
    $meal_type = $_POST['mealType'];
    $food_names = $_POST['foodName'];
    $calories = $_POST['calories'];
    $proteins = $_POST['protein'];
    $carbohydrates = $_POST['carbohydrates'];
    $date = $_POST['date'];

    // Retrieve user's diary_id based on session
    session_start();
    $userId = $_SESSION['userId'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT diary_id FROM diary WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the diary exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $diary_id = $row['diary_id'];

        // Insert meals into the database
        for ($i = 0; $i < count($food_names); $i++) {
            $food_name = $food_names[$i];
            $calorie = $calories[$i];
            $protein = $proteins[$i];
            $carbohydrate = $carbohydrates[$i];

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO meals (diary_id, meal_type, food_name, calories, protein, carbohydrates, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssdds", $diary_id, $meal_type, $food_name, $calorie, $protein, $carbohydrate, $date);

            // Execute the statement
            if ($stmt->execute()) {
                $message = "New meal added successfully";
            } else {
                $message = "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        }
    } else {
        // Handle case where no diary is found for the user
        $message = "Error: No diary found for the user";
    }

    // Close the connection 
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Meals</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
    body {
        background-image: url('images/bg_1.jpg'); /* Add the URL to your background image */
        background-size: cover; /* Ensure the background image covers the entire page */
        background-repeat: no-repeat; /* Prevent the image from repeating */
        background-attachment: fixed; /* Fix the background image in place */
        color: #000; /* Black text color */
    }

    .container {
        max-width: 400px; /* Adjust container width */
        margin: 100px auto; /* Center container vertically */
    }

    .card {
        border: none;
        border-radius: 20px;
        background-color: #6c757d; /* Grey card background color */
        color: #000; /* Black text color */
    }

    .card-header {
        background-color: #495057; /* Dark grey card header background color */
        color: #000; /* Black text color */
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        text-align: center;
    }

    .icon {
        padding: 10px;
    }

    .form-group {
        position: relative;
        margin-bottom: 20px; /* Add bottom margin to form groups */
    }

    .form-group label {
        color: #000; /* Black text color for form labels */
    }

    .form-group input,
    .form-group select {
        color: #000; /* Black text color for form inputs and selects */
        padding-left: 40px;
        border-radius: 10px; /* Add border radius to input */
    }

    .form-group .icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 10px;
        color: #495057; /* Dark grey icon color */
    }

    .bottom-text {
        text-align: center;
        margin-top: 20px;
        color: #6c757d; /* Grey text color */
    }

    .separated-text {
        margin-top: 20px;
        text-align: center;
    }
</style>

</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Add Your Meal</h2>
        <?php if (isset($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form id="meal-form" action="add_meals.php" method="POST">
            <div class="form-group">
                <label for="mealType">Meal Type</label>
                <select class="form-control" id="mealType" name="mealType" required>
                    <option value="">Select Meal Type</option>
                    <option value="breakfast">Breakfast</option>
                    <option value="lunch">Lunch</option>
                    <option value="dinner">Dinner</option>
                    <option value="snacks">Snacks</option>
                </select>
            </div>
            <div class="form-group">
                <label for="day">Date</label>
                <input type="date" class="form-control" id="day" name="date" onchange="displayDay()" required>
            </div>
            <div class="form-group">
                <label for="dayDisplay">Day</label>
                <input type="text" class="form-control" id="dayDisplay" readonly>
            </div>
            <div class="form-group">
                <label for="foodInput">Search Food</label>
                <input type="text" class="form-control" id="foodInput" placeholder="Enter food name">
                <button type="button" id="searchButton" class="btn btn-primary mt-2">Search</button>
            </div>
            <div id="resultsSection" class="mt-4 hidden">
                <div id="resultsContainer"></div>
            </div>
            <!-- <button type="submit" class="btn btn-success mt-4"></button> -->
            <a href="dashboard.php" class="btn btn-secondary mt-4 ml-2">Done</a>

            <!-- Hidden inputs to hold selected food data -->
            <input type="hidden" name="diary_id" value="4"> <!-- Replace with actual diary_id -->
            <div id="selected-foods"></div>
        </form>
    </div>

    <script>
        function displayDay() {
            const selectedDate = new Date(document.getElementById('day').value);
            const today = new Date();
            const diffTime = Math.abs(selectedDate - today);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            document.getElementById('dayDisplay').value = `Day ${diffDays}`;
        }

        function addFoodToSelected(food) {
            const selectedFoodsDiv = document.getElementById('selected-foods');
            const foodItem = document.createElement('div');
            foodItem.classList.add('selected-food', 'd-flex', 'justify-content-between', 'align-items-center', 'mt-2');
            foodItem.innerHTML = `
                <div>
                    <strong>${food.name}</strong><br>
                    Calories: ${food.calories} kcal<br>
                    Serving Size: ${food.serving_size}<br>
                    Protein: ${food.protein_g} g<br>
                    Carbohydrates: ${food.carbohydrates_total_g} g
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeFood(this)">Remove</button>
            `;
            selectedFoodsDiv.appendChild(foodItem);

            // Add hidden input fields for each food item
            const foodNameInput = document.createElement('input');
            foodNameInput.type = 'hidden';
            foodNameInput.name = 'foodName[]';
            foodNameInput.value = food.name;
            selectedFoodsDiv.appendChild(foodNameInput);

            const caloriesInput = document.createElement('input');
            caloriesInput.type = 'hidden';
            caloriesInput.name = 'calories[]';
            caloriesInput.value = food.calories;
            selectedFoodsDiv.appendChild(caloriesInput);

            const proteinInput = document.createElement('input');
            proteinInput.type = 'hidden';
            proteinInput.name = 'protein[]';
            proteinInput.value = food.protein_g;
            selectedFoodsDiv.appendChild(proteinInput);

            const carbohydratesInput = document.createElement('input');
            carbohydratesInput.type = 'hidden';
            carbohydratesInput.name = 'carbohydrates[]';
            carbohydratesInput.value = food.carbohydrates_total_g;
            selectedFoodsDiv.appendChild(carbohydratesInput);
        }

        window.removeFood = function(button) {
            const foodItem = button.parentElement;
            foodItem.remove();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const searchButton = document.getElementById("searchButton");
            const foodInput = document.getElementById("foodInput");
            const resultsSection = document.getElementById("resultsSection");
            const resultsContainer = document.getElementById("resultsContainer");

            searchButton.addEventListener("click", function() {
                const foodName = foodInput.value.trim();
                if (foodName !== "") {
                    searchFoodNutrition(foodName);
                } else {
                    alert("Please enter a food name.");
                }
            });

            function searchFoodNutrition(foodName) {
                const apiKey = "your_api_key";
                const apiUrl = `https://api.api-ninjas.com/v1/nutrition?query=${encodeURIComponent(foodName)}`;

                fetch(apiUrl, {
                    headers: {
                        "X-Api-Key": apiKey
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok.");
                    }
                    return response.json();
                })
                .then(data => {
                    displayResults(data);
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    alert("An error occurred while fetching data. Please try again later.");
                });
            }

            function displayResults(data) {
                resultsContainer.innerHTML = "";

                if (data.length === 0) {
                    resultsContainer.innerHTML = "<p>No results found.</p>";
                    resultsSection.classList.remove("hidden");
                    return;
                }

                data.forEach(food => {
                    const card = document.createElement("div");
                    card.classList.add("card", "mt-2");

                    const cardBody = document.createElement("div");
                    cardBody.classList.add("card-body", "d-flex", "justify-content-between", "align-items-center");

                    const foodDetails = document.createElement("div");
                    foodDetails.innerHTML = `
                        <strong>${food.name}</strong><br>
                        Calories: ${food.calories} kcal<br>
                        Serving Size: ${food.serving_size}<br>
                        Protein: ${food.protein_g} g<br>
                        Carbohydrates: ${food.carbohydrates_total_g} g
                    `;

                    const addButton = document.createElement("button");
                    addButton.classList.add("btn", "btn-primary", "btn-sm");
                    addButton.textContent = "Select";
                    addButton.onclick = function() {
                        addFoodToSelected(food);
                    };

                    cardBody.appendChild(foodDetails);
                    cardBody.appendChild(addButton);
                    card.appendChild(cardBody);
                    resultsContainer.appendChild(card);
                });

                resultsSection.classList.remove("hidden");
            }
        });
    </script>
</body>
</html>
