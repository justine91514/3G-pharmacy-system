<?php
$dataPoints = array(
    array("label" => "Product 1", "y" => 1000, "color" => "#00b0f0"),
    array("label" => "Product 2", "y" => 2000, "color" => "#92d050"),
    array("label" => "Product 3", "y" => 1500, "color" => "#ffc000"),
    array("label" => "Product 4", "y" => 3000, "color" => "#ff6dd9"),
    array("label" => "Product 5", "y" => 2500, "color" => "#9966ff")
);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

    <title>3GMed | Admin</title>

    <!-- Add your CSS styles and DataTables CSS CDN link here -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="need.css">
    <style>
        .container-fluid {
            margin-top: 50px;
            margin-bottom: 100px;
            margin-right: 100px;
        }

        .card {
            margin-top: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #259E9E;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }

        .card-body {
            padding: 20px;
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Import Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Import CanvasJS library -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <!-- Import Chart.js -->
    <!-- Import Chart.js plugin for data labels -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

</head>

<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include ('includes/header.php');
    include ('includes/navbar2.php');
    
    ?>

    <div class="card-body">

        <!-- Content Row -->
        <div class="row">


            <!-- Total Users -->
            <div class="col-xl-3 col-md-6 mb-3" style="margin-top: 70px;">
                <a href="register.php" style="text-decoration: none; color: inherit;">
                    <div class="card border-left-blue shadow"
                        style="font-family: Arial, sans-serif; margin: 0; border-left: 5px solid #259E9E;">
                        <div class="card-body" style="padding: 10px;">
                            <div class="h5 mb-0 font-weight-bold text-gray-800"
                                style="font-size: 1rem; margin-bottom: 0;">
                                <?php
                                require 'dbconfig.php';

                                // Query for total admins
                                $query_admin = "SELECT COUNT(id) AS total_admins FROM register WHERE usertype = 'admin'";
                                $query_run_admin = mysqli_query($connection, $query_admin);
                                $row_admin = mysqli_fetch_assoc($query_run_admin);
                                $total_admins = $row_admin['total_admins'];

                                // Query for total pharmacy assistants
                                $query_pharmacy = "SELECT COUNT(id) AS total_pharmacy_assistants FROM register WHERE usertype = 'pharmacy_assistant'";
                                $query_run_pharmacy = mysqli_query($connection, $query_pharmacy);
                                $row_pharmacy = mysqli_fetch_assoc($query_run_pharmacy);
                                $total_pharmacy_assistants = $row_pharmacy['total_pharmacy_assistants'];

                                echo '<p style="font-size: 1.45rem; margin-bottom: 0;"><strong>Admins:</strong> <strong>' . $total_admins . '</strong></p>';
                                echo '<p style="font-size: 1.45rem; margin-bottom: 0;"><strong>Assistants:</strong> <strong>' . $total_pharmacy_assistants . '</strong></p>';
                                ?>
                            </div>
                        </div>
                        <div class="card-footer bg-blue text-white" style="padding: 0.5rem; background-color: #259E9E;">
                            <i class="fas fa-users fa-2x" style="margin-right: 5px; color: #fff;"></i>
                            <span>Total Users</span>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-xl-3 col-md-6 mb-3" style="margin-top: 70px;">
                <a href="product.php" style="text-decoration: none; color: inherit;">
                    <div class="card border-left-green shadow"
                        style="font-family: Arial, sans-serif; margin: 0; border-left: 5px solid #304B1B;">
                        <div class="card-body" style="padding: 10px;">
                            <div class="h5 mb-0 font-weight-bold text-gray-800"
                                style="font-size: 1rem; margin-bottom: 0;">
                                <?php
                                $query_products = "SELECT COUNT(id) AS total_products FROM product_list";
                                $query_run_products = mysqli_query($connection, $query_products);
                                $row_products = mysqli_fetch_assoc($query_run_products);
                                echo '<p style="font-size: 3rem; margin-bottom: 0;">' . $row_products['total_products'] . '</p>';
                                ?>
                            </div>
                        </div>
                        <div class="card-footer bg-green text-white"
                            style="padding: 0.5rem; background-color: #304B1B;">
                            <i class="fas fa-box-open fa-2x" style="margin-right: 5px; color: #fff;"></i>
                            <span>Total Products</span>
                        </div>
                    </div>
                </a>
            </div>


            <div class="col-xl-3 col-md-6 mb-3" style="margin-top: 70px;">
                <a href="add_category.php" style="text-decoration: none; color: inherit;">
                    <div class="card border-left-orange shadow"
                        style="font-family: Arial, sans-serif; margin: 0; border-left: 5px solid #e3a534;">
                        <!-- lighter shade of orange -->
                        <div class="card-body" style="padding: 10px;">
                            <div class="h5 mb-0 font-weight-bold text-gray-800"
                                style="font-size: 1rem; margin-bottom: 0;">
                                <?php
                                $query_categories = "SELECT COUNT(id) AS total_categories FROM category_list";
                                $query_run_categories = mysqli_query($connection, $query_categories);
                                $row_categories = mysqli_fetch_assoc($query_run_categories);
                                echo '<p style="font-size: 3rem; margin-bottom: 0;">' . $row_categories['total_categories'] . '</p>';
                                ?>
                            </div>
                        </div>
                        <div class="card-footer bg-orange text-white"
                            style="padding: 0.5rem; background-color: #e3a534;">
                            <i class="fas fa-tags fa-2x" style="margin-right: 5px; color: #fff;"></i>
                            <span>Total Categories</span>
                        </div>
                    </div>
                </a>
            </div>


            <!-- Total Suppliers -->
            <div class="col-xl-3 col-md-6 mb-3" style="margin-top: 70px;">
                <a href="add_supplier.php" style="text-decoration: none; color: inherit;">
                    <div class="card border-left-gray shadow"
                        style="font-family: Arial, sans-serif; margin: 0; border-left: 5px solid gray;">
                        <div class="card-body" style="padding: 10px;">
                            <div class="h5 mb-0 font-weight-bold text-gray-800"
                                style="font-size: 1rem; margin-bottom: 0;">
                                <?php
                                $query_suppliers = "SELECT COUNT(id) AS total_suppliers FROM supplier_list";
                                $query_run_suppliers = mysqli_query($connection, $query_suppliers);
                                $row_suppliers = mysqli_fetch_assoc($query_run_suppliers);
                                echo '<p style="font-size: 3rem; margin-bottom: 0;">' . $row_suppliers['total_suppliers'] . '</p>';
                                ?>
                            </div>
                        </div>
                        <div class="card-footer bg-gray text-white" style="padding: 0.5rem; background-color: gray;">
                            <i class="fas fa-truck fa-2x" style="margin-right: 5px; color: #fff;"></i>
                            <span>Total Suppliers</span>
                        </div>
                    </div>
                </a>
            </div>


        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-0">
                        <div class="text-xs font-weight-bold mb-1"
                            style="font-size: 2.1rem; color: #dc3545; font-family: Arial; padding: 20px; margin-left: 10px;">
                            Critical Level
                        </div>
                        <div class="table-responsive" style="margin-bottom: -15px;">
                            <table class="table" id="criticalLevelTable">
                                <thead style="font-size: 1.2rem; font-weight: bold; font-family: Arial;">
                                    <tr>
                                        <th>Item No.</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Critical Alert</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Your database connection
                                    // Assuming $connection is your database connection
                                    
                                    // Fetch first five products from the database
                                    $query = "SELECT * FROM add_stock_list ORDER BY quantity ASC LIMIT 5";
                                    $query_run = mysqli_query($connection, $query);
                                    

                                    // Counter for item number
                                    $itemNo = 1;

                                    if (mysqli_num_rows($query_run) > 0) {
                                        while ($row = mysqli_fetch_assoc($query_run)) {
                                            // Check if quantity is less than or equal to 30
                                            $quantity = $row['quantity'];
                                            $productName = $row['product_stock_name'];

                                            // Determine critical alert level based on the quantity
                                            if ($quantity >= 31) {
                                                $alertLevel = 'Normal'; // Green
                                            } elseif ($quantity >= 21) {
                                                $alertLevel = '50%'; // Orange
                                            } elseif ($quantity >= 11) {
                                                $alertLevel = '75%'; // Orange
                                            } else {
                                                $alertLevel = '100%'; // Red
                                            }

                                            // Output table row for product
                                            echo '<tr>';
                                            echo '<td>' . $itemNo . '</td>'; // Item number
                                            echo '<td>' . $productName . '</td>'; // Product name
                                            echo '<td>' . $quantity . '</td>'; // Quantity
                                            echo '<td><div style="border: 2px solid ';
                                            echo ($alertLevel == 'Normal') ? '#28a745' : (($alertLevel == '50%') ? '#ffc107' : '#dc3545');
                                            echo '; color: ';
                                            echo ($alertLevel == 'Normal') ? '#28a745' : (($alertLevel == '50%') ? '#ffc107' : '#dc3545');
                                            echo '; border-radius: 8px; padding: 3px; display: inline-block;">';
                                            echo $alertLevel;
                                            echo '</div></td>'; // Critical Alert
                                            echo '</tr>';

                                            $itemNo++; // Increment item number
                                        }
                                    } else {
                                        echo '<tr><td colspan="4">No products found.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Add event listener to each row in the table
                document.querySelectorAll('#criticalLevelTable tbody tr').forEach(row => {
                    row.addEventListener('click', function () {
                        // Redirect to add_stock.php
                        window.location.href = 'add_stocks.php';
                    });
                });
            </script>
            <!-- pie chart -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold"
                            style="font-size: 2.7rem; color: red; font-family: Arial; padding: 20px; margin-left: 10px; margin-bottom: 50px; margin-top: -10px; color: #304B1B;">
                            Top Selling Products</h5>
                        <div id="chartContainer" style="height: 300px; width: 100%; margin-top: -30px;"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="container-fluid">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-xs font-weight-bold mb-1"
                                style="color: #259E9E; font-family: Arial; padding: 20px; margin-left: 10px;">
                                <span style="font-size: 1.5rem;">Total Sale</span>
                                <?php
                                // Replace this line with your PHP logic to calculate total sales
                                $totalSales = "P11,347"; // Example total sales (replace with actual calculation)
                                echo "<div style=\"font-size: 2.5rem;\">$totalSales</div>"; // Adjusted font size for the total sales value
                                ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-right" style="margin-top: 40px; margin-right: 30px">
                               
                                <label class="switch">
                                    <input id="chartToggleButton" type="checkbox" onclick="toggleChartType()">
                                    <span class="slider round">
                                        <span class="chart-icon-container left" onclick="selectLineChart()">
                                            
                                            <span id="lineIcon" class="chart-icon fas fa-chart-line"></span>
                                        </span>
                                        <span class="icon-separator"></span>
                                        <span class="chart-icon-container right" onclick="selectBarChart()">
                                            
                                            <span id="barIcon" class="chart-icon fas fa-chart-bar"></span>
                                        </span>
                                    </span> 

                                </label>
                            </div>
                        </div>
                    </div>
                    <canvas id="totalSalesChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
-->
            
    </div>
    <!-- End of Main Content -->

    <!-- Logout Modal Popup + Logout Action -->
    <?php
    include 'logout_modal.php';
    ?>

    <?php
    include 'includes/scripts.php';
    ?>
</body>

</html>


<script>
    window.onload = function () {
        var chartData = <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>;
        console.log(chartData); // Output the data to the console for debugging
// Retrieve top selling products data from the session
var topSellingProductsData = <?php echo json_encode($_SESSION['top_selling_products_data'] ?? [], JSON_NUMERIC_CHECK); ?>;

// Use this data to render the pie chart
var chart = new CanvasJS.Chart("chartContainer", {
    animationEnabled: true,
    data: [{
        type: "pie",
        showInLegend: false,
        toolTipContent: "{label}: <strong>{y}</strong>",
        indexLabel: "{label} - {y}",
        indexLabelFontFamily: "Arial",
        indexLabelFontSize: 16,
        toolTipFontFamily: "Arial",
        toolTipFontSize: 14,
        dataPoints: topSellingProductsData
    }]
});

chart.render();

    }
</script>


<style>
    /* Assuming `.canvasjs-chart-credit` is the class associated with the attribution link */
    .canvasjs-chart-credit {
        display: none !important;
        /* Change this to your desired color */
    }
</style>


<script>
    var isBarChart = true; // Initially set as bar chart
    var totalSalesChart; // Variable to store the chart instance

    // Function to create the bar chart
    function createBarChart() {
        var totalSalesData = {
            labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            datasets: [{
                label: 'Total Sales',
                data: [1000, 2000, 1500, 3000, 2500, 1800, 2200], // Example data (replace with actual data)
                backgroundColor: '#259E9E', // Solid blue background color for the bars
                borderColor: '#259E9E', // Solid border color for the bars
                borderWidth: 2 // Increase the border width for better visibility
            }]
        };

        // Get the canvas element
        var ctx = document.getElementById('totalSalesChart').getContext('2d');

        // Destroy the existing chart if it exists
        if (totalSalesChart) {
            totalSalesChart.destroy();
        }

        // Create the bar chart
        totalSalesChart = new Chart(ctx, {
            type: 'bar',
            data: totalSalesData,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: {
                    display: false // Remove legend labels and box
                }
            }
        });
    }

    // Function to create the line chart
    function createLineChart() {
        var totalSalesData = {
            labels: ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            datasets: [{
                label: 'Total Sales',
                data: [1000, 2000, 1500, 3000, 2500, 1800, 2200], // Example data (replace with actual data)
                backgroundColor: '', // Set solid blue background color for the line
                borderColor: '#259E9E', // Set solid blue border color for the line
                borderWidth: 1
            }]
        };

        // Get the canvas element
        var ctx = document.getElementById('totalSalesChart').getContext('2d');

        // Destroy the existing chart if it exists
        if (totalSalesChart) {
            totalSalesChart.destroy();
        }

        // Create the line chart
        totalSalesChart = new Chart(ctx, {
            type: 'line',
            data: totalSalesData,
            options: {
                aspectRatio: 4, // Set a custom aspect ratio (width:height)
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: {
                    display: false // Remove legend labels and box
                }
            }
        });
    }

    // Call the createBarChart function by default
    createBarChart();

    // Function to toggle between bar and line chart
    function toggleChartType() {
        if (isBarChart) {
            createLineChart(); // Switch to line chart
            document.getElementById('barIcon').style.opacity = '0';
            document.getElementById('lineIcon').style.opacity = '1';
        } else {
            createBarChart(); // Switch to bar chart
            document.getElementById('lineIcon').style.opacity = '0';
            document.getElementById('barIcon').style.opacity = '1';
        }
        isBarChart = !isBarChart; // Toggle the chart type flag
    }
</script>

<style>
    /* The switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 80px;
        /* Adjusted width to accommodate both icons */
        height: 34px;
    }

    /* Hide the default checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider (background) */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #259E9E;
        /* Solid blue background */
        border-radius: 34px;
        box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
        /* Box shadow for depth */
        transition: background-color 0.4s;
    }


    /* Slider circle */
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        /* Adjusted position to center */
        bottom: 4px;
        /* Adjusted position to center */
        background-color: #fff;
        /* Circle color */
        border-radius: 50%;
        transition: transform 0.4s;
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    /* Style for the chart icons */
    .chart-icon {
        position: absolute;
        top: 55%;
        transform: translateY(-50%);
        font-size: 16px;
        color: #fff;
        /* Adjust color for the icon */
        opacity: 1;
        /* Fully visible */
        transition: opacity 0.3s ease;
    }

    /* Style for the icons on hover */
    .chart-icon:hover {
        opacity: 1;
        /* Fully visible on hover */
    }

    /* Positioning for the icons */
    #lineIcon {
        left: 10px;
        /* Adjusted position for the line graph icon */
    }

    #barIcon {
        right: 10px;
        /* Adjusted position for the bar graph icon */
    }

    /* Adjust position of the slider circle when switch is checked */
    .switch input:checked+.slider:before {
        transform: translateX(46px);
        /* Adjusted position to right */
    }
</style>

