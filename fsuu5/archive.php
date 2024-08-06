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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the Post button is clicked
    if (isset($_POST['post_button'])) {
        $scholarship_id = $_POST['id'];

        // Update the status to 'post'
        $sql_update_status = "UPDATE scholarship SET status = 'Post' WHERE id = $scholarship_id";
        if ($conn->query($sql_update_status) === TRUE) {
            // Status updated successfully
            header("Location: archive.php");
            exit();
        } else {
            // Error updating status
            echo "Error updating status: " . $conn->error;
        }
    }
}

// The rest of your HTML and PHP code follows...

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>FSUU Scholarships Portal</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <link rel="stylesheet" href="table1.css">

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

  <link rel="stylesheet" type="text/css" href="sharingbuttons.css"/>

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- DataTables JavaScript -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

<style>

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

</head>

<body class="bg-light">

  <!-- ======= Header ======= -->
  <header id="header" class="d-flex align-items-center header-transparent" style="background-color: rgba(2, 5, 161, 0.91);">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="logo">
        <h1><a href="index.html"><img src="assets/img/logo.png" alt="FSUU Logo"> <span>FSUU Scholarships Portal</span></a></h1>
      </div>

      <nav id="navbar" class="navbar">
            <ul>
                <li><a class="nav-link scrollto" href="admin2.php">Reports</a></li>
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
                <li><a class="nav-link scrollto active" href="archive.php">Archive</a>
                    <?php if ($totalArchive > 0): ?>
                        <span class="notification-badge"><?= $totalArchive ?></span>
                    <?php endif; ?>
                </li>
                <li><a class="nav-link scrollto" href="logout2.php">Logout</a></li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->

    </div>
  </header><!-- End Header --> <br>

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">


    <div class="container mt-5">
    <?php
    include "db_conn.php";

    // Sharing buttons powered by https://sharingbuttons.io/
    include("sharingbuttons.php");

    // Display 'Post' scholarships
    $sql_archive = "SELECT * FROM scholarship WHERE status = 'Archive' ORDER BY id DESC";
    $result_archive = $conn->query($sql_archive);

    echo "<div class='d-flex flex-column flex-md-row align-items-center mb-3'>    <!-- Center the filters and reset button -->
    <div class='d-flex me-2'>
        <label for='typeFilter' class='me-2'>Category:</label>
        <select id='typeFilter' class='form-select'></select>
    </div>
    <div class='d-flex me-2'>
        <label for='gradeLevelFilter' class='me-2'>Grade Level:</label>
        <select id='gradeLevelFilter' class='form-select'></select>
    </div>
    <div class='d-flex me-2'>
        <label for='benefitsFilter' class='me-2'>Benefits:</label>
        <select id='benefitsFilter' class='form-select'></select>
    </div>
    <div class='d-flex me-2'>
        <button type='button' id='resetFilters' class='btn btn-secondary'>Reset</button>
    </div>
</div><br>";

echo "<div class='table-container'>";
echo "<table id='scholarshipTable' class='table table-bordered'>
        <thead>
            <tr>
                <th style='display:none;'></th>
                <th>Categories</th>
                <th>Provider</th>
                <th>Scholarship</th>
                <th>Grade Level</th>
                <th>Benefits</th>
                <th>Start Date (YYYY/MM/DD)</th>
                <th>End Date (YYYY/MM/DD)</th>
                <th>Filte Attachment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>";

while ($row_archive = $result_archive->fetch_assoc()) {
    echo "<tr>
            <td style='display:none;'>{$row_archive['post_date']}</td>
            <td>{$row_archive['type']}</td>
            <td>{$row_archive['provider']}</td>
            <td>{$row_archive['scholarship']}</td>
            <td>{$row_archive['grade_level']}</td>
            <td>{$row_archive['benefits']}</td>
            <td style='text-align: center;'>" . date('Y-m-d', strtotime($row_archive['start_date'])) . "</td>
            <td style='text-align: center;'>" . date('Y-m-d', strtotime($row_archive['end_date'])) . "</td>
            <td class='text-center'><a href='{$row_archive['file_attachment']}' target='_blank' class='btn btn-primary'>Details</a></td>
            <td class='text-center'>
            <form method='post' style='display:inline;'>
                <input type='hidden' name='id' value='" . $row_archive['id'] . "'>
                <button type='submit' name='post_button' class='btn btn-outline-primary'>Post</button>
            </form> <br> <br>
            
            <form action='edit_archive.php' method='get' style='display:inline; margin-left: 5px;'>
                <input type='hidden' name='id' value='" . $row_archive['id'] . "'>
                <button type='submit' class='btn btn-outline-warning'>Edit</button>
            </form> <br> <br>
            
            <form action='delete_archive.php' method='get' style='display:inline; margin-left: 5px;'>
                <input type='hidden' name='id' value='" . $row_archive['id'] . "'>
                <button type='submit' class='btn btn-outline-danger'>Delete</button>
            </form>
        </td>        
        </tr>";
}

echo "</tbody></table>";
echo "</div>"; // Close the table-container div


    echo "<script>
        $(document).ready(function () {
            var table = $('#scholarshipTable').DataTable({
                'order': [[0, 'desc']]
            });

            // Add type filter
            $('#typeFilter').on('change', function () {
                table.column(1).search($(this).val()).draw();
            });

            // Add grade level filter
            $('#gradeLevelFilter').on('change', function () {
                table.column(4).search($(this).val()).draw();
            });

            // Add benefits filter
            $('#benefitsFilter').on('change', function () {
                table.column(5).search($(this).val()).draw();
            });

            // Add reset button
            $('#resetFilters').on('click', function () {
                $('#typeFilter, #gradeLevelFilter, #benefitsFilter').val('').change();
            });

            // Populate dropdowns for filters
            $.each(['', 'FSUU Funded Scholarships', 'Government Funded Scholarships', 'Private Funded Scholarships', 'Civic/Religious Funded Scholarships'], function (i, value) {
                $('#typeFilter').append('<option value=\"' + value + '\">' + value + '</option>');
            });

            $.each(['', 'Basic Education', 'College', 'Graduate Studies'], function (i, value) {
                $('#gradeLevelFilter').append('<option value=\"' + value + '\">' + value + '</option>');
            });

            jQuery.each(['', 
                'FULL COVERAGE SCHOLARSHIPS:',
                'Full tuition, fees, allowance per semester', 
                'Full tuition and fees only per semester', 
                'Full tuition only per semester', 
                'PARTIAL TUITION SCHOLARSHIPS:',
                'Php 60,000 above per semester',
                'Php 30,000 - Php 60,000 per semester', 
                'Php 10,000 - Php 29,999 per semester', 
                'Php 4,000 - Php 9,999 per semester',
                'TUITION DISCOUNTS:', 
                '15 to 24 units covered per semester', 
                'ALLOWANCE-ONLY SCHOLARSHIPS:',
                'Living, uniform, book, or transportation allowances', 
                'FEES-ONLY SCHOLARSHIPS:',
                'Covers miscellaneous fees only'
            ], function (i, value) {
                var option = jQuery('<option value=\"' + value + '\">' + value + '</option>');

                // Disable 'Full tuition, fees, allowance per semester' option
                if (value === 'FULL COVERAGE SCHOLARSHIPS:') {
                    option.prop('disabled', true);
                }
                
                // Disable 'Full tuition, fees, allowance per semester' option
                if (value === 'PARTIAL TUITION SCHOLARSHIPS:') {
                    option.prop('disabled', true);
                }

                // Disable 'Full tuition, fees, allowance per semester' option
                if (value === 'TUITION DISCOUNTS:') {
                    option.prop('disabled', true);
                }

                // Disable 'Full tuition, fees, allowance per semester' option
                if (value === 'ALLOWANCE-ONLY SCHOLARSHIPS:') {
                    option.prop('disabled', true);
                }

                // Disable 'Full tuition, fees, allowance per semester' option
                if (value === 'FEES-ONLY SCHOLARSHIPS:') {
                    option.prop('disabled', true);
                }

                jQuery('#benefitsFilter').append(option);
            });

        });
    </script>";

    $conn->close();
    ?> <br>

</div>
      
</section><!-- End About Section -->                

</main><!-- End #main --> <br> <br> <br>

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