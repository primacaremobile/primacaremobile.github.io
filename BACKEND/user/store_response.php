<?php
session_start();
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = $_POST['response'];
    $reference = $_POST['reference'];
    $userId = $_SESSION['sid'];

    $query = "INSERT INTO roadside (UserId, Response, Reference) VALUES ('$userId', '$response', '$reference')";
    if (mysqli_query($con, $query)) {
        echo "Response and reference stored successfully.";
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($con);
    }
}
?>
