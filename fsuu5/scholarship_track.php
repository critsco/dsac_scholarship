<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Details Click Count</title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <!-- Include Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <style>
        body {
            background-color: #1015A3; /* Set the background color to a light shade */
            padding-top: 30px; /* Adjust the top padding to accommodate the fixed navbar */
        }

        .container {
            background-color: #fffffe; /* Set the container background color */
            border-radius: 10px; /* Add border-radius for a rounded appearance */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow for depth */
            padding: 20px; /* Add some padding to the container */
            margin-top: 20px; /* Add some space between the container and the top */
        }

        /* Style for the export to PDF button */
        #exportButton {
            margin-left: auto; /* Align to the right */
            display: block; /* Make it a block-level element for full width */
            margin-bottom: 10px; /* Add some space at the bottom */
        }
    </style>

</head>
<body>

<div class="container mt-4">
<button id="exportButton" class="btn btn-outline-danger" onclick="printOrSavePDF()">Export</button>
    <!-- Date Range Picker -->
    <form action="" method="get" id="dateFilterForm">
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="start_date">From:</label>
                <input type="date" class="form-control datepicker" name="start_date" id="start_date" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="end_date">To:</label>
                <input type="date" class="form-control datepicker" name="end_date" id="end_date" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="invisible">Filter</label>
                <button class="btn btn-primary btn-block" type="submit">Filter</button>
            </div>
        </div>
    </form>

    <?php
    include "db_conn.php";

    // Check if a date range is selected
    if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
        // Sanitize and validate user input
        $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
        $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);

        // Ensure the date format matches the database format
        $start_date = date('Y-m-d', strtotime($start_date));

        // Automatically add 1 day to the end date
        $end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));

        // Retrieve details click count for each scholarship within the selected date range
        $sql = "SELECT scholarship, COUNT(*) as click_count 
            FROM details_click 
            WHERE date_click BETWEEN '$start_date' AND '$end_date'
            GROUP BY scholarship 
            ORDER BY click_count DESC";
    } else {
        // Retrieve details click count for each scholarship without date range filtering
        $sql = "SELECT scholarship, COUNT(*) as click_count FROM details_click GROUP BY scholarship ORDER BY click_count DESC";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2 class='mt-4 mb-4'>Number of Views for Each Scholarship</h2>";
        echo "<div class='table-responsive'>";
        echo "<table id='myTable' class='table table-striped table-bordered'>";
        echo "<thead class='thead-dark'><tr><th>Scholarship</th><th>Views</th></tr></thead>";
        echo "<tbody>";
    
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['scholarship'] . "</td>";
            echo "<td>" . $row['click_count'] . "</td>";
            echo "</tr>";
        }
    
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p class='mt-4 mb-4'>No details click data available.</p>";
    }

    $conn->close();
    ?>

    <!-- Include jQuery, Bootstrap JS, and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <!-- Include Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>

</div> <br>

<script>
    function printOrSavePDF() {
        // Open the print dialog
        window.print();
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Check if start_date and end_date parameters are present in the URL
        const urlParams = new URLSearchParams(window.location.search);
        const startDateParam = urlParams.get('start_date');
        const endDateParam = urlParams.get('end_date');

        if (startDateParam && endDateParam) {
            // If date parameters are present, set them as default values
            document.getElementById("start_date").value = startDateParam;
            document.getElementById("end_date").value = endDateParam;
        } else {
            // If no date parameters, set default values (1 month ago to current date)
            var currentDate = new Date();
            var oneMonthAgo = new Date();
            oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
            var formattedCurrentDate = currentDate.toISOString().split('T')[0];
            var formattedOneMonthAgo = oneMonthAgo.toISOString().split('T')[0];
            
            document.getElementById("start_date").value = formattedOneMonthAgo;
            document.getElementById("end_date").value = formattedCurrentDate;
        }
    });
</script>

</body>
</html>
