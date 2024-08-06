<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Assign the username to a variable
$username = $_SESSION["username"];

include "db_conn.php";

// Count total scholarships with status 'post'
$totalQuery = "SELECT COUNT(*) AS total FROM scholarship WHERE status = 'post'";
$totalResult = $conn->query($totalQuery);

$totalScholarships = 0;
if ($totalResult->num_rows > 0) {
    $totalRow = $totalResult->fetch_assoc();
    $totalScholarships = $totalRow["total"];
}

// Count total scholarships with status 'draft'
$totalDraftsQuery = "SELECT COUNT(*) AS total FROM scholarship WHERE status = 'draft'";
$totalDraftsResult = $conn->query($totalDraftsQuery);

$totalDrafts = 0;
if ($totalDraftsResult->num_rows > 0) {
    $totalDraftsRow = $totalDraftsResult->fetch_assoc();
    $totalDrafts = $totalDraftsRow["total"];
}

// Count total scholarships with status 'archive'
$totalArchiveQuery = "SELECT COUNT(*) AS total FROM scholarship WHERE status = 'archive'";
$totalArchiveResult = $conn->query($totalArchiveQuery);

$totalArchive = 0;
if ($totalArchiveResult->num_rows > 0) {
    $totalArchiveRow = $totalArchiveResult->fetch_assoc();
    $totalArchive = $totalArchiveRow["total"];
}

// Count scholarships by type with status 'post'
$typeTotals = array();
$typeQuery = "SELECT type, COUNT(*) AS total FROM scholarship WHERE status = 'post' GROUP BY type";
$typeResult = $conn->query($typeQuery);

if ($typeResult->num_rows > 0) {
    while ($row = $typeResult->fetch_assoc()) {
        $typeTotals[$row["type"]] = $row["total"];
    }
}

// Count scholarships by grade level with status 'post'
$gradeLevelTotals = array();
$gradeLevelQuery = "SELECT grade_level, COUNT(*) AS total FROM scholarship WHERE status = 'post' GROUP BY grade_level";
$gradeLevelResult = $conn->query($gradeLevelQuery);

if ($gradeLevelResult->num_rows > 0) {
    while ($row = $gradeLevelResult->fetch_assoc()) {
        $gradeLevelTotals[$row["grade_level"]] = $row["total"];
    }
}

// Count scholarships by benefits with status 'post'
$benefitsTotals = array();
$benefitsQuery = "SELECT benefits, COUNT(*) AS total FROM scholarship WHERE status = 'post' GROUP BY benefits";
$benefitsResult = $conn->query($benefitsQuery);

if ($benefitsResult->num_rows > 0) {
    while ($row = $benefitsResult->fetch_assoc()) {
        $benefitsTotals[$row["benefits"]] = $row["total"];
    }
}
 
// Close connection
$conn->close();

// Column chart template for scholarships by type
$typeDataPoints = array();
foreach ($typeTotals as $type => $count) {
    $typeDataPoints[] = array("y" => $count, "label" => $type);
}

// Column chart template for scholarships by grade level
$gradeLevelDataPoints = array();
foreach ($gradeLevelTotals as $gradeLevel => $count) {
    $gradeLevelDataPoints[] = array("y" => $count, "label" => $gradeLevel);
}

// Column chart template for scholarships by benefits
$benefitsDataPoints = array();
foreach ($benefitsTotals as $benefits => $count) {
    $benefitsDataPoints[] = array("y" => $count, "label" => $benefits);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>FSUU Scholarships Portal - Admin</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <link href="card.css" rel="stylesheet">

  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

  <style>
  .button-container {
    margin-right: 110px; /* Adjust the margin as needed */
    text-align: right;
  }

  .navbar {
    position: relative;
}

.notification-badge {
    position: absolute;
    top: -2%;
    transform: translateY(-50%);
    right: -10px; /* You can adjust this value according to your design */
    background-color: #ff0000;
    color: #fff;
    padding: 5px 10px;
    border-radius: 50%;
    font-size: 12px;
}

  </style>

<!-- Add this script in the head section of your HTML -->
<script>
function redirectToFilterTrack() {
    // Calculate one month ago from the current date
    var startDate = new Date();
    startDate.setMonth(startDate.getMonth() - 1);

    // Format the start and end dates as 'YYYY-MM-DD'
    var formattedStartDate = startDate.toISOString().split('T')[0];
    var formattedEndDate = new Date().toISOString().split('T')[0];

    // Construct the URL with start and end date parameters
    var url = 'filter_track.php?start_date=' + formattedStartDate + '&end_date=' + formattedEndDate;

    // Open the URL in a new tab
    window.open(url, '_blank');
}

function redirectToScholarshipTrack() {
    // Calculate one month ago from the current date
    var startDate = new Date();
    startDate.setMonth(startDate.getMonth() - 1);

    // Format the start and end dates as 'YYYY-MM-DD'
    var formattedStartDate = startDate.toISOString().split('T')[0];
    var formattedEndDate = new Date().toISOString().split('T')[0];

    // Construct the URL with start and end date parameters
    var url = 'scholarship_track.php?start_date=' + formattedStartDate + '&end_date=' + formattedEndDate;

    // Open the URL in a new tab
    window.open(url, '_blank');
}
</script>

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center header-transparent" style="background-color: rgba(2, 5, 161, 0.91);">
    <div class="container d-flex align-items-center justify-content-between">

    <div class="logo">
        <h1><a href="index.html"><img src="assets/img/logo.png" alt="FSUU Logo"> <span>FSUU Scholarships Portal</span></a></h1>
    </div>

    <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto active" href="admin2.php">Reports</a></li>
                <li><a class="nav-link scrollto" href="post2.php">Post</a>
                    <?php if ($totalScholarships > 0): ?>
                        <span class="notification-badge"><?= $totalScholarships ?></span>
                    <?php endif; ?>
                </li>
                <li><a class="nav-link scrollto" href="draft2.php">Drafts</a>
                    <?php if ($totalDrafts > 0): ?>
                        <span class="notification-badge"><?= $totalDrafts ?></span>
                    <?php endif; ?>
                </li>
                <li><a class="nav-link scrollto" href="archive.php">Archive</a>
                    <?php if ($totalArchive > 0): ?>
                        <span class="notification-badge"><?= $totalArchive ?></span>
                    <?php endif; ?>
                </li>
                <li><a class="nav-link scrollto" href="logout2.php">Logout</a></li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

    </div>
  </header><!-- End Header --> 

  <main id="main"> <br> <br>

<!-- Add the 'onclick' attribute to your button elements -->
<div class="button-container">
    <button class="btn btn-primary" onclick="redirectToFilterTrack()">Filter Track</button>
    <button class="btn btn-primary" onclick="redirectToScholarshipTrack()">Scholarship Track</button>
</div>

<!-- ======= Customizable Cards Section ======= -->

<section id="custom-cards" class="custom-cards">

  <div class="container">
    
    <div class="row mb-5">

      <div class="col-lg-3 col-md-6">
        <div class="card" id="card1">
          <div class="card-body">
            <h5 class="card-title">Scholarships</h5>
            <p class="card-text">Total: <span class="custom-number"><?= $totalScholarships ?></span></p>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card" id="card2">
          <div class="card-body">
            <h5 class="card-title">FSUU Funded Scholarships</h5>
            <p class="card-text">Total: <span class="custom-number"><?= isset($typeTotals['FSUU Funded Scholarships']) ? $typeTotals['FSUU Funded Scholarships'] : 0 ?></span></p>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card" id="card3">
          <div class="card-body">
            <h5 class="card-title">Government Funded Scholarships</h5>
            <p class="card-text">Total: <span class="custom-number"><?= isset($typeTotals['Government Funded Scholarships']) ? $typeTotals['Government Funded Scholarships'] : 0 ?></span></p>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card" id="card4">
          <div class="card-body">
            <h5 class="card-title">Private Funded Scholarships</h5>
            <p class="card-text">Total: <span class="custom-number"><?= isset($typeTotals['Private Funded Scholarships']) ? $typeTotals['Private Funded Scholarships'] : 0 ?></span></p>
          </div>
        </div>  
      </div>

    </div>

    <div class="row mb-5">

      <div class="col-lg-3 col-md-6">
        <div class="card" id="card5">
          <div class="card-body">
            <h5 class="card-title">Civic/Religious Funded Scholarships</h5>
            <p class="card-text">Total: <span class="custom-number"><?= isset($typeTotals['Civic/Religious Funded Scholarships']) ? $typeTotals['Civic/Religious Funded Scholarships'] : 0 ?></span></p>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card" id="card6">
          <div class="card-body">
            <h5 class="card-title">Basic Education</h5>
            <p class="card-text">Total: <span class="custom-number"><?= isset($gradeLevelTotals['Basic Education']) ? $gradeLevelTotals['Basic Education'] : 0 ?></span></p>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card" id="card7">
          <div class="card-body">
            <h5 class="card-title">College</h5>
            <p class="card-text">Total: <span class="custom-number"><?= isset($gradeLevelTotals['College']) ? $gradeLevelTotals['College'] : 0 ?></span></p>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="card" id="card8">
          <div class="card-body">
            <h5 class="card-title">Graduate Studies</h5> 
            <p class="card-text">Total: <span class="custom-number"><?= isset($gradeLevelTotals['Graduate Studies']) ? $gradeLevelTotals['Graduate Studies'] : 0 ?></span></p>
          </div>
        </div>
      </div>
      

    </div>

    <div class="chart-wrapper">
  <div id="typeChartContainer" style="height: 370px; width: 100%;"></div>
  </div> <br>
  <div class="chart-wrapper">
  <div id="gradeLevelChartContainer" style="height: 370px; width: 100%;"></div>
  </div> <br>
  <div class="chart-wrapper">
  <div id="benefitsChartContainer" style="height: 370px; width: 100%;"></div>
  </div>
  </div>

</section>


  <!-- ======= About Section ======= -->
  <section id="about" class="about">

  <script>
window.onload = function() {
    // Chart for scholarships by type
    var typeChart = new CanvasJS.Chart("typeChartContainer", {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: "Scholarships by Category"
        },
        axisY: {
            title: ""
        },
        data: [{
            type: "column",
            yValueFormatString: "#",
            dataPoints: <?php echo json_encode($typeDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    typeChart.render();

    // Chart for scholarships by grade level
    var gradeLevelChart = new CanvasJS.Chart("gradeLevelChartContainer", {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: "Scholarships by Grade Level"
        },
        axisY: {
            title: ""
        },
        data: [{
            type: "column",
            yValueFormatString: "#",
            dataPoints: <?php echo json_encode($gradeLevelDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    gradeLevelChart.render();

    // Chart for scholarships by benefits
    var benefitsChart = new CanvasJS.Chart("benefitsChartContainer", {
        animationEnabled: true,
        theme: "light2",
        title: {
            text: "Scholarships by Benefits"
        },
        axisY: {
            title: ""
        },
        data: [{
            type: "column",
            yValueFormatString: "#",
            dataPoints: <?php echo json_encode($benefitsDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    benefitsChart.render();
}
</script>
      

    </section><!-- End About Section -->             

  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>