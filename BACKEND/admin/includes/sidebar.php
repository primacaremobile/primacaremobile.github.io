  <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">

                <div class="slimscroll-menu" id="remove-scroll">

                    <!-- LOGO -->
                    <div class="topbar-left">
                       <h3>EDEN | Admin  </h3>
                       <hr color="#000" />                    </div>

                    <!-- User box -->
                    <div class="user-box">
                        <div class="user-img">
                            <img src="../assets/images/user.png" alt="user-img" title="Mat Helme" class="rounded-circle img-fluid">
                        </div>

                            <?php
$adid=$_SESSION['adid'];
$ret=mysqli_query($con,"select AdminName from tbladmin where ID='$adid'");
$row=mysqli_fetch_array($ret);
$name=$row['AdminName'];

?>
                        <h5><?php echo $name; ?></a> </h5>
                        <p class="text-muted">EDEN Admin</p>
                    </div>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <ul class="metismenu" id="side-menu">

                            <!--<li class="menu-title">Navigation</li>-->

                            <li>
                                <a href="dashboard.php">
                                    <i class="fi-air-play"></i><span class="badge badge-danger badge-pill float-right"></span> <span> Dashboard </span>
                                </a>
                            </li>

                          

                            <li>
                                <a href="javascript: void(0);"><i class="fi-layers"></i><span> Locations </span> <span class="menu-arrow"></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="add-mechanics.php">Add Location</a></li>
                                    <li><a href="manage-mechanics.php">Manage Locations</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><i class="fi-layers"></i><span> Emergency Assistance </span> <span class="menu-arrow"></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                <li><a href="manage-roadside.php">Manage Emergencies</a></li>
                                    <li><a href="all-roadside.php">Emergency Records</a></li>
                                </ul>
                            </li>



  

                            <li>
                                <a href="reg-user.php">
                                  <i class="icon-people"></i> <span> Register Users </span>
                                </a>
                            </li>
                        


<li>
<a href="javascript: void(0);"><i class="fi-paper"></i><span> Service Request </span> <span class="menu-arrow"></span></a>
<ul class="nav-second-level" aria-expanded="false">
<li><a href="pending-service.php"> New </a></li>
<li><a href="rejected-services.php">Rejected</a></li>
</ul>
</li>
<li>
<a href="javascript: void(0);"><i class="fi-paper"></i><span> Servicing </span> <span class="menu-arrow"></span></a>
<ul class="nav-second-level" aria-expanded="false">
<li><a href="pending-servicing.php"> Pending</a></li>
<li><a href="completed-service.php"> Completed </a></li>
</ul>
</li>


<li>
                                <a href="javascript: void(0);"><i class="fi-paper"></i><span> Customer Enquiry </span> <span class="menu-arrow"></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="notrespond-enquiry.php"> Not Respond Enquiry</a></li>
                                    
                                     <li><a href="respond-enquiry.php"> Respond Enquiry </a></li>
                                </ul>
                            </li>


  <li>
                                <a href="search-enquiry.php">
                                    <i class="fi-air-play"></i><span class="badge badge-danger badge-pill float-right"></span> <span> Enquiry Search </span>
                                </a>
                            </li>

                             <li>
                                <a href="search-service.php">
                                    <i class="fi-air-play"></i><span class="badge badge-danger badge-pill float-right"></span> <span> Service Search </span>
                                </a>
                            </li>






                        </ul>

                    </div>
                    <!-- Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->

