<?php
include "db_conn.php";

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Insert a record into the page_view table
$sql = "INSERT INTO page_view (view, date) VALUES ('fsuu3/index.php', '$currentDateTime')";
$conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>FSUU Scholarships Portal</title>
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

  <link rel="stylesheet" href="table.css">

  <link rel="stylesheet" type="text/css" href="sharingbuttons.css"/>

  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <!-- DataTables JavaScript -->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>

  <!-- Add this line to link your custom CSS file -->
  <link rel="stylesheet" type="text/css" href="details.css">

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
          <li><a class="nav-link scrollto active" href="#about">Search</a></li>
        </ul>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero">

    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-7 pt-5 pt-lg-0 order-2 order-lg-1 d-flex align-items-center">
          <div data-aos="zoom-out">
            <h1>Scholarship Search Made Easy With FSUU Funded Scholarships Portal</h1>
            <h2>Simplify your scholarship search in FSUU, making it easier for you to find the financial support you need for your education.</h2>
            <div class="text-center text-lg-start">
              <a href="#about" class="btn-get-started scrollto">Get Started</a>
            </div>
          </div>
        </div>
        <div class="col-lg-4 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="300">
          <img src="assets/img/studying.png" class="img-fluid animated" alt="">
        </div>
      </div>
    </div>

    <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28 " preserveAspectRatio="none">
      <defs>
        <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z">
      </defs>
      <g class="wave1">
        <use xlink:href="#wave-path" x="50" y="3" fill="rgba(255,255,255, .1)">
      </g>
      <g class="wave2">
        <use xlink:href="#wave-path" x="50" y="0" fill="rgba(255,255,255, .2)">
      </g>
      <g class="wave3">
        <use xlink:href="#wave-path" x="50" y="9" fill="#fff">
      </g>
    </svg>

  </section><!-- End Hero --> <br>

  <main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">


    <div class="container mt-5">
    <?php
    include "db_conn.php";

    // Sharing buttons powered by https://sharingbuttons.io/
    include("sharingbuttons.php");

    // Display 'Post' scholarships
    $sql_post = "SELECT * FROM scholarship WHERE status = 'Post' ORDER BY id DESC";
    $result_post = $conn->query($sql_post);

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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>";

        while ($row_post = $result_post->fetch_assoc()) {
            echo "<tr>
                    <td style='display:none;'>{$row_post['post_date']}</td>
                    <td>{$row_post['type']}</td>
                    <td>{$row_post['provider']}</td>
                    <td>{$row_post['scholarship']}</td>
                    <td>{$row_post['grade_level']}</td>
                    <td>{$row_post['benefits']}</td>
                    <td style='text-align: center;'>" . date('Y-m-d', strtotime($row_post['start_date'])) . "</td>
                    <td style='text-align: center;'>" . date('Y-m-d', strtotime($row_post['end_date'])) . "</td>
                    <td><a href='{$row_post['file_attachment']}' target='_blank'><button class='details-button'>Details</button></a></td>
                </tr>";
        }
        
        echo "</tbody></table>";
        
        echo "<script>
        $(document).ready(function () {
            var table = $('#scholarshipTable').DataTable({
                'order': [[0, 'desc']]
            });
        
            // Function to record filter clicks
            function recordFilterClick(filterType, filterValue) {
                $.ajax({
                    type: 'POST',
                    url: 'record_click.php', // Replace with the actual PHP file to handle the database update
                    data: {
                        filterType: filterType,
                        filterValue: filterValue
                    },
                    success: function (response) {
                        console.log('Filter click recorded successfully');
                    },
                    error: function (error) {
                        console.error('Error recording filter click', error);
                    }
                });
            }
        
            // Add type filter
            $('#typeFilter').on('change', function () {
                var filterValue = $(this).val();
                if (filterValue !== '') {
                    table.column(1).search(filterValue).draw();
                    recordFilterClick('type_filter', filterValue);
                }
            });
            
            // Add grade level filter
            $('#gradeLevelFilter').on('change', function () {
                var filterValue = $(this).val();
                if (filterValue !== '') {
                    table.column(4).search(filterValue).draw();
                    recordFilterClick('grade_level_filter', filterValue);
                }
            });
            
            // Add benefits filter
            $('#benefitsFilter').on('change', function () {
                var filterValue = $(this).val();
                if (filterValue !== '') {
                    table.column(5).search(filterValue).draw();
                    recordFilterClick('benefits_filter', filterValue);
                }
            });
            
        
            // Function to record details button click
            function recordDetailsClick(scholarship) {
                $.ajax({
                    type: 'POST',
                    url: 'record_click.php', // Replace with the actual PHP file to handle the database update
                    data: {
                        detailsClick: true,
                        scholarship: scholarship
                    },
                    success: function (response) {
                        console.log('Details click recorded successfully');
                    },
                    error: function (error) {
                        console.error('Error recording details click', error);
                    }
                });
            }
        
            // Add details button click event
            $('#scholarshipTable').on('click', 'button', function () {
                var scholarship = $(this).closest('tr').find('td:eq(3)').text(); // Assuming the scholarship name is in the 4th column
                recordDetailsClick(scholarship);
            });
        
            // Add reset button
            $('#resetFilters').on('click', function () {
                $('#typeFilter, #gradeLevelFilter, #benefitsFilter').val('');
                table.column(1).search('').draw();
                table.column(4).search('').draw();
                table.column(5).search('').draw();
                
                // Manually trigger change event to update filters
                $('#typeFilter, #gradeLevelFilter, #benefitsFilter').trigger('change');
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
            '',
            'Full tuition, fees, allowance per semester', 
            'Full tuition and fees only per semester', 
            'Full tuition only per semester', 
            '',
            'PARTIAL TUITION SCHOLARSHIPS:',
            '',
            'Php 60,000 above per semester', 
            'Php 30,000 - Php 60,000 per semester', 
            'Php 10,000 - Php 29,999 per semester', 
            'Php 4,000 - Php 9,999 per semester',
            '',
            'TUITION DISCOUNTS:', 
            '',
            '15 to 24 units covered per semester', 
            '',
            'ALLOWANCE-ONLY SCHOLARSHIPS:',
            '',
            'Living, uniform, book, or transportation allowances', 
            '',
            'FEES-ONLY SCHOLARSHIPS:',
            '',
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
        ?>
        
        <?php
        showSharer("https://google.com/", "Available scholarship programs in FSUU, check it out!");
        ?>
</div> <br> <br> <br> <br>
      
</section><!-- End About Section -->                

</main><!-- End #main --> 

  <!-- ======= Footer ======= -->
  <footer id="footer">

    <div class="container">
      <div class="copyright">
        <span>2024</span></strong>. FSUU Scholarships Portal
      </div>
    </div>
  </footer><!-- End Footer -->

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