<?php
session_start();
include('includes/dbconnection.php');

if(isset($_POST['submit'])) {
    $fname = $_POST['fullname'];
    $mobno = $_POST['mobilenumber'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $referral = $_POST['referralcode']; // Retrieve referral code

    // Check if the mobile number starts with "0"
    if(substr($mobno, 0, 1) == '0') {
        // Prepend "234" to the mobile number
        $mobno = '234' . substr($mobno, 1);
    }

    // Determine the registration fee based on referral code
    $registrationFee = ($referral == 'palmer') ? 8224 : 10254;

    // Initialize Paystack payment
    $curl = curl_init();
    $amount = $registrationFee * 100; // Amount in kobo

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode([
            'amount' => $amount,
            'email' => $email,
            'callback_url' => 'http://yourdomain.com/paystack_callback.php',
        ]),
        CURLOPT_HTTPHEADER => [
            "authorization: Bearer sk_test_69fbfe08e47e8abd1603588b3a7c796753a5d363",
            "content-type: application/json",
            "cache-control: no-cache"
        ],
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if($err){
        // there was an error contacting the Paystack API
        $msg = 'Curl returned error: ' . $err;
        header('Location: registration.php?error=' . urlencode($msg));
        exit();
    }

    $tranx = json_decode($response, true);

    if(!$tranx['status']){
        // there was an error from the API
        $msg = 'API returned error: ' . $tranx['message'];
        header('Location: registration.php?error=' . urlencode($msg));
        exit();
    }

    // Store Paystack reference in session
    $_SESSION['paystackRef'] = $tranx['data']['reference'];

    // Redirect to Paystack payment page
    header('Location: ' . $tranx['data']['authorization_url']);
    exit();
}
?>
