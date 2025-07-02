<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
} else {
    $uid = $_SESSION['sid'];

    // Fetch user state
    $query = mysqli_query($con, "SELECT State FROM tbluser WHERE id='$uid'");
    $user = mysqli_fetch_array($query);
    $userState = $user['State']; 

    if (isset($_POST['submit'])) {
        $servicedate = $_POST['servicedate'];
        $servicetime = $_POST['servicetime'];
        $servicelocation = !empty($_POST['servicelocation']) ? $_POST['servicelocation'] : $userState;
        $problemdescription = $_POST['problemdescription'];
        $sernumber = mt_rand(100000000, 999999999);

        // Insert main service request
        $query = mysqli_query($con, "INSERT INTO tblservicerequest (UserId, ServiceNumber, ServiceDate, ServiceTime, ServiceLocation, ProblemDescription) 
                                    VALUES ('$uid', '$sernumber', '$servicedate', '$servicetime', '$servicelocation', '$problemdescription')");

        if ($query) {
            $requestId = mysqli_insert_id($con); // Get the last inserted request ID

            // Insert Clothing Categories
            if (!empty($_POST['clothing'])) {
                foreach ($_POST['clothing'] as $category => $quantity) {
                    if (!empty($quantity) && is_numeric($quantity)) {
                        mysqli_query($con, "INSERT INTO tblclothingrequest (RequestId, Category, Quantity) 
                                            VALUES ('$requestId', '$category', '$quantity')");
                    }
                }
            }

            echo "<script>alert('Data has been added successfully.');</script>";
            echo "<script>window.location.href ='service-request.php'</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    }
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Clothing Service Request</title>

    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/css/style.css" rel="stylesheet" type="text/css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        .category-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
            transition: 0.3s;
        }

        .category-image.selected {
            border: 2px solid #007bff;
            border-radius: 10px;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            display: none;
            margin-top: 5px;
        }
    </style>
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
                                <h4 class="m-t-0 header-title">Clothing Service Request</h4>

                                <div class="p-20">
                                    <form class="form-horizontal" method="post">

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PickUp Date</label>
                                            <div class="col-sm-10">
                                                <input type="date" class="form-control" name="servicedate" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PickUp Time</label>
                                            <div class="col-sm-10">
                                                <input type="time" class="form-control" name="servicetime" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PickUp Location</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="servicelocation" value="<?php echo htmlspecialchars($userState); ?>" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Special Note (if any)</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" name="problemdescription"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Select Clothing Items</label>
                                            <div class="col-sm-10">
                                                <div class="row">
                                                    <?php
                                                    $categories = [
                                                        'Tops' => 'top.png',
                                                        'Trousers' => 'trousers.png',
                                                        'Shorts' => 'shorts.png',
                                                        'Dresses' => 'dress.png',
                                                        'Others' => 'wardrobe.png'
                                                    ];
                                                    foreach ($categories as $category => $image) {
                                                        echo '
                                                        <div class="col-md-2 text-center">
                                                            <img src="../assets/images/' . $image . '" class="category-image" onclick="selectCategory(\'' . $category . '\')" id="img_' . $category . '">
                                                            <p>' . $category . '</p>
                                                            <input type="number" name="clothing[' . $category . ']" id="qty_' . $category . '" class="form-control quantity-input" min="1" max="50" placeholder="Qty">
                                                        </div>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12 text-center">
                                                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> 
            </div>

            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <script>
        function selectCategory(category) {
            let imgElement = document.getElementById('img_' + category);
            let inputField = document.getElementById('qty_' + category);
            
            if (inputField.style.display === 'none') {
                inputField.style.display = 'block';
                imgElement.classList.add('selected');
            } else {
                inputField.style.display = 'none';
                inputField.value = "";
                imgElement.classList.remove('selected');
            }
        }
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector("form").addEventListener("submit", function (event) {
            let totalQuantity = 0;
            document.querySelectorAll(".quantity-input").forEach(input => {
                let value = parseInt(input.value) || 0;
                totalQuantity += value;
            });
            
            if (totalQuantity > 30) {
                alert("Total quantity of clothing items cannot exceed 30.");
                event.preventDefault(); // Prevent form submission
            }
        });
    });
</script>


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
<?php } ?>
