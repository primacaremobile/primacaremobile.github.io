<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "vsmsdb");
if (mysqli_connect_errno()) {
    echo "Connection Fail" . mysqli_connect_error();
    exit();
}

// Leaderboard query
$query = "
    SELECT 
        r.name, 
        r.referral_code, 
        COUNT(l.referrer_code) AS total_referrals,
        MIN(l.referral_date) AS first_referral_date
    FROM 
        referrals r
    LEFT JOIN 
        referrals_log l 
    ON 
        r.referral_code = l.referrer_code
    GROUP BY 
        r.referral_code
    ORDER BY 
        total_referrals DESC, 
        first_referral_date ASC;
";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Referral Leaderboard</title>
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        .navbar a {
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
                display: block;
                color: #f2f2f2;
                text-align: center;
                font-size: 22px;
            }
        }

        @media screen and (max-width: 600px) {
            .navbar.responsive {
                flex-direction: column;
                align-items: flex-start;
            }
            .navbar.responsive a {
                display: block;
                width: 100%;
                text-align: left;
            }
        }

        /* Content Styling */
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            margin: 20px auto;
            text-align: center;
        }

        h2 {
            color: #333333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 14px;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .rank {
            font-weight: bold;
        }

        @media screen and (max-width: 600px) {
            th, td {
                font-size: 12px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Responsive Navbar -->
    <div class="navbar" id="myNavbar">
    <a href="Referrak.php">Referral</a>
        <a href="#" class="active">Leaderboard</a>
        <a href="../../index.html">Home Page</a>
        <a href="javascript:void(0);" class="icon" onclick="toggleMenu()">&#9776;</a>
    </div>

    <div class="container">
        <h2>Referral Leaderboard</h2>
        <table>
            <tr>
                <th>Rank</th>
                <th>Name</th>
                <th>Referral Code</th>
                <th>Total Referrals</th>
            </tr>
            <?php 
            if (mysqli_num_rows($result) > 0) {
                $rank = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td class='rank'>" . $rank . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['referral_code']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['total_referrals']) . "</td>";
                    echo "</tr>";
                    $rank++;
                }
            } else {
                echo "<tr><td colspan='4'>No referrals found</td></tr>";
            }
            ?>
        </table>
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
    </script>
</body>
</html>

<?php
mysqli_close($con);
?>
