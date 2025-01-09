<?php 
session_start();
    include("class/adminback.php");
    $obj= new adminback();
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    if($user_id==null){
        header("location:index.php");
    }
    

    if(isset($_GET['adminLogout'])){
        if($_GET['adminLogout']=="logout"){
            $obj->user_logout();
        }
    }
?>



<?php 
    include ("includes/head.php")
?>

  <body>
       <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="loader-bar"></div>
        </div>
    </div>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">

         <?php include_once ("includes/headernav.php") ?>


            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                   
                <?php include_once ("includes/sidenav.php") ?>


                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">

                                    <div class="page-body">
                                     
      <!-- sesuaiin sm page -->
                                <?php 
                                    if($views){
                                                                                if($views=="dashboard"){
                                            include ('views/dashboard_view.php');
                                        }elseif($views=="add-cust"){
                                            include ("views/input_order_view.php");
                                        }elseif($views=="edit-cust"){
                                            include ("views/edit_cust_view.php");
                                        } elseif ( $views == 'edit-custb2c' ) {
                                            include ( 'views/edit_custb2c_view.php' );
                                        } elseif ( $views == 'edit-orderb2c' ) {
                                            include ( 'views/edit_orderb2c_view.php' );
                                        } elseif($views=="manage-lead"){
                                            include ("views/manage_lead_view.php");
                                        }elseif($views=="order-data"){
                                            include ("views/manage_order_view.php");
                                        }elseif($views=="manage-cust"){
                                            include ("views/manage_cust_view.php");
                                        }elseif($views=="add-user"){
                                            include ("views/add_user_view.php");
                                        }elseif($views=="edit_user"){
                                             include ("views/edit_user_view.php");
                                        }elseif($views=="manage-user"){ 
                                            include ("views/manage_user_view.php");
                                        }elseif($views=="manage-inventory"){
                                            include ("views/manage_inventory_view.php");
                                        }elseif($views=="stock-in"){
                                            include ("views/stock_in_view.php");
                                        } elseif ( $views == 'edit-stockin' ) {
                                            include ( 'views/edit_stockin_view.php' );
                                            
                                        } elseif($views=="stock-out"){
                                            include ("views/stock_out_view.php");
                                        } elseif ( $views == 'edit-stockout' ) {
                                            include ( 'views/edit_stockout_view.php' );
                                            
                                        } elseif($views=="manage-progress"){
                                            include ("views/manage_progress_view.php");
                                        }elseif($views=="progress-stock"){
                                            include ("views/manage_progress_stock_view.php");
                                        }elseif($views=="input-stock"){
                                            include ("views/input_stock_view.php");
                                        }elseif($views=="edit-order"){
                                            include ("views/edit_order_view.php");
                                        }
                                        elseif($views=="output-stock"){
                                            include ("views/output_stock_view.php");
                                        }elseif($views=="manage-supplier"){
                                            include ("views/manage_supplier_view.php");
                                        }elseif($views=="add-supp"){
                                            include ("views/add_supplier_view.php");
                                        } elseif($views=="manage-custB2C"){
                                        include ("views/manage_customer_view.php");
                                        } elseif($views=="manage-orderB2C"){
                                        include ("views/manage_orderB2C_view.php");
                                        } elseif($views=="edit-supp"){
                                            include ("views/edit_supplier_view.php");
                                        }

                                    }
                                ?>


                                    <div id="styleSelector">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        </body>

        <!-- Warning Section Starts -->
        <!-- Older IE warning message -->
    <!--[if lt IE 9]>
<div class="ie-warning">
    <h1>Warning!!</h1>
    <p>You are using an outdated version of Internet Explorer, please upgrade <br/>to any of the following web browsers to access this website.</p>
    <div class="iew-container">
        <ul class="iew-download">
            <li>
                <a href="http://www.google.com/chrome/">
                    <img src="assets/images/browser/chrome.png" alt="Chrome">
                    <div>Chrome</div>
                </a>
            </li>
            <li>
                <a href="https://www.mozilla.org/en-US/firefox/new/">
                    <img src="assets/images/browser/firefox.png" alt="Firefox">
                    <div>Firefox</div>
                </a>
            </li>
            <li>
                <a href="http://www.opera.com">
                    <img src="assets/images/browser/opera.png" alt="Opera">
                    <div>Opera</div>
                </a>
            </li>
            <li>
                <a href="https://www.apple.com/safari/">
                    <img src="assets/images/browser/safari.png" alt="Safari">
                    <div>Safari</div>
                </a>
            </li>
            <li>
                <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                    <img src="assets/images/browser/ie.png" alt="">
                    <div>IE (9 & above)</div>
                </a>
            </li>
        </ul>
    </div>
    <p>Sorry for the inconvenience!</p>
</div>
<![endif]-->
<!-- Warning Section Ends -->
<!-- Required Jquery -->

<?php 
    include ("includes/script.php")
?>