<?php
// Establish a connection to the database
include "db_conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST["type"];
    $provider = $_POST["provider"];
    $scholarship = $_POST["scholarship"];
    $grade_level = $_POST["grade_level"];
    $benefits = $_POST["benefits"];
    $start_date = !empty($_POST["start_date"]) ? $_POST["start_date"] : '2023-01-01'; // Set a default date value
    $end_date = !empty($_POST["end_date"]) ? $_POST["end_date"] : '2023-01-01'; // Set a default date value
    $file_attachment = $_FILES["file_attachment"];
    $status = $_POST["submit"]; // 'Post' or 'Draft'

    // Handling file upload
    $file_attachment = $_FILES["file_attachment"];

    // Check if a file is provided
    if ($file_attachment["size"] > 0) {
        // File is provided
        $target_dir = "uploads/";
        $original_filename = basename($file_attachment["name"]);
        $file_extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));

        // Generate a unique filename using timestamp
        $timestamp = time();
        $target_file = $target_dir . $timestamp . "_" . $original_filename;

        // Check if the file is a PDF
        if ($file_extension != "pdf") {
            echo "<script>alert('Sorry, only PDF files are allowed.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Check file size (max 10MB)
        if ($file_attachment["size"] > 10 * 1024 * 1024) {
            echo "<script>alert('Sorry, your file is too large. Max file size is 10MB.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Move the uploaded file to the target location
        if (!move_uploaded_file($file_attachment["tmp_name"], $target_file)) {
            echo "Sorry, there was an error uploading your file.";
            echo "<script>window.history.back();</script>";
            exit();
        }
    } else {
        // File is not provided, set target_file to an empty string
        $target_file = '';
    }

     // Insert data into the database
    $sql = "INSERT INTO scholarship (type, provider, scholarship, grade_level, benefits, start_date, end_date, file_attachment, status)
            VALUES ('$type', '$provider', '$scholarship', '$grade_level', '$benefits', '$start_date', '$end_date', '$target_file', '$status')";

    if ($conn->query($sql) === TRUE) {
        header("Location: post2.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>