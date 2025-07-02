<!-- Top Bar Start -->
<div class="topbar">
    <nav class="navbar-custom">
        <ul class="list-unstyled topbar-right-menu float-right mb-0">
            <?php
            $ret = mysqli_query($con, "SELECT tblservicerequest.ServiceNumber, tblservicerequest.ID as apid, tbluser.FullName, tblservicerequest.ServicerequestDate FROM tblservicerequest INNER JOIN tbluser ON tbluser.ID=tblservicerequest.UserId WHERE tblservicerequest.AdminStatus IS NULL");
            $num = mysqli_num_rows($ret);
            ?>
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fi-bell noti-icon" style="color:#fff"></i>
                    <span class="badge badge-danger badge-pill noti-icon-badge"><?php echo $num; ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-lg">
                    <div class="dropdown-item noti-title">
                        <h5 class="m-0"><span class="float-right"></span>Notification</h5>
                        <hr />
                    </div>
                    <div class="slimscroll" style="max-height: 230px;">
                        <?php
                        if ($num > 0) {
                            while ($row = mysqli_fetch_array($ret)) {
                        ?>
                                <a href="view-service-request.php?aticid=<?php echo $row['apid']; ?>" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-success"><i class="mdi mdi-comment-account-outline"></i></div>
                                    <p class="notify-details">New Request Received <?php echo $row['ServiceNumber']; ?><small class="text-muted">at <?php echo $row['ServicerequestDate']; ?></small></p>
                                </a>
                        <?php }
                        } else { ?>
                            <p align="center">No Request found</p>
                        <?php } ?>
                    </div>
                    <!-- All-->
                    <a href="pending-service.php" class="dropdown-item text-center text-primary notify-item notify-all">
                        View all <i class="fi-arrow-right"></i>
                    </a>
                </div>
            </li>

            <?php
            $ret_roadside = mysqli_query($con, "SELECT roadside.Reference, roadside.ID as rid, tbluser.FullName, roadside.CreatedAt FROM roadside INNER JOIN tbluser ON tbluser.ID = roadside.UserId WHERE roadside.completed = 0");
            $num_roadside = mysqli_num_rows($ret_roadside);
            ?>
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="fi-bell noti-icon" style="color:#F00"></i>
                    <span class="badge badge-danger badge-pill noti-icon-badge"><?php echo $num_roadside; ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated dropdown-lg">
                    <div class="dropdown-item noti-title">
                        <h5 class="m-0"><span class="float-right"></span>Emergency Assistance</h5>
                        <hr />
                    </div>
                    <div class="slimscroll" style="max-height: 230px;">
                        <?php
                        if ($num_roadside > 0) {
                            while ($row = mysqli_fetch_array($ret_roadside)) {
                        ?>
                                <a href="manage-roadside.php" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-success"><i class="mdi mdi-comment-account-outline"></i></div>
                                    <p class="notify-details">New Assistance Request <?php echo $row['Reference']; ?><small class="text-muted">at <?php echo $row['CreatedAt']; ?></small></p>
                                </a>
                        <?php }
                        } else { ?>
                            <p align="center">No Request found</p>
                        <?php } ?>
                    </div>
                    <!-- All-->
                    <a href="manage-roadside.php" class="dropdown-item text-center text-primary notify-item notify-all">
                        View all <i class="fi-arrow-right"></i>
                    </a>
                </div>
            </li>

            <?php
            $adid = $_SESSION['adid'];
            $ret = mysqli_query($con, "SELECT AdminName FROM tbladmin WHERE ID='$adid'");
            $row = mysqli_fetch_array($ret);
            $name = $row['AdminName'];
            ?>
            <li class="dropdown notification-list">
                <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="../assets/images/user.png" alt="user" class="rounded-circle"> <span class="ml-1"><?php echo $name; ?> <i class="mdi mdi-chevron-down"></i> </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>
                    <!-- item-->
                    <a href="admin-profile.php" class="dropdown-item notify-item">
                        <i class="fi-head"></i> <span>My Profile</span>
                    </a>
                    <!-- item-->
                    <a href="changepassword.php" class="dropdown-item notify-item">
                        <i class="fi-cog"></i> <span>Change Password</span>
                    </a>
                    <a href="logout.php" class="dropdown-item notify-item">
                        <i class="fi-power"></i> <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>
        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left disable-btn">
                    <i class="dripicons-menu"></i>
                </button>
            </li>
        </ul>
    </nav>
</div>
<!-- Top Bar End -->
<hr color="#fff" />
