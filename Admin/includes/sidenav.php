<nav class="pcoded-navbar">
    <div class="sidebar_toggle">
        <a href="#"><i class="icon-close icons"></i></a>
    </div>
    <div class="pcoded-inner-navbar main-menu">
        <ul class="pcoded-item pcoded-left-item">
            <!-- Dashboard -->
            <li class="active">
                <a href="dashboard.php">
                    <span class="pcoded-micon"><i class="ti-home"></i><b>D</b></span>
                    <span class="pcoded-mtext" data-i18n="nav.dash.main">Dashboard</span>
                </a>
            </li>

            <!-- Admin B2B -->
            <?php if($_SESSION['user_role'] == 1 || $_SESSION['user_role'] == 2){ ?>
                   <li class="pcoded-hasmenu">
                    <a href="input_cust.php">
                        <span class="pcoded-micon"><i class="ti-plus"></i></span>
                        <span class="pcoded-mtext">Input Order</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="order_data.php">
                        <span class="pcoded-micon"><i class="ti-package"></i></span>
                        <span class="pcoded-mtext">Order Data</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="track_lead.php">
                        <span class="pcoded-micon"><i class="ti-bar-chart"></i></span>
                        <span class="pcoded-mtext">Track Lead Data</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="cust_data.php">
                    <span class="pcoded-micon"><i class="ti-id-badge"></i></span>

                        <span class="pcoded-mtext">Customer Data</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="manage_progress.php">
                    <span class="pcoded-micon"><i class="ti-check-box"></i></span>

                        <span class="pcoded-mtext">Progress History</span>
                    </a>
                </li>
            <?php }?>

            <!-- Admin Inventaris -->
            <?php if($_SESSION['user_role'] == 1 || $_SESSION['user_role'] == 3){ ?>
                <li class="pcoded-hasmenu">
                    <a href="input_stock.php">
                    <span class="pcoded-micon"><i class="ti-import"></i></span>

                        <span class="pcoded-mtext">Input Stock In</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="output_stock.php">
                    <span class="pcoded-micon"><i class="ti-export"></i></span>

                        <span class="pcoded-mtext">Input Stock Out</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="manage_inventory.php">
                        <span class="pcoded-micon"><i class="ti-archive"></i></span>
                        <span class="pcoded-mtext">Inventory Data</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)" data-toggle="collapse" data-target="#stockManagement" aria-expanded="false" aria-controls="stockManagement">
                        <span class="pcoded-micon"><i class="ti-settings"></i></span>
                        <span class="pcoded-mtext">Stock Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu collapse" id="stockManagement">
                        <li>
                            <a href="stock_in.php">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext">Stock In</span>
                            </a>
                        </li>
                        <li>
                            <a href="stock_out.php">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext">Stock Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)" data-toggle="collapse" data-target="#supplier" aria-expanded="false" aria-controls="supplier">
                        <span class="pcoded-micon"><i class="ti-truck"></i></span>
                        <span class="pcoded-mtext">Supplier</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu collapse" id="supplier">
                        <li>
                            <a href="add_supplier.php">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext">Add Supplier</span>
                            </a>
                        </li>
                        <li>
                            <a href="manage_supplier.php">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext">Manage Supplier</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="manage_progress_stock.php">
                        <span class="pcoded-micon"><i class="ti-time"></i></span>
                        <span class="pcoded-mtext">Stock History</span>
                    </a>
                </li>
            <?php }?>

            <!-- Admin B2C -->
            <?php if($_SESSION['user_role'] == 1 || $_SESSION['user_role'] == 4){ ?>
                <li class="pcoded-hasmenu">
                    <a href="manage_custB2C.php">
                        <span class="pcoded-micon"><i class="ti-user"></i></span>
                        <span class="pcoded-mtext">Manage Customer B2C</span>
                    </a>
                </li>
                <li class="pcoded-hasmenu">
                    <a href="manage_orderB2C.php">
                        <span class="pcoded-micon"><i class="ti-shopping-cart-full"></i></span>
                        <span class="pcoded-mtext">Manage Order B2C</span>
                    </a>
                </li>
            <?php }?>

            <!-- Super Admin -->
            <?php if($_SESSION['user_role']==1){ ?>
                <li class="pcoded-hasmenu">
                    <a href="javascript:void(0)" data-toggle="collapse" data-target="#userManagement" aria-expanded="false" aria-controls="userManagement">
                        <span class="pcoded-micon"><i class="ti-user"></i></span>
                        <span class="pcoded-mtext">User Management</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                    <ul class="pcoded-submenu collapse" id="userManagement">
                        <li>
                            <a href="add_user.php">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext">Add User</span>
                            </a>
                        </li>
                        <li>
                            <a href="manage_user.php">
                                <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                <span class="pcoded-mtext">Manage User</span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php }?>
        </ul>
    </div>
</nav>
