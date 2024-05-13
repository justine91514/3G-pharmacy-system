<?php 
include_once('notification_logic2.php');
$user_info = $_SESSION['user_info'] ?? null; 
$branch = $user_info['branch'] ?? "Default Branch"; // Default branch kung wala
// Kunin ang halaga ng branch filter
$selected_branch = isset($_POST['branch_filter']) ? $_POST['branch_filter'] : $user_info['branch']; // Default to user's branch if not set

// Construct the SQL query with branch filter
$query = "SELECT * FROM your_table WHERE branch = '$selected_branch'";
// Execute the query and display the results in the table

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Cashier</title>
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
                        <!-- Add these links if not included -->
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

                        <!-- Nav Item - Calendar -->
                        <li class="nav-item dropdown no-arrow mx-1">
                        <a class="nav-link dropdown-toggle" href="#" id="calendarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span style="display: inline-flex; align-items: center; justify-content: center; border: 2px solid lightgray; border-radius: 50%; padding: 5px; width: 35px; height: 35px; margin-left: 10px; margin-right: -10px;">
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
                                <input type="text" id="datepicker" placeholder="2024-01-24">
                            </div>
                            <div id="anotherCalendar" class="text-center" style="max-width: 380px; margin: 0 auto;"></div>
                        </div>
                        </li>
                        <!-- Initialize flatpickr and Another Calendar -->
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <?php
                            $sel = "SELECT * FROM register";
                            $query = mysqli_query($connection,$sel);
                            $result = mysqli_fetch_assoc($query)
                        ?>
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="img-profile rounded-circle"
                                src="img/undraw_profile.svg">
                                <span class="mr-2 d-none ml-3 d-lg-inline text-600 small" style="color: #44A6F1; font-weight: bold;">
                                <?php 
                                // Check if user_info is set and not empty
                                if($user_info && !empty($user_info)) {
                                    echo $user_info['first_name'] . ' ' . $user_info['mid_name'] . ' ' . $user_info['last_name'];
                                    // Check if branch information exists
                                    if(isset($user_info['branch']) && !empty($user_info['branch'])) {
                                        // Display branch information
                                        echo '<br><span style="color: #59B568; font-weight: normal; margin-left: 15px;">' . $user_info['branch'] . '</span>';
                                    } else {
                                        // Display default branch information if not available
                                        echo '<br><span style="color: #59B568; font-weight: normal; margin-left: 15px;">Default Branch</span>';
                                    }
                                } else {
                                    // Default message if user_info is not set
                                    echo "Guest";
                                }
                                ?>
                                <br><span style="color: #59B568; font-weight: normal; margin-left: 15px;">Pharmacy Assistant</span></span>
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="index.php" data-toggle="modal" data-target="#logoutModal">
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

<script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Initialize flatpickr
                            const datepicker = flatpickr("#datepicker", {
                                dateFormat: "Y-m-d",
                                onChange: function (selectedDates, dateStr, instance) {
                                    // Handle date selection here if needed
                                    updateSelectedDateText(dateStr);
                                }
                            });

                            $('#calendarDropdown').on('show.bs.dropdown', function () {
                                // Open the flatpickr datepicker when the dropdown is shown
                                datepicker.open();
                            });

                            // Initialize Another Calendar
                            $('#anotherCalendar').fullCalendar({
                                header: {
                                    left: 'prev,next today',
                                    center: 'title',
                                    right: 'month,agendaWeek,agendaDay'
                                },
                                defaultView: 'month',
                                events: [
                                    {
                                        title: 'Event 1',
                                        start: '2024-02-01'
                                    },
                                    {
                                        title: 'Event 2',
                                        start: '2024-02-10'
                                    },
                                    // Add more events as needed
                                ],
                                dayClick: function (date) {
                                    // Update the flatpickr datepicker when a day is clicked
                                    datepicker.setDate(date.toDate());
                                    updateSelectedDateText(datepicker.input.value);
                                }
                            });

                            // Update the text in the calendar dates dropdown
                            function updateSelectedDateText(dateStr) {
                                // You can handle the selected date here if needed
                            }
                        });
                        </script>
                        
<?php include('notification_logic2.php'); ?>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

    