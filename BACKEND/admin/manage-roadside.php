<?php  
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['adid']==0)) {
  header('location:logout.php');
} else {
// for Completing Records
if ($_GET['id']) {
    $eid = substr(base64_decode($_GET['id']), 0, -5);
    $query = mysqli_query($con, "UPDATE roadside SET completed=1 WHERE ID='$eid'");
    if ($query) {
        echo "<script>alert('Record marked as completed.');</script>";
        echo "<script>window.location.href ='all-roadside.php'</script>";
    } else {
        echo "<script>alert('Something Went Wrong. Please try again.');</script>";
    }    
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>EDEN || Emergencies</title>
    <!-- App css -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../assets/js/modernizr.min.js"></script>
</head>

<body>
    <!-- Begin page -->
    <div id="wrapper">
        <?php include_once('includes/sidebar.php'); ?>
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <?php include_once('includes/header.php'); ?>
            <!-- Start Page content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="m-t-0 header-title">Manage Emergencies</h4>
                                <p class="text-muted m-b-30 font-14"></p>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="p-20">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>S.NO</th>
                                                        <th>User Full Name</th>
                                                        <th>Mobile Number</th>
                                                        <th>Response</th>
                                                        <th>Reference</th>
                                                        <th>Location</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                $rno = mt_rand(10000, 99999);
                                                $ret = mysqli_query($con, "SELECT roadside.ID, tbluser.FullName, tbluser.State, tbluser.MobileNo, roadside.Response, roadside.Reference FROM roadside JOIN tbluser ON roadside.UserId = tbluser.ID WHERE roadside.completed=0");
                                                $cnt = 1;
                                                while ($row = mysqli_fetch_array($ret)) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo $row['FullName']; ?></td>
                                                        <td><?php echo $row['MobileNo']; ?></td>
                                                        <td><?php echo $row['Response']; ?></td>
                                                        <td><?php echo $row['Reference']; ?></td>
                                                        <td><?php echo $row['State']; ?></td>
                                                        <td><a href="manage-roadside.php?id=<?php echo base64_encode($row['ID'] . $rno); ?>" style="color:green;">Done</a> | <a href="manage-roadside.php?id=<?php echo base64_encode($row['ID'] . $rno); ?>" style="color:red;">Delete</a></td>
                                                    </tr>
                                                <?php $cnt = $cnt + 1;
                                                } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                            </div> <!-- end card-box -->
                        </div><!-- end col -->
                    </div>
                </div> <!-- container -->
            </div> <!-- content -->
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>
    <!-- jQuery  -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/metisMenu.min.js"></script>
    <script src="../assets/js/waves.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <!-- App js -->
    <script src="../assets/js/jquery.core.js"></script>
    <script src="../assets/js/jquery.app.js"></script>
</body>
</html>
<?php } ?>
