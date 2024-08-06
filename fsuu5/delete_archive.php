<?php
include "db_conn.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch scholarship details based on the provided ID
    $sql_select = "SELECT * FROM scholarship WHERE id = $id";
    $result_select = $conn->query($sql_select);

    if ($result_select->num_rows > 0) {
        $row_select = $result_select->fetch_assoc();
        $file_path = $row_select['file_attachment'];

        if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
            // Proceed with deletion after confirmation

            // Check if the file_attachment is not empty before attempting to delete the file
            if (!empty($file_path) && file_exists($file_path) && unlink($file_path)) {
                // File deleted successfully
            } elseif (!empty($file_path)) {
                // Error deleting PDF file or file not found
                echo "Error deleting PDF file or file not found.";
            }

            // Delete the scholarship record from the database
            $sql_delete = "DELETE FROM scholarship WHERE id = $id";
            if ($conn->query($sql_delete) === TRUE) {
                header("Location: archive.php");
                exit();
            } else {
                echo "Error deleting scholarship: " . $conn->error;
            }
        } else {
            // Display confirmation message
            echo "<script>";
            echo "if (window.confirm('Are you sure to delete this scholarship?')) {";
            echo "  window.location.href = 'delete_archive.php?confirm=yes&id=$id';";
            echo "} else {";
            echo "  window.location.href = 'archive.php';";
            echo "}";
            echo "</script>";
        }
    } else {
        echo "Scholarship not found.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
