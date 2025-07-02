<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['submit'])) {
    $fname = $_POST['fullname'];
    $mobno = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $referral = $_POST['referralcode']; 
    $carMake = $_POST['carmake'];
    $carModel = $_POST['carmodel'];
    $engineType = $_POST['enginetype'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $licensePlate = $_POST['licenseplate'];

    if(substr($mobno, 0, 1) == '0') {
        $mobno = '234' . substr($mobno, 1);
    }

    $ret = mysqli_query($con, "SELECT Email FROM tbluser WHERE Email='$email' OR MobileNo='$mobno'");
    $result = mysqli_fetch_array($ret);

    if($result > 0) {
        $msg = "This email or Contact Number is already associated with another account";
    } else {
        // Generate a unique referral code for the new user
        $uniqueReferralCode = substr(md5(uniqid(rand(), true)), 0, 8);

        // Log the referral usage if a referral code is provided
        if ($referral) {
            $logReferralQuery = "INSERT INTO referrals_log (referrer_code, referral_date) VALUES ('$referral', NOW())";
            mysqli_query($con, $logReferralQuery);
        }

        // Insert the new user into the database with the generated referral code
        $query = mysqli_query($con, "INSERT INTO tbluser (FullName, MobileNo, Email, Password, ReferralCode, ReferredBy, CarMake, CarModel, EngineType, State, City, LicensePlate) VALUES ('$fname', '$mobno', '$email', '$password', '$uniqueReferralCode', '$referral', '$carMake', '$carModel', '$engineType', '$state', '$city', '$licensePlate')");
        
        if ($query) {
            // Redirect the user to the login page after successful registration
            header('Location: index.php');
            exit();
        } else {
            $msg = "Something Went Wrong. Please try again";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Vehicle Service Management System</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../assets/js/modernizr.min.js"></script>
    <script type="text/javascript">
        function checkpass() {
            if(document.signup.password.value != document.signup.repeatpassword.value) {
                alert('Password and Repeat Password field does not match');
                document.signup.repeatpassword.focus();
                return false;
            }
            return true;
        } 
    </script>
</head>
<body class="account-pages">
    <div class="accountbg" style="background: url('../assets/images/bg-2.jpg');background-position: center; height: 750px;"></div>
    <div class="wrapper-page account-page-full">
        <div class="card">
            <div class="card-block">
                <div class="account-box">
                    <div class="card-box p-5">
                        <h3 class="text-uppercase text-center pb-4">
                            <a href="../../FAQ.html">
                                <span>EDEN | Sign Up. </span>
                            </a>
                        </h3>
                        <hr color="#000" />
                        <p style="font-size:16px; color:red" align="center"><?php if($msg){ echo $msg; } ?></p>
                        <form class="form-horizontal" action="" name="signup" method="post" onsubmit="return checkpass();">
                            <div class="form-group">
                                <label for="fullname">Full Name</label>
                                <input class="form-control" type="text" id="fullname" name="fullname" required="" placeholder="Enter Your Full Name">
                            </div>
                            <div class="form-group">
                                <label for="mobilenumber">Mobile Number</label>
                                <input class="form-control" type="text" id="mobilenumber" name="mobilenumber" required="" placeholder="Enter Your Mobile Number" maxlength="11" pattern="[0-9]+">
                            </div>
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input class="form-control" type="email" id="email" name="email" required="" placeholder="abc@gmail.com">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input class="form-control" type="password" required="" id="password" name="password" placeholder="Enter your password">
                            </div>
                            <div class="form-group">
                                <label for="repeatpassword">Repeat Password</label>
                                <input class="form-control" type="password" required="" id="repeatpassword" name="repeatpassword" placeholder="Enter your password">
                            </div>
                            <div class="form-group">
                                <label for="referralcode">Referral Code (optional)</label>
                                <input class="form-control" type="text" id="referralcode" name="referralcode" placeholder="Enter Referral Code">
                            </div>
                       
                        
                            <div class="form-group">
                                <label for="state">Home Address</label>
                                <input class="form-control" type="text" id="state" name="state" required="" placeholder="PickUp & Delivery Location">
                            </div>
                        
                            <div class="form-group text-center m-t-10">
                                <button class="btn btn-block btn-custom waves-effect waves-light" type="submit" name="submit">Sign Up</button>
                            </div>
                        </form>
                        <div class="row m-t-50">
                            <div class="col-12 text-center">
                                <p class="text-muted">Already have an account? <a href="index.php" class="text-dark m-l-5"><b>Sign In</b></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/popper.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/metisMenu.min.js"></script>
    <script src="../assets/js/waves.js"></script>
    <script src="../assets/js/jquery.slimscroll.js"></script>
    <script src="../assets/js/jquery.core.js"></script>
    <script src="../assets/js/jquery.app.js"></script>
</body>
</html>
