<?php  
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['adid']==0)) {
    header('location:logout.php');
} else {
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Vehicle Service Management System</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../assets/js/modernizr.min.js"></script>
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
                                <h4 class="m-t-0 header-title">Register Users</h4>
                                <p class="text-muted m-b-30 font-14"></p>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="p-20">
                                            <table class="table mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>S.NO</th>
                                                        <th>Full Name</th>
                                                        <th>Mobile Number</th>
                                                        <th>Email</th>
                                                        <th>Registration Date</th>
                                                        <th>Assigned Mechanic</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                                $rno = mt_rand(10000, 99999);

                                                // Fetch users along with assigned mechanic names (if any)
                                                $query = "SELECT u.ID, u.FullName, u.MobileNo, u.Email, u.RegDate, 
                                                m.FullName AS AssignedMechanic 
                                         FROM tbluser u
                                         LEFT JOIN tbluser_mechanics um ON u.ID = um.UserID
                                         LEFT JOIN tblmechanics m ON um.MechanicID = m.ID";
                               
                               
                                                
                                                $result = mysqli_query($con, $query);

                                                if (!$result) {
                                                    die("Query failed: " . mysqli_error($con)); // Debugging
                                                }

                                                $cnt = 1;
                                                while ($row = mysqli_fetch_array($result)) { ?>
                                                    <tr>
                                                        <td><?php echo $cnt; ?></td>
                                                        <td><?php echo $row['FullName']; ?></td>
                                                        <td><?php echo $row['MobileNo']; ?></td>
                                                        <td><?php echo $row['Email']; ?></td>
                                                        <td><?php echo $row['RegDate']; ?></td>
                                                        <td><?php echo ($row['AssignedMechanic']) ? $row['AssignedMechanic'] : "Not Assigned"; ?></td>
                                                        <td>
                                                            <a href="edit-userdetail.php?udid=<?php echo base64_encode($row['ID'] . $rno); ?>" title="Edit user details">
                                                                <i class="la la-edit"> edit detail</i>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="assign location.php?udid=<?php echo base64_encode($row['ID'] . $rno); ?>" title="Edit user details">
                                                                <i class="la la-edit"> edit hub</i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php
                                                    $cnt++;
                                                }
                                                ?>
                                            </table>
                                        </div>
                                    </div>
                                </div> <!-- end row -->
                            </div> <!-- end card-box -->
                        </div> <!-- end col -->
                    </div> <!-- end row -->
                </div> <!-- container -->
            </div> <!-- content -->
            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/metisMenu.min.js"></script>
    <script src="../assets/js/waves.js"></script>
    <script src="../assets/js/jquery.slimscroll.js"></script>
    <script src="../assets/js/jquery.core.js"></script>
    <script src="../assets/js/jquery.app.js"></script>
</body>

</html>
<?php } ?>
