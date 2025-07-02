<?php
include('includes/dbconnection.php');

$date = $_POST['date'];
$time = $_POST['time'];

$query = mysqli_query($con, "SELECT COUNT(*) as count FROM tblservicerequest WHERE ServiceDate='$date' AND ServiceTime='$time'");
$result = mysqli_fetch_array($query);

if ($result['count'] >= 2) {
    echo 'full';
} else {
    echo 'available';
}
?>
