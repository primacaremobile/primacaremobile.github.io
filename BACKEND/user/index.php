<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
require 'vendor/autoload.php'; // Include the Paystack PHP SDK

use Yabacon\Paystack;

// Function to check Paystack subscription status
function checkSubscription($email, $plan_code) {
    $paystack = new Paystack('sk_test_69fbfe08e47e8abd1603588b3a7c796753a5d363'); // Replace with your Paystack secret key

    try {
        // Retrieve customer by email
        $customerList = $paystack->customer->list(['email' => $email]);

        if (!empty($customerList->data)) {
            $customer_id = $customerList->data[0]->id;

            // Retrieve subscriptions for the customer
            $subscriptions = $paystack->subscription->list([
                'customer' => $customer_id,
                'perPage' => 100 // Ensure we retrieve enough subscriptions
            ]);

            foreach ($subscriptions->data as $subscription) {
                if ($subscription->status === 'active' && $subscription->plan->plan_code === $plan_code) {
                    return true;
                }
            }
        }
    } catch (Exception $e) {
        error_log('Error checking subscription: ' . $e->getMessage());
    }

    return false;
}

if (isset($_POST['login'])) {
    $emailcon = $_POST['emailcont'];
    $password = md5($_POST['password']);
    $query = mysqli_query($con, "SELECT ID, ReferredBy FROM tbluser WHERE (Email='$emailcon' OR MobileNo='$emailcon') AND Password='$password'");
    $ret = mysqli_fetch_array($query);

    if ($ret > 0) {
        $referredBy = $ret['ReferredBy'];

        // Determine plan code and sign-up link based on referral code
        if (strtolower($referredBy) === 'palmer') {
            $plan_code = 'PLN_3u2esv4kzg946so';
            $signup_link = 'https://paystack.com/pay/jfdyrztbih';
        } else {
            $plan_code = 'PLN_3u2esv4kzg946so';
            $signup_link = 'https://paystack.com/pay/jfdyrztbih';
        }

        if (checkSubscription($emailcon, $plan_code)) {
            $_SESSION['sid'] = $ret['ID'];
            header('Location: welcome.php');
            exit();
        } else {
            $msg = "No active subscription found. Please <a href='$signup_link'>sign up for a plan</a>.";
        }
    } else {
        $msg = "Invalid Details.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>EDEN | Login</title>
    <!-- App css -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />
    <script src="../assets/js/modernizr.min.js"></script>
</head>

<body class="account-pages">

    <!-- Begin page -->
    <div class="accountbg" style="background: url('../assets/images/bg-2.jpg');background-size: cover;background-position: center; border:solid 1px;"></div>

    <div class="wrapper-page account-page-full">

        <div class="card">
            <div class="card-block">

                <div class="account-box">

                    <div class="card-box p-5">
                        <h3 class="text-uppercase text-center pb-4">
                            <a href="../index.php" ><span>EDEN | User Login</span></a>
                        </h3>
                        <hr color="#000" />
                        <p style="font-size:16px; color:red" align="center">
                            <?php if ($msg) {
                                echo $msg;
                            } ?>
                        </p>

                        <form class="" action="#" name="login" method="post">

                            <div class="form-group m-b-20">
                                <label for="emailaddress">Email address</label>
                                <input class="form-control" type="text" id="email" name="emailcont" required="" placeholder="Registered Email or Contact Number">
                            </div>

                            <div class="form-group m-b-20">
                                <a href="forget-password.php" class="text-muted float-right"><small>Forgot your password?</small></a>
                                <label for="password">Password</label>
                                <input class="form-control" type="password" required="" id="password" name="password" placeholder="Enter your password">
                            </div>

                            <div class="form-group text-center m-t-10">
                                <button class="btn btn-block btn-custom waves-effect waves-light" type="submit" name="login">Sign In</button>
                            </div>

                        </form>

                        <div class="row m-t-50">
                            <div class="col-sm-12 text-center">
                                <p class="text-muted">Don't have an account? <a href="register.php" class="text-dark m-l-5"><b>Sign Up</b></a></p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="m-t-40 text-center">
            <p class="account-copyright"><?php echo date('Y'); ?> © MuseLabs</p>
        </div>

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
