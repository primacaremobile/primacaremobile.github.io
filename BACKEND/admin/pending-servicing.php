<?php  
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['adid']) == 0) {
    header('location:logout.php');
} else {
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>EDEN || PENDING SERVICE</title>
    <!-- App css -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../assets/js/modernizr.min.js"></script>
</head>

<body>
    <div id="wrapper">
        <?php include_once('includes/sidebar.php');?>
        <div class="content-page">
            <?php include_once('includes/header.php');?>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card-box">
                                <h4 class="m-t-0 header-title">Pending Services</h4>
                                <button class="btn btn-primary mb-3" onclick="printTable()">Print</button>
                                
                                <form method="GET" action="">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Filter by Assigned Hub:</label>
                                            <select name="hub" class="form-control">
                                                <option value="">All</option>
                                                <?php
                                                    $hubQuery = mysqli_query($con, "SELECT DISTINCT FullName FROM tblmechanics");
                                                    while ($hubRow = mysqli_fetch_assoc($hubQuery)) {
                                                        echo "<option value='" . $hubRow['FullName'] . "'>" . $hubRow['FullName'] . "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-success btn-block">Filter</button>
                                        </div>
                                    </div>
                                </form>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="p-20">
                                            <div id="printArea">
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>S.NO</th>
                                                            <th>Location</th>
                                                            <th>Full Name</th>
                                                            <th>Mobile Number</th>
                                                            <th>Email</th>
                                                            <th>Assigned Hub</th>
                                                        </tr>
                                                    </thead>
                                                    <?php
                                                        $whereClause = " WHERE sr.AdminStatus='1'";
                                                        if (!empty($_GET['location'])) {
                                                            $location = mysqli_real_escape_string($con, $_GET['location']);
                                                            $whereClause .= " AND u.State='$location'";
                                                        }
                                                        if (!empty($_GET['hub'])) {
                                                            $hub = mysqli_real_escape_string($con, $_GET['hub']);
                                                            $whereClause .= " AND m.FullName='$hub'";
                                                        }

                                                        $query = "SELECT sr.ID as apid, sr.ServiceLocation, u.FullName, u.MobileNo, u.Email, m.FullName AS MechanicName 
                                                                  FROM tblservicerequest sr
                                                                  INNER JOIN tbluser u ON u.ID = sr.UserId
                                                                  LEFT JOIN tbluser_mechanics um ON u.ID = um.UserID
                                                                  LEFT JOIN tblmechanics m ON um.MechanicID = m.ID" . $whereClause;

                                                        $ret = mysqli_query($con, $query);
                                                        $cnt = 1;
                                                        while ($row = mysqli_fetch_array($ret)) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $cnt;?></td>
                                                        <td><?php echo $row['ServiceLocation'];?></td>              
                                                        <td><?php echo $row['FullName'];?></td>
                                                        <td><?php echo $row['MobileNo'];?></td>
                                                        <td><?php echo $row['Email'];?></td>
                                                        <td><?php echo ($row['MechanicName']) ? $row['MechanicName'] : 'Not Assigned'; ?></td>

                                                        <td><a href="view-service.php?aticid=<?php echo $row['apid'];?>">View Details</a></td>
                                                    </tr>
                                                    <?php 
                                                        $cnt++;
                                                    }?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div> 
            </div> 
            <?php include_once('includes/footer.php');?>
        </div>
    </div>
    <script>
        function printTable() {
            var printContent = document.getElementById("printArea").innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
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
