<?php
include_once ('notification_logic2.php');
$user_info = $_SESSION['user_info'] ?? null;

//-----------------------------------------------------------------------
// Check if the user is logged in, automatically go to index.php
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    $_SESSION['status'] = "Please login first.";
    exit();
}
//-----------------------------------------------------------------------


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta property="og:type" content="website">
    <meta property="og:description" content="3G Pharmacy Capstone Project" />
    <meta property="og:image" content="https://i.imgur.com/J4i9dsq.png">
    <meta name="keywords" content="3G Pharmacy, Pharmacy, MIS">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <link rel="icon" type="image/png" href="./img/3GMED.png">

    <title>3GMed | Dashboard</title>

    <!-- Custom fonts -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        /* Adjust the size of the box in the topbar */
        .navbar {
            position: fixed;
            top: 0;
            margin-left: 224px;
            /* Adjust the left position according to your sidebar width */
            width: calc(100% - 224px);
            /* Adjust width to fit the remaining space */
            height: 70px;
            background-color: #ffffff;
            z-index: 999;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
        }
    </style>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow fixed-top">
                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>


                <!-- Topbar Right Section -->
                <ul class="navbar-nav ml-auto">
                    <!-- Nav Item - Alerts -->
                    <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span
                                style="display: inline-flex; align-items: center; justify-content: center; border: 2px solid lightgray; border-radius: 50%; padding: 5px; width: 35px; height: 35px; margin-right: -25px;">
                                <i class="fas fa-bell fa-fw"></i>
                            </span>
                            <!-- Counter - Alerts -->
                            <span class="badge badge-danger badge-counter" style="margin-right: -25px;"></span>

                        </a>
                        <!-- Dropdown - Alerts -->
                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="alertsDropdown">
                            <h6 class="dropdown-header mb-2 text-center">
                                Stock Alerts
                            </h6>
                            <?php
                            $low_stock_count_query = "SELECT COUNT(*) AS low_stock_count FROM add_stock_list WHERE quantity < 20";
                            $low_stock_count_result = mysqli_query($connection, $low_stock_count_query);
                            $row = mysqli_fetch_assoc($low_stock_count_result);
                            $low_stock_count = $row['low_stock_count'];
                            if ($low_stock_count > 0) {
                                echo '<a class="dropdown-item d-flex align-items-center" href="add_stocks.php">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-capsules text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">' . date("F j, Y") . '</div>
                                    <div id="notification-container"></div>
                                    <span class="font-weight-bold">Low stock alert:</span> ' . $low_stock_count . ' products are running low.
                                </div>
                            </a>';
                            }
                            ?>
                            <?php
                            if ($expiring_soon_count > 0) {
                                // Display message for expiring soon products in stocks
                                echo '<a class="dropdown-item d-flex align-items-center" href="add_stocks.php">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-warning">
                                                <i class="fas fa-exclamation-triangle text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                        <div class="small text-gray-500">' . date("F j, Y") . '</div>
                                            <div id="notification-container"></div>
                                            <span class="font-weight-bold">Expiring Soon in Stocks:</span> ' . $expiring_soon_count . ' product(s) will expire soon in stocks.
                                        </div>
                                    </a>';
                            }

                            if ($expiring_soon_buffer_count > 0) {
                                // Display message for expiring soon products in buffer
                                echo '<a class="dropdown-item d-flex align-items-center" href="buffer_stock.php">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-warning">
                                                <i class="fas fa-exclamation-triangle text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                        <div class="small text-gray-500">' . date("F j, Y") . '</div>
                                            <div id="notification-container"></div>
                                            <span class="font-weight-bold">Expiring Soon in Buffer:</span> ' . $expiring_soon_buffer_count . ' product(s) will expire soon in buffer.
                                        </div>
                                    </a>';
                            }
                            ?>
                            <?php
                            // Check if there are expired products
                            if ($expired_count > 0) {
                                echo '<a id="expiredLink" class="dropdown-item d-flex align-items-center" href="expired_products.php">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-danger">
                                                <i class="fas fa-calendar-times text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                        <div class="small text-gray-500">' . date("F j, Y") . '</div>
                                            <div id="notification-container"></div>
                                            <span class="font-weight-bold">Expired Products:</span> ' . $expired_count . ' product(s) are expired.
                                        </div>
                                    </a>';
                            }
                            ?>
                            <?php
                            // Check if there are expired products
                            if ($out_count > 0) {
                                echo '<a id="outstockLink" class="dropdown-item d-flex align-items-center" href="out_of_stock.php">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-danger">
                                            <i class="fas fa-exclamation-circle text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                        <div class="small text-gray-500">' . date("F j, Y") . '</div>
                                            <div id="notification-container"></div>
                                            <span class="font-weight-bold">Out of Stock Products:</span> ' . $out_count . ' product(s) are out of stocks.
                                        </div>
                                    </a>';
                            }
                            ?>
                            <!-- Dropdown - Sales and Inventory Alerts 
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-success">
                                        <i class="fas fa-chart-line text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">January 5, 2022</div>
                                    <span class="font-weight-bold">Sales Report:</span> Monthly sales report is ready
                                    for review.
                                </div>
                            </a>-->
                            <!-- Dropdown - View All Alerts -->
<!-- Nav Item - Calendar -->
<li class="nav-item dropdown no-arrow mx-1">
    <a class="nav-link dropdown-toggle" href="#" id="calendarDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span
            style="display: inline-flex; align-items: center; justify-content: center; border: 2px solid lightgray; border-radius: 50%; padding: 5px; width: 35px; height: 35px; margin-left: 10px; margin-right: -10px;">
            <i class="far fa-calendar-alt fa-fw"></i>
        </span>
        <!-- Counter - Calendar -->
    </a>
    <!-- Dropdown - Calendar -->
    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
        aria-labelledby="calendarDropdown">
        <div class="text-center">
            <h6 class="dropdown-header mb-2">
                Calendar
            </h6>
            <!-- Calendar View -->
            <div id="calendarView" style="max-width: 380px; margin: 0 auto;">
                <!-- iCloud Calendar iframe -->
                <iframe src="https://calendar.google.com/calendar/embed?src=YOUR_GOOGLE_CALENDAR_ID" style="border: 0" width="100%" height="300" frameborder="0" scrolling="no"></iframe>

            </div>
        </div>
    </div>
</li>



                    <div class="topbar-divider d-none d-sm-block"></div>

                    <?php
                    $sel = "SELECT * FROM register";
                    $query = mysqli_query($connection, $sel);
                    $result = mysqli_fetch_assoc($query)
                        ?>


                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            <span class="mr-2 d-none ml-3 d-lg-inline text-600 small"
                                style="color: #44A6F1; font-weight: bold;">
                                <?php
                                // Check if user_info is set and not empty
                                if ($user_info && !empty($user_info)) {
                                    echo $user_info['first_name'] . ' ' . $user_info['mid_name'] . ' ' . $user_info['last_name'];
                                } else {
                                    // Default message if user_info is not set
                                    echo "Guest";
                                }
                                ?>
                                <br><span
                                    style="color: #59B568; font-weight: normal; margin-left: 15px;">Admin</span></span>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="activity_logs_view.php">
                                <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                Activity Log
                            </a>
                            <a class="dropdown-item" href="login.php" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- JavaScript Functions -->
            <script>
                function userFunction() {
                    // User section logic goes here
                    console.log('User section clicked');
                }

                function notificationFunction() {
                    // Notification section logic goes here
                    console.log('Notification section clicked');
                }

                function dateFunction() {
                    // Date section logic goes here
                    console.log('Date section clicked');
                }
            </script>
            <?php include ('notification_logic2.php'); ?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">