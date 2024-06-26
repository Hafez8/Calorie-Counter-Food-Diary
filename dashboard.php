<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    // Redirect to login page if not logged in
    header('Location: loginform.php');
    exit();
}

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

// Get the user ID from the session
$userId = $_SESSION['userId'];

// Fetch the user's diary entries
$sql = "SELECT * FROM diary WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

$name = isset($_SESSION['userName']) ? $_SESSION['userName'] : 'User';
$startDate = isset($_SESSION['start_date']) ? $_SESSION['start_date'] : 'Day';

// Close the statement and connection
$stmt->close();

// Function to summarize meals for a specific day
function summarizeMeals($con, $userId, $day) {
    // Fetch meals for the specified day
    $sql = "SELECT * FROM meals WHERE user_id = ? AND date = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('is', $userId, $day);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize variables for total calories, proteins, and carbohydrates
    $totalCalories = 0;
    $totalProteins = 0;
    $totalCarbohydrates = 0;

    // Loop through the meals and calculate totals
    while ($row = $result->fetch_assoc()) {
        $totalCalories += $row['calories'];
        $totalProteins += $row['protein'];
        $totalCarbohydrates += $row['carbohydrates'];
    }

    // Close the statement
    $stmt->close();

    // Return the summarized data
    return array(
        'totalCalories' => $totalCalories,
        'totalProteins' => $totalProteins,
        'totalCarbohydrates' => $totalCarbohydrates
    );
}

// Check if a day was selected for summarization
if (isset($_GET['diary_day'])) {
    // Get the selected day from the URL parameter
    $selectedDay = $_GET['diary_day'];

    // Summarize meals for the selected day
    $summary = summarizeMeals($con, $userId, $selectedDay);
}

// Close the connection
$con->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard - NiceAdmin Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="images/favicon.png" rel="icon">
  <link href="images/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="css/dashboard.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin - v2.4.1
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <span class="d-none d-lg-block">CALORIE COUNTER: FOOD DIARY</span>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <!-- <li class="nav-item dropdown">

          <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="badge bg-primary badge-number">4</span>
          </a>End Notification Icon -->

          <!-- <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
            <li class="dropdown-header">
              You have 4 new notifications
              <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
            </li> -->

          <!-- </ul>End Notification Dropdown Items -->

        <!-- </li>End Notification Nav -->

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="images/person_logo.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo $name; ?></span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $name; ?></h6>
              
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.php">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="index.html">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link " href="dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link" href="calorie_search.php">
          <i class="bi bi-menu-button-wide"></i><span>Calories</span>
        </a>
      </li><!-- End Components Nav -->
      
      <li class="nav-item">
        <a class="nav-link" href="bmi_calculator.php">
          <i class="bi bi-journal-text"></i><span>BMI Calculator</span>
        </a>
      </li>
      <!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-contact.php">
          <i class="bi bi-envelope"></i>
          <span>Feedback</span>
        </a>
      </li><!-- End Contact Page Nav -->

      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.php">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li><!-- End Profile Page Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="loginform.php">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Sign Out</span>
        </a>
      </li><!-- End Login Page Nav -->
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
          <ol class="breadcrumb">
              <li class="breadcrumb-item active">WELCOME <?php echo $name; ?>! HAVE A NICE DAY.</li>
          </ol>
      </nav>
  </div>
  <!-- End Page Title -->

  <section class="section dashboard">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <?php if ($result->num_rows == 0) { ?>
                    <div class="card bg-light">
                        <div class="card-body text-center">
                            <h4 class="text-dark">Oops!</h4>
                            <p class="text-dark">Seems like you don't have any diary yet. Would you like to add your first diary?</p>
                            <a href="add_diary.php" class="btn btn-success">Add Diary</a>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                        <div class="diary-entry">
                                            <h4><?php echo htmlspecialchars($row['diary_name']); ?></h4>
                                            <p>Your Diary ID: <?php echo htmlspecialchars($row['diary_id']); ?></p>
                                            <p>Current Weight: <?php echo htmlspecialchars($row['current_weight']); ?> kg</p>
                                            <p>Target Weight: <?php echo htmlspecialchars($row['target_weight']); ?> kg</p>
                                            <p>Height: <?php echo htmlspecialchars($row['height']); ?> cm</p>
                                            <p>Gender: <?php echo htmlspecialchars($row['gender']); ?></p>
                                            <p>Start Date: <?php echo htmlspecialchars($row['start_date']); ?></p>
                                            <p>Self Quote: <?php echo htmlspecialchars($row['self_quote']); ?></p>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                              <div class="card bg-light">
                                  <div class="card-body">
                                      <h4 class="text-dark">Log Your Meals</h4>
                                      <p>Keep track of your daily meals by logging them here.</p>
                                      <button type="button" class="btn btn-primary" onclick="location.href='add_meals.php'">Log Now!</button>
                                  </div>
                              </div>
                          </div>
                    </div>
                    <div class="col-md-6">
    <div class="card bg-light">
        <div class="card-body">
            <h4 class="text-dark">Summarize Your Meals Now!</h4>
            <p>Let's summarize your diary per day!</p>
            <form action="summarize_meals.php" method="GET"> <!-- Assuming you'll use GET method to pass selected day -->
            <a href="summarize.php" class="btn btn-success">Summarize Now!</a>
            </form>
        </div>
    </div>
</div>

                <?php } ?>
            </div>
        </div>
    </div>
</section>


<!-- Meal Modal -->
<div class="modal fade" id="mealModal" tabindex="-1" role="dialog" aria-labelledby="mealModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mealModalLabel">Add Food to <span id="mealType"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="mealTypeHidden">
        <div class="form-group">
          <label for="foodInput">Search Food</label>
          <input type="text" class="form-control" id="foodInput" placeholder="Enter food name">
          <button type="button" class="btn btn-primary mt-2" id="searchButton">Search</button>
        </div>
        <div id="resultsContainer"></div>
        <div id="selectedFoodsContainer" class="mt-3">
          <h5>Selected Foods</h5>
          <ul id="selectedFoodsList"></ul>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="addFoodButton">Add Food</button>
      </div>
    </div>
  </div>
</div>


<script> src="js/search2.js"</script>

<script>
  function openMealModal(mealType) {
    document.getElementById('mealType').textContent = mealType;
    document.getElementById('mealTypeHidden').value = mealType;
    $('#mealModal').modal('show');
}

document.getElementById('addFoodButton').addEventListener('click', function() {
    const mealType = document.getElementById('mealTypeHidden').value;
    const foodInput = document.getElementById('foodInput').value;

    if (foodInput.trim() !== '') {
        fetch('add_meals.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                mealType: mealType,
                food: foodInput,
                diaryId: <?php echo $row['diary_id']; ?> 
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Food added successfully!');
                $('#mealModal').modal('hide');
            } else {
                alert('Failed to add food. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    } else {
        alert('Please enter a food name.');
    }
});


  function summarizeDay(dayIndex) {
    alert('Summarize Day ' + (dayIndex + 1));
    // Add logic to summarize the meals for the day and calculate total calories
  }
</script>

<!-- Include Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    
  </footer>
  <!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="vendor/apexcharts/apexcharts.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/chart.js/chart.min.js"></script>
  <script src="vendor/echarts/echarts.min.js"></script>
  <script src="vendor/quill/quill.min.js"></script>
  <script src="vendor/simple-datatables/simple-datatables.js"></script>
  <script src="vendor/tinymce/tinymce.min.js"></script>
  <script src="vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="js/dashboard.js"></script>
  <script src="js/search2.js"></script>

</body>

</html>