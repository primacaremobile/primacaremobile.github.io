<?php
session_start();
include('includes/dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION['sid'])) {
        $uid = $_SESSION['sid'];
        $servicedate = $_POST['servicedate'];
        $servicetime = $_POST['servicetime'];
        $servicelocation = $_POST['servicelocation'];
        $problemdescription = $_POST['problemdescription'];
        $paystack_reference = $_POST['paystack_reference']; // Paystack transaction reference
        $sernumber = mt_rand(100000000, 999999999);

        // Insert main service request
        $query = mysqli_query($con, "INSERT INTO tblservicerequest (UserId, ServiceNumber, ServiceDate, ServiceTime, ServiceLocation, ProblemDescription, PaymentReference) 
                                    VALUES ('$uid', '$sernumber', '$servicedate', '$servicetime', '$servicelocation', '$problemdescription', '$paystack_reference')");

        if ($query) {
            $requestId = mysqli_insert_id($con); // Get last inserted ID

            // Insert Clothing Categories
            if (!empty($_POST['clothing'])) {
                foreach ($_POST['clothing'] as $category => $quantity) {
                    if (!empty($quantity) && is_numeric($quantity)) {
                        mysqli_query($con, "INSERT INTO tblclothingrequest (RequestId, Category, Quantity) 
                                            VALUES ('$requestId', '$category', '$quantity')");
                    }
                }
            }

            echo "Service request submitted successfully!";
        } else {
            echo "Error submitting request. Please try again.";
        }
    } else {
        echo "User not logged in. Please log in again.";
    }
} else {
    echo "Invalid request.";
}
?>
