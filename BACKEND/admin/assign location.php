<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['adid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $sid = substr(base64_decode($_GET['udid']), 0, -5);
        $fname = $_POST['fullname'];
        $oemVerified = isset($_POST['oemverified']) ? 1 : 0;
        $mechanicID = $_POST['mechanic']; // Selected mechanic ID

        // Get the approval statuses from the form
        $engineRepairsApproved = isset($_POST['enginerepairsapproved']) ? 1 : 0;
        $paintJobsApproved = isset($_POST['paintjobsapproved']) ? 1 : 0;
        $highCostWorksApproved = isset($_POST['highcostworksapproved']) ? 1 : 0;

        // Check if user has a record in tblservicerequest
        $checkRequest = mysqli_query($con, "SELECT * FROM tblservicerequest WHERE UserId='$sid'");
        if (mysqli_num_rows($checkRequest) == 0) {
            mysqli_query($con, "INSERT INTO tblservicerequest (UserId) VALUES ('$sid')");
        }

        // Update the user's information
        $query = mysqli_query($con, 
            "UPDATE tbluser u 
             LEFT JOIN tblservicerequest s ON u.ID = s.UserId 
            SET u.FullName='$fname', 
                u.OEMVerified='$oemVerified', 
                s.EngineRepairsApproved='$engineRepairsApproved', 
                s.PaintJobsApproved='$paintJobsApproved', 
                s.HighCostWorksApproved='$highCostWorksApproved' 
            WHERE u.ID='$sid'"
        );

        // Assign mechanic to user
        if ($mechanicID) {
            // Remove any existing assignments for this user
            mysqli_query($con, "DELETE FROM tbluser_mechanics WHERE UserID='$sid'");

            // Assign the new mechanic
            mysqli_query($con, "INSERT INTO tbluser_mechanics (UserID, MechanicID) VALUES ('$sid', '$mechanicID')");
        }

        if ($query) {
            $msg = "User profile, service approvals, and mechanic assignment updated successfully";
        } else {
            $msg = "Something went wrong. Please try again";
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>EDEN || ASSIGN LAUNDRY</title>
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
                  <h4 class="m-t-0 header-title">ASSIGN LAUNDRY</h4>
                  <p class="text-muted m-b-30 font-14"></p>
                  <div class="row">
                    <div class="col-12">
                      <div class="p-20">
                        <p style="font-size:16px; color:red" align="center">
                          <?php if($msg) { echo $msg; } ?>
                        </p>
                        <form class="form-horizontal" role="form" method="post">
                          <?php
                          $sid = substr(base64_decode($_GET['udid']), 0, -5);
                          $ret = mysqli_query($con, 
                              "SELECT u.*, 
                                      s.EngineRepairsApproved, 
                                      s.PaintJobsApproved, 
                                      s.HighCostWorksApproved,
                                      um.MechanicID 
                               FROM tbluser u 
                               LEFT JOIN tblservicerequest s ON u.ID = s.UserId 
                               LEFT JOIN tbluser_mechanics um ON u.ID = um.UserID
                               WHERE u.ID='$sid' 
                               LIMIT 1"
                          );
                          if ($row = mysqli_fetch_array($ret)) {
                          ?> 
                          <div class="form-group row">
                            <label class="col-2 col-form-label">Full Name</label>
                            <div class="col-10">
                              <input type="text" name="fullname" class="form-control" required value="<?php echo $row['FullName']; ?>">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-2 col-form-label">Mobile Number</label>
                            <div class="col-10">
                              <input type="text" class="form-control" required readonly value="<?php echo $row['MobileNo']; ?>">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-2 col-form-label">Email</label>
                            <div class="col-10">
                              <input type="email" class="form-control" required readonly value="<?php echo $row['Email']; ?>">
                            </div>
                          </div>
                                           
                        
                          <div class="form-group row">
                            <label class="col-2 col-form-label">Assign laundry</label>
                            <div class="col-10">
                              <select name="mechanic" class="form-control">
                                <option value="">-- Select Manager --</option>
                                <?php
                                  $mechanics = mysqli_query($con, "SELECT * FROM tblmechanics");
                                  while ($mrow = mysqli_fetch_array($mechanics)) {
                                    $selected = ($row['MechanicID'] == $mrow['ID']) ? "selected" : "";
                                    echo "<option value='{$mrow['ID']}' $selected>{$mrow['FullName']} ({$mrow['MobileNumber']})</option>";
                                  }
                                ?>
                              </select>
                            </div>
                          </div>

                          <div class="form-group row">
                            <div class="col-12">
                              <button type="submit" name="submit" class="btn btn-info">Update</button>
                            </div>
                          </div>
                          <?php } ?>
                        </form>
                      </div>
                    </div>
                  </div>
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
</body>
</html>
<?php } ?>
