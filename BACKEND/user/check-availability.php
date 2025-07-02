<?php
include('includes/dbconnection.php');

if (isset($_POST['servicedate']) && isset($_POST['servicetime'])) {
    $servicedate = $_POST['servicedate'];
    $servicetime = $_POST['servicetime'];

    $query = mysqli_query($con, "SELECT COUNT(*) as count FROM tblservicerequest WHERE ServiceDate='$servicedate' AND ServiceTime='$servicetime'");
    $result = mysqli_fetch_array($query);

    if ($result['count'] >= 2) {
        echo 'unavailable';
    } else {
        echo 'available';
    }
}
?>
