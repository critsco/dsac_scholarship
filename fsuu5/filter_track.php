<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Details Click Count</title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Include Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <!-- Include CanvasJS -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

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

// Check if a date range is specified
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);

    // Ensure the date format matches the database format
    $start_date = date('Y-m-d', strtotime($start_date));
    $end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));

    // Retrieve count of occurrences of 'fsuu3/index.php' in the 'view' column of the 'page_view' table within the specified date range
    $view_count_with_date_range_sql = "SELECT COUNT(*) AS view_count 
                                        FROM page_view 
                                        WHERE view = 'fsuu3/index.php' 
                                        AND date BETWEEN '$start_date' AND '$end_date'";
    
    $result_with_date_range = $conn->query($view_count_with_date_range_sql);

    if ($result_with_date_range->num_rows > 0) {
        $row_with_date_range = $result_with_date_range->fetch_assoc();
        $total_views_with_date_range = $row_with_date_range['view_count'];
        echo "Page View: $total_views_with_date_range";
    } else {
        echo "<p class='page-view'>Page View: $total_views_with_date_range</p>";
    }
} else {
    echo "<p class='page-view'>No data available for the specified date range.</p>";
}


// Check if a date range is selected
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    // Sanitize and validate user input
    $start_date = mysqli_real_escape_string($conn, $_GET['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_GET['end_date']);

    // Ensure the date format matches the database format
    $start_date = date('Y-m-d', strtotime($start_date));
    
    // Automatically add 1 day to the end date
    $end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));

    // Retrieve type filter click count for each option within the selected date range
    $type_sql = "SELECT option, COUNT(*) as click_count 
            FROM type_filter 
            WHERE date_click BETWEEN '$start_date' AND '$end_date'
            GROUP BY option 
            ORDER BY click_count DESC";

    // Retrieve grade level filter click count for each option within the selected date range
    $grade_level_sql = "SELECT option, COUNT(*) as click_count 
            FROM grade_level_filter 
            WHERE date_click BETWEEN '$start_date' AND '$end_date'
            GROUP BY option 
            ORDER BY click_count DESC";

    // Retrieve benefits filter click count for each option within the selected date range
    $benefits_sql = "SELECT option, COUNT(*) as click_count 
            FROM benefits_filter 
            WHERE date_click BETWEEN '$start_date' AND '$end_date'
            GROUP BY option 
            ORDER BY click_count DESC";
} else {
    // Retrieve type filter click count for each option without date range filtering
    $type_sql = "SELECT option, COUNT(*) as click_count FROM type_filter GROUP BY option ORDER BY click_count DESC";

    // Retrieve grade level filter click count for each option without date range filtering
    $grade_level_sql = "SELECT option, COUNT(*) as click_count FROM grade_level_filter GROUP BY option ORDER BY click_count DESC";

    // Retrieve benefits filter click count for each option without date range filtering
    $benefits_sql = "SELECT option, COUNT(*) as click_count FROM benefits_filter GROUP BY option ORDER BY click_count DESC";
}

// Process each table individually
processTable("type_filter", $type_sql, "Scholarship by Category");
processTable("grade_level_filter", $grade_level_sql, "Scholarship by Grade Level");
processTable("benefits_filter", $benefits_sql, "Scholarship by Benefits");

$conn->close();

function processTable($table_name, $sql, $chartTitle) {
    global $conn;

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Create an array to store data for CanvasJS
        $canvasJSData = array();

        while ($row = $result->fetch_assoc()) {
            // Add data to CanvasJS array
            $canvasJSData[] = array("label" => $row['option'], "y" => $row['click_count']);
        }

        // Output the CanvasJS chart
        echo "<div id='chartContainer_$table_name' style='height: 370px; width: 100%;'></div>";
        echo "<script>
                    var chart_$table_name = new CanvasJS.Chart('chartContainer_$table_name', {
                        animationEnabled: true,
                        title: {
                            text: '$chartTitle'
                        },
                        axisY: {
                            title: 'Click Count'
                        },
                        data: [{
                            type: 'column',
                            dataPoints: " . json_encode($canvasJSData, JSON_NUMERIC_CHECK) . "
                        }]
                    });
                    chart_$table_name.render();
              </script>";
    } else {
        echo "No data available for $table_name.";
    }
}
?>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Include Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

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