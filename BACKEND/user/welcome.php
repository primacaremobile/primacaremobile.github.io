<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
} else {
    $uid = $_SESSION['sid'];

    // Fetch user information
    $query = mysqli_query($con, "SELECT FullName, Email, ReferralCode, OEMVerified, OEMVerificationStartDate FROM tbluser WHERE ID='$uid'");
    $result = mysqli_fetch_array($query);
    $userName = $result['FullName'];
    $userEmail = $result['Email'];
    $referralCode = $result['ReferralCode'];
    $oemVerified = $result['OEMVerified'];
    $oemVerificationStartDate = $result['OEMVerificationStartDate'];

    // If OEM verification hasn't started, set the start date
    if (!$oemVerificationStartDate) {
        $oemVerificationStartDate = date('Y-m-d');
        mysqli_query($con, "UPDATE tbluser SET OEMVerificationStartDate='$oemVerificationStartDate' WHERE ID='$uid'");
    }

    // Calculate progress percentage
    $currentDate = new DateTime();
    $startDate = new DateTime($oemVerificationStartDate);
    $interval = $currentDate->diff($startDate)->days;

    // Start at 20% and increase linearly to 98% over 30 days
    $progressPercentage = 30 + (68 / 30) * $interval; // 78% increase over 30 days
    if ($progressPercentage > 98) {
        $progressPercentage = 98;
    }

    // Generate a unique referral code if it doesn't exist
    if (!$referralCode) {
        $referralCode = strtoupper(bin2hex(random_bytes(4))); // Generate a random 8-character code
        mysqli_query($con, "UPDATE tbluser SET ReferralCode='$referralCode' WHERE ID='$uid'");
    }

    // Count how many times the referral code has been used
    $referralCountQuery = mysqli_query($con, "SELECT COUNT(*) as ReferralCount FROM tbluser WHERE ReferredBy='$referralCode'");
    $referralCountResult = mysqli_fetch_array($referralCountQuery);
    $referralCount = $referralCountResult['ReferralCount'];

        // Fetch service requests and their approval status
        $serviceQuery = mysqli_query($con, "SELECT EngineRepairsApproved, PaintJobsApproved, HighCostWorksApproved FROM tblservicerequest WHERE UserId='$uid'");
        $serviceStatus = mysqli_fetch_array($serviceQuery);
    
        $engineRepairsApproved = $serviceStatus['EngineRepairsApproved'];
        $paintJobsApproved = $serviceStatus['PaintJobsApproved'];
        $highCostWorksApproved = $serviceStatus['HighCostWorksApproved'];
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>VSMS</title>
        <!-- App css -->
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
                                    <h4 class="header-title mb-4">Welcome back, <?php echo $userName; ?>!</h4>
                                    <button id="roadside-assistance-btn" class="btn btn-primary mt-4">Emergency Assistance</button>
                                    <div class="row">
                                        <div class="col-sm-6 col-lg-6 col-xl-3 mb-4">
                                            <div class="card-box widget-chart-two">
                                                <div class="float-right">
                                                    <?php
                                                    $query3 = mysqli_query($con, "Select ID from tblservicerequest where UserId='$uid'");
                                                    $sercount = mysqli_num_rows($query3);
                                                    ?>
                                                    <input data-plugin="knob" data-width="80" data-height="80" data-linecap="round" data-fgColor="#2d7bf4" value="<?php echo $sercount; ?>" data-skin="tron" data-angleOffset="180" data-readOnly="true" data-thickness=".2" />
                                                </div>
                                                <div class="widget-chart-two-content">
                                                    <p class="text-muted mb-0 mt-2">Total Service Requests</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-6 col-xl-3 mb-4">
                                            <div class="card-box widget-chart-two">
                                                <div class="float-right">
                                                    <?php
                                                    $query31 = mysqli_query($con, "Select ID from tblservicerequest where AdminStatus is null and UserId='$uid'");
                                                    $newrequest = mysqli_num_rows($query31);
                                                    ?>
                                                    <input data-plugin="knob" data-width="80" data-height="80" data-linecap="round" data-fgColor="#2d7bf4" value="<?php echo $newrequest; ?>" data-skin="tron" data-angleOffset="180" data-readOnly="true" data-thickness=".2" />
                                                </div>
                                                <p class="text-muted mb-0 mt-2">New Service Requests</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-6 col-xl-3 mb-4">
                                            <div class="card-box widget-chart-two">
                                                <div class="float-right">
                                                    <?php
                                                    $query32 = mysqli_query($con, "Select ID from tblservicerequest where AdminStatus='2' and UserId='$uid'");
                                                    $rejectedrequest = mysqli_num_rows($query32);
                                                    ?>
                                                    <input data-plugin="knob" data-width="80" data-height="80" data-linecap="round" data-fgColor="#f1556c" value="<?php echo $rejectedrequest; ?>" data-skin="tron" data-angleOffset="180" data-readOnly="true" data-thickness=".2" />
                                                </div>
                                                <div class="widget-chart-two-content">
                                                    <p class="text-muted mb-0 mt-2">Rejected Service Requests</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- New Referral Code Widget -->
                                        <div class="col-sm-6 col-lg-6 col-xl-3 mb-4">
                                            <div class="card-box widget-chart-two">
                                                <div class="float-right">
                                                    <h5><?php echo $referralCode; ?></h5>
                                                </div>
                                                <div class="widget-chart-two-content">
                                                    <p class="text-muted mb-0 mt-2">Your Referral Code</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Referral Count Widget -->
                                        <div class="col-sm-6 col-lg-6 col-xl-3 mb-4">
                                            <div class="card-box widget-chart-two">
                                                <div class="float-right">
                                                    <input data-plugin="knob" data-width="80" data-height="80" data-linecap="round" data-fgColor="#0acf97" value="<?php echo $referralCount; ?>" data-skin="tron" data-angleOffset="180" data-readOnly="true" data-thickness=".2" />
                                                </div>
                                                <div class="widget-chart-two-content">
                                                    <p class="text-muted mb-0 mt-2">Referral Count</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-6 col-xl-3 mb-4">
                                            <div class="card-box widget-chart-two">
                                                <div class="float-right">
                                                    <?php
                                                    $query33 = mysqli_query($con, "Select ID from tblservicerequest where AdminStatus='3' and UserId='$uid'");
                                                    $compsercount = mysqli_num_rows($query33);
                                                    ?>
                                                    <input data-plugin="knob" data-width="80" data-height="80" data-linecap="round" data-fgColor="#0acf97" value="<?php echo $compsercount; ?>" data-skin="tron" data-angleOffset="180" data-readOnly="true" data-thickness=".2" />
                                                </div>
                                                <div class="widget-chart-two-content">
                                                    <p class="text-muted mb-0 mt-2">Completed Services</p>
                                                </div>
                                            </div>
                                        </div>
                                                                                <!-- New Service Approval Widgets -->
                                                                                <div class="col-sm-6 col-lg-6 col-xl-3 mb-4">
                                            <div class="card-box widget-chart-two">
                                                <div class="float-right">
                                                    <?php if ($engineRepairsApproved) { ?>
                                                        <span style="color: green;">✔️</span>
                                                    <?php } else { ?>
                                                        <span style="color: red;">❌</span>
                                                    <?php } ?>
                                                </div>
                                                <div class="widget-chart-two-content">
                                                    <p class="text-muted mb-0 mt-2">PickUp Approved</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-lg-6 col-xl-3 mb-4">
                                            <div class="card-box widget-chart-two">
                                                <div class="float-right">
                                                    <?php if ($paintJobsApproved) { ?>
                                                        <span style="color: green;">✔️</span>
                                                    <?php } else { ?>
                                                        <span style="color: red;">❌</span>
                                                    <?php } ?>
                                                </div>
                                                <div class="widget-chart-two-content">
                                                    <p class="text-muted mb-0 mt-2">Delivery Approved</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-6 col-lg-6 col-xl-3 mb-4">
                                            <div class="card-box widget-chart-two">
                                                <div class="float-right">
                                                    <?php if ($highCostWorksApproved) { ?>
                                                        <span style="color: green;">✔️</span>
                                                    <?php } else { ?>
                                                        <span style="color: red;">❌</span>
                                                    <?php } ?>
                                                </div>
                                                <div class="widget-chart-two-content">
                                                    <p class="text-muted mb-0 mt-2">Location(s) Assigned</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- container -->
                </div> <!-- content -->
                <?php include_once('includes/footer.php'); ?>
            </div>
        </div>
        <!-- END wrapper -->
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/metisMenu.min.js"></script>
        <script src="../assets/js/waves.js"></script>
        <script src="../assets/js/jquery.slimscroll.js"></script>
        <script src="../plugins/jquery-knob/jquery.knob.js"></script>
        <script src="../assets/pages/jquery.dashboard.init.js"></script>
        <script src="../assets/js/jquery.core.js"></script>
        <script src="../assets/js/jquery.app.js"></script>

        <script>
    $(document).ready(function() {
        <?php if ($oemVerified == 0) { ?>
            // Disable scrolling and interactions
            $("body").css({
                "overflow": "hidden",
                "pointer-events": "none"
            });

            // Create a background overlay
            $("body").append(`
                <div id="oem-overlay" style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.6);
                    z-index: 999;
                "></div>
            `);

            // Create the popup
            $("body").append(`
                <div id="oem-popup" style="
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: #fff;
                    padding: 30px;
                    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
                    border-radius: 12px;
                    z-index: 1000;
                    text-align: center;
                    width: 90%;
                    max-width: 500px;
                    font-family: 'Arial', sans-serif;
                    animation: fadeIn 0.3s ease-in-out;
                ">
                    <h2 style="
                        color: #2d7bf4;
                        font-size: 24px;
                        margin-bottom: 15px;
                        font-weight: bold;
                    ">Verification in Progress 🚀</h2>

                    <p style="
                        color: #333;
                        font-size: 16px;
                        line-height: 1.6;
                        margin-bottom: 20px;
                    "> 
                        Hey, your account is currently under verification. This process takes just <b>2-5 minutes</b>. ⏳  
                        <br/><br/>
                        💡 <b>In the meantime...</b><br/> 
                        Did you know you have a referral code on your dashboard? 🎉 <br/>
                        Earn <b>₦2,000</b> for every friend who signs up using your code! More referrals = More rewards! 💰  
                    </p>

                    <p style="color: #2d7bf4; font-weight: bold; margin-bottom: 10px;">
                        ✅ Sit tight, You'll be laundry-free soon. Just refresh.<br/>
                        ✅ Start referring & earning as soon as you’re in!
                    </p>

                    <div class="progress" style="
                        height: 10px; 
                        width: 100%; 
                        background: #e0e0e0; 
                        border-radius: 6px; 
                        overflow: hidden; 
                        margin-top: 15px;
                    ">
                        <div class="progress-bar" style="
                            width: <?php echo $progressPercentage; ?>%; 
                            background: #2d7bf4;
                            height: 100%;
                            transition: width 0.5s ease-in-out;
                        "></div>
                    </div>

                    <p style="
                        margin-top: 10px;
                        font-size: 14px;
                        color: #666;
                    "><?php echo round($progressPercentage); ?>% completed</p>
                </div>
            `);
        <?php } ?>
    });
</script>

        <script>
            $(document).ready(function() {
                $('#roadside-assistance-btn').on('click', function() {
                    var confirmation = confirm("Do you require emergency assistance?");
                    if (confirmation) {
                        var paystackHandler = PaystackPop.setup({
                            key: 'pk_live_5f543881987f266003e7b6fb4e01fbf974e51888', // Replace with your Paystack public key
                            email: '<?php echo $userEmail; ?>', // User's email fetched from the database
                            amount: 2500 * 100, // Amount in kobo
                            currency: 'NGN', // Currency
                            callback: function(response) {
                                // Payment successful, store the response and reference
                                $.ajax({
                                    url: 'store_response.php',
                                    method: 'POST',
                                    data: {
                                        response: 'yes',
                                        reference: response.reference
                                    },
                                    success: function(data) {
                                        alert('Payment successful and Assistance Dispatched Soon.');
                                    }
                                });
                            },
                            onClose: function() {
                                alert('Payment window closed.');
                            }
                        });
                        paystackHandler.openIframe();
                    }
                });
            });
        </script>
        <script src="https://js.paystack.co/v1/inline.js"></script>
    </body>
</html>
<?php } ?>
