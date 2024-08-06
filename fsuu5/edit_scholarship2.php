<?php
// Database connection
include "db_conn.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch scholarship details
    $sql = "SELECT * FROM scholarship WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Fetch 'year_level' from the database
        $grade_level = $row['grade_level'];
    } else {
        echo "Scholarship not found";
        exit();
    }
} else {
    echo "Invalid request";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update scholarship details
    $type = $_POST["type"];
    $provider = $_POST["provider"];
    $scholarship = $_POST["scholarship"];
    $grade_level = $_POST["grade_level"];
    $benefits = $_POST["benefits"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    // File upload handling
    $file_attachment = $_FILES['file_attachment'];

    // Check if a file was uploaded
    if ($file_attachment['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';

        // Generate a unique filename
        $unique_filename = uniqid() . "_" . basename($file_attachment['name']);
        $uploadFile = $uploadDir . $unique_filename;

        // Check if the file is a PDF
        $file_extension = strtolower(pathinfo($unique_filename, PATHINFO_EXTENSION));
        if ($file_extension != "pdf") {
            echo "<script>alert('Sorry, only PDF files are allowed.');</script>";
            echo "<script>window.history.back();</script>"; // Instruct the user to go back
            exit();
        }

        // Check file size (max 10MB)
        if ($file_attachment['size'] > 10 * 1024 * 1024) {
            echo "<script>alert('Sorry, your file is too large. Max file size is 10MB.');</script>";
            echo "<script>window.history.back();</script>"; // Instruct the user to go back
            exit();
        }

        // Move the file to the uploads directory
        if (move_uploaded_file($file_attachment['tmp_name'], $uploadFile)) {
            // Delete the old PDF file
            if (!empty($row['file_attachment'])) {
                unlink($row['file_attachment']);
            }

            // File uploaded successfully, update the database with the new file path
            $file_attachment = $uploadFile;
        } else {
            // Handle the error, file upload failed
            echo "Error uploading file.";
            exit();
        }
    } else {
        // No new file uploaded, use the existing file path
        $file_attachment = $row['file_attachment'];
    }

    $update_sql = "UPDATE scholarship SET type = '$type', provider = '$provider', scholarship = '$scholarship', grade_level = '$grade_level', benefits = '$benefits', start_date = '$start_date', end_date = '$end_date', file_attachment = '$file_attachment' WHERE id = '$id'";

    if ($conn->query($update_sql) === TRUE) {
        // Scholarship updated successfully, redirect to provider.php
        header("Location: post2.php");
        exit();
    } else {
        echo "Error updating scholarship: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Scholarship Information</title>

    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #1015A3;
            color: #fffffe;
            padding: 10px;
        }

        form {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fffffe;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h1 {
            text-align: center;
            color: #fffffe;
        }

        label {
            margin-top: 5px;
            display: block;
            color: #000; /* Change label color to black */
        }

        select, input[type="text"], input[type="date"], input[type="file"], button {
            margin-bottom: 10px;
        }

        button {
            background-color: #1015A3;
        }

        button:hover {
            background-color: #0a0e7a;
        }
    </style>
</head>
<body>
    <h1>Edit Scholarship Information Form</h1>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    
        <label for="type">Category:</label>
        <select name="type" class="form-select">
            <option value=""></option>
            <option value="FSUU Funded Scholarships" <?php echo ($row['type'] == 'FSUU Funded Scholarships') ? 'selected' : ''; ?>>FSUU Funded Scholarships</option>
            <option value="Government Funded Scholarships" <?php echo ($row['type'] == 'Government Funded Scholarships') ? 'selected' : ''; ?>>Government Funded Scholarships</option>
            <option value="Private Scholarship" <?php echo ($row['type'] == 'Private Scholarship') ? 'selected' : ''; ?>>Private Scholarship</option>
            <option value="Civic/Religious Funded Scholarships" <?php echo ($row['type'] == 'Civic/Religious Funded Scholarships') ? 'selected' : ''; ?>>Civic/Religious Funded Scholarships</option>
        </select><br>
    
        <label for="provider">Provider:</label>
        <input type="text" name="provider" value="<?php echo $row['provider']; ?>" class="form-control"><br>
    
        <label for="scholarship">Scholarship:</label>
        <input type="text" name="scholarship" value="<?php echo $row['scholarship']; ?>" class="form-control"><br>
    
        <label for="grade_level">Grade Level:</label>
        <select name="grade_level" class="form-select">
            <option value=""></option>
            <option value="Basic Education" <?php echo ($row['grade_level'] == 'Basic Education') ? 'selected' : ''; ?>>Basic Education</option>
            <option value="College" <?php echo ($row['grade_level'] == 'College') ? 'selected' : ''; ?>>College</option>
            <option value="Graduate Studies" <?php echo ($row['grade_level'] == 'Graduate Studies') ? 'selected' : ''; ?>>Graduate Studies</option>
        </select><br>
    
        <label for="benefits">Benefits:</label>
        <select name="benefits" class="form-select">
            <option value=""></option>
            <option value="" disabled>FULL COVERAGE SCHOLARSHIPS:</option>
            <option value="Full tuition, fees, allowance per semester" <?php echo ($row['benefits'] == 'Full tuition, fees, allowance per semester') ? 'selected' : ''; ?>>Full tuition, fees, allowance per semester</option>
            <option value="Full tuition and fees only per semester" <?php echo ($row['benefits'] == 'Full tuition and fees only per semester') ? 'selected' : ''; ?>>Full tuition and fees only per semester</option>
            <option value="Full tuition per semester" <?php echo ($row['benefits'] == 'Full tuition only per semester') ? 'selected' : ''; ?>>Full tuition only per semester</option>
            <option value="" disabled>PARTIAL TUITION SCHOLARSHIPS:</option>
            <option value="Php 60,000 above per semester" <?php echo ($row['benefits'] == 'Php 60,000 above per semester') ? 'selected' : ''; ?>>Php 60,000 above per semester</option>
            <option value="Php 30,000 - Php 60,000 per semester" <?php echo ($row['benefits'] == 'Php 30,000 - Php 60,000 per semester') ? 'selected' : ''; ?>>Php 30,000 - Php 60,000 per semester</option>
            <option value="Php 10,000 - Php 29,999 per semester" <?php echo ($row['benefits'] == 'Php 10,000 - Php 29,999 per semester') ? 'selected' : ''; ?>>Php 10,000 - Php 29,999 per semester</option>
            <option value="Php 4,000 - Php 9,999 per semester" <?php echo ($row['benefits'] == 'Php 4,000 - Php 9,999 per semester') ? 'selected' : ''; ?>>Php 4,000 - Php 9,999 per semester</option>
            <option value="" disabled>TUITION DISCOUNTS:</option>
            <option value="15 to 24 units covered per semester" <?php echo ($row['benefits'] == '15 to 24 units covered per semester') ? 'selected' : ''; ?>>15 to 24 units covered per semester</option>
            <option value="" disabled>ALLOWANCE-ONLY SCHOLARSHIPS:</option>
            <option value="Living, uniform, book, or transportation allowances" <?php echo ($row['benefits'] == 'Living, uniform, book, or transportation allowances') ? 'selected' : ''; ?>>Living, uniform, book, or transportation allowances</option>
            <option value="" disabled>FEES-ONLY SCHOLARSHIPS:</option>
            <option value="Covers miscellaneous fees only" <?php echo ($row['benefits'] == 'Covers miscellaneous fees only') ? 'selected' : ''; ?>>Covers miscellaneous fees only</option>
        </select><br>
    
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" value="<?php echo $row['start_date']; ?>" class="form-control"><br>
    
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" value="<?php echo $row['end_date']; ?>" class="form-control"><br>
    
        <label for="file_attachment">Other Details (PDF, max 10MB): <?php echo $row['file_attachment']; ?>:</label>
        <input type="file" name="file_attachment" accept=".pdf" class="form-control"><br>
    
        <button type="submit" name="submit" value="Update" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
    </form> <br>

    <!-- Bootstrap JavaScript and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function cancelEdit() {
            // Redirect back to provider.php when the cancel button is clicked
            window.location.href = 'post2.php';
        }
    </script>
</body>
</html>
