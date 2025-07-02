<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "vsmsdb");
if (mysqli_connect_errno()) {
    echo "Connection Fail" . mysqli_connect_error();
    exit();
}

// Initialize a variable to store the referral code
$referral_code = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $location = mysqli_real_escape_string($con, $_POST['location']);

    // Check if the email already exists in the database
    $query = "SELECT referral_code FROM referrals WHERE email='$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        // Email exists, retrieve the referral code
        $row = mysqli_fetch_assoc($result);
        $referral_code = $row['referral_code'];
    } else {
        // Email does not exist, generate a new referral code
        $referral_code = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        // Insert the referral data into the database
        $query = "INSERT INTO referrals (name, email, location, referral_code) VALUES ('$name', '$email', '$location', '$referral_code')";
        if (!mysqli_query($con, $query)) {
            echo "Error: " . mysqli_error($con);
            $referral_code = ""; // Clear referral code if there's an error
        }
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Program</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Responsive Navbar CSS */
        .navbar {
            overflow: hidden;
            background-color: #333;
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .navbar a.active {
            background-color: #4CAF50;
            color: white;
        }

        .navbar .icon {
            display: none;
        }

        @media screen and (max-width: 600px) {
            .navbar a:not(:first-child) {
                display: none;
            }
            .navbar a.icon {
                float: right;
                display: block;
            }
        }

        @media screen and (max-width: 600px) {
            .navbar.responsive {
                position: relative;
            }
            .navbar.responsive .icon {
                position: absolute;
                right: 0;
                top: 0;
            }
            .navbar.responsive a {
                float: none;
                display: block;
                text-align: left;
            }
        }

        /* Content Styling */
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 90%;
            margin: 50px auto;
            text-align: center;
        }

        h2 {
            color: #333333;
            margin-bottom: 20px;
        }

        label {
            color: #555555;
            font-size: 14px;
            display: block;
            margin-bottom: 5px;
            text-align: left;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: #ffffff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .referral-code {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
            font-size: 18px;
            color: #555555;
        }

        /* Enhanced Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6); /* Darker background */
        }

        .modal-content {
            background-color: #ffffff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); /* Enhanced shadow for depth */
            text-align: left; /* Left-align the content */
        }

        .modal-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #dddddd; /* Subtle border for separation */
            padding-bottom: 10px;
        }

        .modal-close {
            float: right;
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
        }

        .modal-close:hover,
        .modal-close:focus {
            color: #000;
            text-decoration: none;
        }

        .modal-body {
            margin-top: 15px;
            font-size: 16px;
            line-height: 1.6;
            color: #444444;
        }

        .modal-body ul {
            list-style-type: none;
            padding-left: 0;
        }

        .modal-body ul li {
            padding: 8px 0;
        }

        .modal-body ul li::before {
            content: "•";
            color: #28a745;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }

        .modal-body a {
            color: #007bff;
            text-decoration: none;
        }

        .modal-body a:hover {
            text-decoration: underline;
        }

        .modal-footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Responsive Navbar -->
    <div class="navbar" id="myNavbar">
        <a href="#" class="active">Referral</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="../../index.html">Home Page</a>
        <a href="javascript:void(0);" class="icon" onclick="toggleMenu()">
            &#9776;
        </a>
    </div>

    <div class="container">
        <h2>Referral Program</h2>
        <?php if ($referral_code): ?>
            <p>Thank you for your submission!</p>
            <div class="referral-code">
                Your referral code is: <strong><?php echo $referral_code; ?></strong>
            </div>
        <?php else: ?>
            <form action="" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <input type="submit" value="Submit">
            </form>
        <?php endif; ?>
        <!-- Modal Trigger Button -->
        <button onclick="openModal()">REFERRAL DETAILS</button>
    </div>

    <!-- The Modal -->
    <div id="referralModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <div class="modal-header">Quick Summary for Referral Participants</div>
            <div class="modal-body">
                <p>PrimaCare is a subscription-based mobile auto mechanic service designed to offer convenient and reliable car maintenance directly to customers. For ₦15,000 per month, subscribers receive a wide range of services, from routine maintenance to major repairs. The service is mobile, meaning the mechanics come to you, with operations in Abuja and Lagos.</p>
                <ul>
                    <li><strong>Convenient Mobile Service:</strong> Mechanics come to your location.</li>
                    <li><strong>Comprehensive Maintenance:</strong> Covers all car issues except for accidents and insurance claims.</li>
                    <li><strong>Subscription Plan:</strong> ₦15,000 per month with special terms for new subscribers.</li>
                    <li><strong>Multi-Vehicle Option:</strong> Each vehicle requires its own plan.</li>
                </ul>
                <p>To learn more, visit the FAQ page on our website or join our WhatsApp group using this link: <a href="https://chat.whatsapp.com/LS8ndz82AbZBg6Ysyfl3Ph" target="_blank">Join WhatsApp Group</a>.</p>
            </div>
        </div>
    </div>

    <script>
        function toggleMenu() {
            var x = document.getElementById("myNavbar");
            if (x.className === "navbar") {
                x.className += " responsive";
            } else {
                x.className = "navbar";
            }
        }

        function openModal() {
            document.getElementById("referralModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("referralModal").style.display = "none";
        }
    </script>
</body>
</html>
