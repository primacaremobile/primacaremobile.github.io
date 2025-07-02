<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['adid']==0)) {
  header('location:logout.php');
} else {
  if(isset($_POST['submit'])) {
    $sid = substr(base64_decode($_GET['udid']), 0, -5);
    $fname = $_POST['fullname'];
    $oemVerified = isset($_POST['oemverified']) ? 1 : 0;

    // Get the approval statuses from the form
    $engineRepairsApproved = isset($_POST['enginerepairsapproved']) ? 1 : 0;
    $paintJobsApproved = isset($_POST['paintjobsapproved']) ? 1 : 0;
    $highCostWorksApproved = isset($_POST['highcostworksapproved']) ? 1 : 0;
// Check if user has a record in tblservicerequest
$checkRequest = mysqli_query($con, "SELECT * FROM tblservicerequest WHERE UserId='$sid'");
if (mysqli_num_rows($checkRequest) == 0) {
    mysqli_query($con, "INSERT INTO tblservicerequest (UserId) VALUES ('$sid')");
}

    // Update the user's full name, OEM verification status, and service approvals
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

    if ($query) {
      $msg = "User profile and service approvals have been updated";
    } else {
      $msg = "Something went wrong. Please try again";
    }
  }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>EDEN || EDIT USER</title>
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
                  <h4 class="m-t-0 header-title">Update Registered User</h4>
                  <p class="text-muted m-b-30 font-14"></p>
                  <div class="row">
                    <div class="col-12">
                      <div class="p-20">
                        <p style="font-size:16px; color:red" align="center">
                          <?php if($msg) { echo $msg; } ?>
                        </p>
                        <form class="form-horizontal" role="form" method="post" name="submit">
                          <?php
                          $sid = substr(base64_decode($_GET['udid']), 0, -5);
                          $ret = mysqli_query($con, 
                              "SELECT u.*, 
                                      s.EngineRepairsApproved, 
                                      s.PaintJobsApproved, 
                                      s.HighCostWorksApproved 
                               FROM tbluser u 
                               LEFT JOIN tblservicerequest s ON u.ID = s.UserId 
                               WHERE u.ID='$sid' 
                               LIMIT 1"
                          );
                          if ($row = mysqli_fetch_array($ret)) {
                          ?> 
                          <div class="form-group row">
                            <label class="col-2 col-form-label" for="fullname">Full Name</label>
                            <div class="col-10">
                              <input type="text" id="fullname" name="fullname" class="form-control" required="true" value="<?php echo $row['FullName']; ?>">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-2 col-form-label" for="mobilenumber">Mobile Number</label>
                            <div class="col-10">
                              <input type="text" id="mobilenumber" name="mobilenumber" class="form-control" required="true" readonly="true" value="<?php echo $row['MobileNo']; ?>">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-2 col-form-label" for="email">Email</label>
                            <div class="col-10">
                              <input type="email" id="email" name="email" class="form-control" required="true" readonly="true" value="<?php echo $row['Email']; ?>">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-2 col-form-label" for="regdate">Registration Date</label>
                            <div class="col-10">
                              <input type="text" id="regdate" name="regdate" class="form-control" required="true" readonly="true" value="<?php echo $row['RegDate']; ?>">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-2 col-form-label" for="oemverified">Member Verified</label>
                            <div class="col-10">
                              <input type="checkbox" id="oemverified" name="oemverified" <?php if ($row['OEMVerified'] == 1) echo "checked"; ?>>
                              <label for="oemverified">Check to verify account creation</label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-2 col-form-label" for="enginerepairsapproved">Pickup Approval</label>
                            <div class="col-10">
                              <input type="checkbox" id="enginerepairsapproved" name="enginerepairsapproved" <?php if ($row['EngineRepairsApproved'] == 1) echo "checked"; ?>>
                              <label for="enginerepairsapproved">Check to approve pickups</label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-2 col-form-label" for="paintjobsapproved">Delivery Approval</label>
                            <div class="col-10">
                              <input type="checkbox" id="paintjobsapproved" name="paintjobsapproved" <?php if ($row['PaintJobsApproved'] == 1) echo "checked"; ?>>
                              <label for="paintjobsapproved">Check to approve Deliveries</label>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-2 col-form-label" for="highcostworksapproved">Location Assigning</label>
                            <div class="col-10">
                              <input type="checkbox" id="highcostworksapproved" name="highcostworksapproved" <?php if ($row['HighCostWorksApproved'] == 1) echo "checked"; ?>>
                              <label for="highcostworksapproved">Check if members location to laundry has been established</label>
                            </div>
                          </div>
                          <?php } ?>
                          <div class="form-group row">
                            <div class="col-12">
                              <p style="text-align: center;">
                                <button type="submit" name="submit" class="btn btn-info btn-min-width mr-1 mb-1">Update</button>
                              </p>
                            </div>
                          </div>
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
    <script src="../assets/js/metisMenu.min.js"></script>
    <script src="../assets/js/waves.js"></script>
    <script src="../assets/js/jquery.slimscroll.js"></script>
    <script src="../assets/js/jquery.core.js"></script>
    <script src="../assets/js/jquery.app.js"></script>
</body>
</html>
<?php } ?>
