<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['adid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $cid = $_GET['aticid'];
        $admsta = $_POST['status'];
        
        $query = mysqli_query($con, "UPDATE tblservicerequest SET AdminStatus='$admsta' WHERE ID='$cid'");
        
        if ($query) {
            $msg = "Status has been updated";
        } else {
            $msg = "Something Went Wrong. Please try again";
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Vehicle Service Management System</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="wrapper">
    <?php include_once('includes/sidebar.php'); ?>
    <div class="content-page">
        <?php include_once('includes/header.php'); ?>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title">View Services</h4>
                            <p style="font-size:16px; color:red" align="center"> <?php if ($msg) { echo $msg; } ?> </p>

                            <?php
                            $cid = $_GET['aticid'];
                            $ret = mysqli_query($con, "SELECT * FROM tblservicerequest JOIN tbluser ON tbluser.ID=tblservicerequest.UserId WHERE tblservicerequest.ID='$cid'");
                            $row = mysqli_fetch_array($ret);
                            ?>

                            <table border="1" class="table table-bordered">
                                <tr><th>Service Number</th><td><?php echo $row['ServiceNumber']; ?></td></tr>
                                <tr><th>Full Name</th><td><?php echo $row['FullName']; ?></td></tr>
                                <tr><th>Service Date</th><td><?php echo $row['ServiceDate']; ?></td></tr>
                                <tr><th>Admin Status</th><td><?php echo ($row['AdminStatus'] == 1) ? "Approved" : (($row['AdminStatus'] == 2) ? "Rejected" : "Pending"); ?></td></tr>
                            </table>

                            <h4>Clothing Requests</h4>
                            <table border="1" class="table table-bordered">
                                <tr><th>Category</th><th>Quantity</th></tr>
                                <?php
                                $ret2 = mysqli_query($con, "SELECT * FROM tblclothingrequest WHERE RequestId='$cid'");
                                while ($row2 = mysqli_fetch_array($ret2)) {
                                    echo "<tr><td>" . $row2['Category'] . "</td><td>" . $row2['Quantity'] . "</td></tr>";
                                }
                                ?>
                            </table>

                            <?php if ($row['AdminStatus'] == '') { ?>
                                <form method="post">
                                    <tr><th>Admin Status:</th>
                                        <td>
                                            <select name="status" class="form-control" required>
                                                <option value="">Select</option>
                                                <option value="1">Approved</option>
                                                <option value="2">Cancelled</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr align="center">
                                        <td colspan="2"><button type="submit" name="submit" class="btn btn-primary">Submit</button></td>
                                    </tr>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once('includes/footer.php'); ?>
    </div>
</div>
<script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/metisMenu.min.js"></script>
        <script src="../assets/js/waves.js"></script>
        <script src="../assets/js/jquery.slimscroll.js"></script>

        <!-- App js -->
        <script src="../assets/js/jquery.core.js"></script>
        <script src="../assets/js/jquery.app.js"></script>
</body>
</html>
<?php } ?>
