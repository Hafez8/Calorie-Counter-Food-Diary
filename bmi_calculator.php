
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

      <li class="nav-item dropdown">

<a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
  <i class="bi bi-bell"></i>
  <span class="badge bg-primary badge-number">4</span>
</a><!-- End Notification Icon -->

<ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
  <li class="dropdown-header">
    You have 4 new notifications
    <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
  </li>

</ul><!-- End Notification Dropdown Items -->

</li><!-- End Notification Nav -->

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
        <a class="nav-link collapsed" href="pages-loginform.php">
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
  </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row justify-content-center"> <!-- Centering content -->
          <div class="col-lg-8">
            <div class="card bg-light">
              <div class="card-body">
                <h2 class="card-title text-center mb-4">Let's calculate your BMI now!</h2>
                <form id="bmiCalculatorForm">
                  <div class="mb-3">
                    <label for="ageInput" class="form-label">Age</label>
                    <input type="number" class="form-control" id="ageInput" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <div>
                      <input type="radio" id="male" name="gender" value="male" required>
                      <label for="male">Male</label>
                    </div>
                    <div>
                      <input type="radio" id="female" name="gender" value="female" required>
                      <label for="female">Female</label>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label for="heightInput" class="form-label">Height (cm)</label>
                    <input type="number" class="form-control" id="heightInput" required>
                  </div>
                  <div class="mb-3">
                    <label for="weightInput" class="form-label">Weight (kg)</label>
                    <input type="number" class="form-control" id="weightInput" required>
                  </div>
                  <button type="button" class="btn btn-success btn-block rounded-pill" onclick="calculateBMI()">Calculate</button>
                </form>
                <div id="result" class="mt-4"></div>
              </div>
            </div>
          </div>
        </div>

        <script>
            function calculateBMI() {
              var age = parseInt(document.getElementById('ageInput').value);
              var gender = document.querySelector('input[name="gender"]:checked').value;
              var height = parseInt(document.getElementById('heightInput').value) / 100; // converting to meters
              var weight = parseInt(document.getElementById('weightInput').value);
              
              var bmi = weight / (height * height);
              var comment = "";
          
              if (bmi < 18.5) {
                comment = "Underweight";
              } else if (bmi >= 18.5 && bmi < 24.9) {
                comment = "Normal weight";
              } else if (bmi >= 25 && bmi < 29.9) {
                comment = "Overweight";
              } else {
                comment = "Obese";
              }
          
              var resultElement = document.getElementById('result');
              resultElement.innerHTML = "<p>Your BMI: " + bmi.toFixed(2) + "</p><p>Comment: " + comment + "</p>";
            }
          </script>
          
      </section>
      

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    
  </footer><!-- End Footer -->

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

</body>

</html>