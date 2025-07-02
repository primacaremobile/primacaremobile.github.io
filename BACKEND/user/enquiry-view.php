<?php  
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']) == 0) {
  header('location:logout.php');
} else {

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Vehicle Service Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- App css -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../assets/js/modernizr.min.js"></script>
    <script type="text/javascript">
      function printdata() {
        window.print();
      }
    </script>
</head>

<body>

    <!-- Begin page -->
    <div id="wrapper">

        <?php include_once('includes/sidebar.php');?>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->

        <div class="content-page">

            <?php include_once('includes/header.php');?>

            <!-- Start Page content -->
            <div class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="m-t-0 header-title">Enquiry History View</h4>
                                <p class="text-muted m-b-30 font-14"></p>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="p-20">

                                            <form method="post">
                                                <p style="font-size:16px; color:red" align="left">
                                                    <?php if($msg) { echo $msg; } ?>
                                                </p>
                                                <?php
                                                $antcid = substr(base64_decode($_GET['ticid']), 0, -5);
                                                $uid = $_SESSION['sid'];
                                                $ret = mysqli_query($con, "select * from tblenquiry where ID='$antcid' and UserId='$uid'");
                                                $cnt = 1;
                                                while ($row = mysqli_fetch_array($ret)) {
                                                ?>

                                                <div class="table-responsive">
                                                    <table class="table table-bordered mg-b-0">
                                                        <tr>
                                                            <th>Enquiry Date</th>
                                                            <td><?php echo $row['EnquiryDate']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Enquiry Number</th>
                                                            <td><?php echo $row['EnquiryNumber']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Enquiry Type</th>
                                                            <td><?php echo $row['EnquiryType']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Description</th>
                                                            <td><?php echo $row['Description']; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th>Admin Response</th>
                                                            <td><?php echo $row['AdminResponse'] ? $row['AdminResponse'] : "No action taken yet"; ?></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <?php } ?>
                                            </form>
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

        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->

    </div>

    <!-- jQuery  -->
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
