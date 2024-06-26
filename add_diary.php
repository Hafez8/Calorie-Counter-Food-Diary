<?php
session_start(); // Start the session

// Check if the user's name is passed as a parameter in the URL
if (isset($_GET['name'])) {
    $name = $_GET['name'];
} elseif (isset($_SESSION['userName'])) {
    // If user's name is not in URL, check if it's stored in a session variable
    $name = $_SESSION['userName'];
} else {
    // Default name if not available
    $name = 'User';
}
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
        <a class="nav-link" href="calorie_search.html">
          <i class="bi bi-menu-button-wide"></i><span>Calories</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
      </li><!-- End Components Nav -->
      
      <li class="nav-item">
        <a class="nav-link" href="bmi_calculator.html">
          <i class="bi bi-journal-text"></i><span>BMI Calculator</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
      </li>
      <!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-contact.html">
          <i class="bi bi-envelope"></i>
          <span>Feedback</span>
        </a>
      </li><!-- End Contact Page Nav -->

      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
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

  <section class="section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <!-- Diary Form Card -->
        <div class="card bg-light p-4">
          <div class="card-body">
            <form method="POST" action="handle_form.php">
              <!-- First Row: Diary Name -->
              <div class="form-group row mb-3">
                <label for="diaryName" class="col-sm-3 col-form-label">Diary Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="diaryName" name="diary_name" placeholder="Enter Diary Name">
                </div>
              </div>
              <!-- Second Row: Current Weight and Target Weight -->
              <div class="form-group row mb-3">
                <label for="currentWeight" class="col-sm-3 col-form-label">Current Weight</label>
                <div class="col-sm-4">
                  <input type="number" class="form-control" id="currentWeight" name="current_weight" placeholder="Current Weight">
                </div>
                <label for="targetWeight" class="col-sm-2 col-form-label">Target Weight</label>
                <div class="col-sm-3">
                  <input type="number" class="form-control" id="targetWeight" name="target_weight" placeholder="Target Weight">
                </div>
              </div>
              <!-- Third Row: Height and Gender -->
              <div class="form-group row mb-3">
                <label for="height" class="col-sm-3 col-form-label">Height</label>
                <div class="col-sm-4">
                  <input type="number" class="form-control" id="height" name="height" placeholder="Height">
                </div>
                <div class="col-sm-5">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male">
                    <label class="form-check-label" for="male">Male</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                    <label class="form-check-label" for="female">Female</label>
                  </div>
                </div>
              </div>
              <!-- Fourth Row: Start Date and Upload Image -->
              <div class="form-group row mb-3">
                <label for="startDate" class="col-sm-3 col-form-label">Start Date</label>
                <div class="col-sm-4">
                  <input type="date" class="form-control" id="startDate" name="start_date">
                </div>
                <label for="imageUpload" class="col-sm-2 col-form-label">Upload Image</label>
                <div class="col-sm-3">
                  <input type="file" class="form-control-file" id="imageUpload" name="image_upload">
                </div>
              </div>
              <!-- Fifth Row: Self Quote -->
              <div class="form-group row mb-3">
                <label for="selfQuote" class="col-sm-3 col-form-label">Self Quote</label>
                <div class="col-sm-9">
                  <textarea class="form-control" id="selfQuote" name="self_quote" rows="3" placeholder="Enter your self quote"></textarea>
                </div>
              </div>
              <!-- Submit Button -->
              <div class="form-group row">
                <div class="col-sm-12 text-center">
                  <button type="submit" class="btn btn-success">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>





  </main>
  <!-- End #main -->

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