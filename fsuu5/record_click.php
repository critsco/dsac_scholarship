<?php
include "db_conn.php";

if (isset($_POST['filterType']) && isset($_POST['filterValue'])) {
    $filterType = $_POST['filterType'];
    $filterValue = $_POST['filterValue'];

    $sql = "INSERT INTO $filterType (option) VALUES ('$filterValue')";
    $conn->query($sql);
}

if (isset($_POST['detailsClick']) && isset($_POST['scholarship'])) {
    $scholarship = $_POST['scholarship'];

    $sql = "INSERT INTO details_click (scholarship) VALUES ('$scholarship')";
    $conn->query($sql);
}

$conn->close();
?>
