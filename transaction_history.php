<?php
session_start(); // Start the session

// Includes
require 'includes/header.php';
require 'includes/navbar2.php';
date_default_timezone_set('Asia/Manila');

// Database Connection
$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

// Check Connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch Branches for Dropdown
$branch_query = "SELECT DISTINCT branch FROM transaction_list";
$branch_result = mysqli_query($connection, $branch_query);
$branches = [];
if ($branch_result && mysqli_num_rows($branch_result) > 0) {
    while ($branch = mysqli_fetch_assoc($branch_result)) {
        $branches[] = $branch;
    }
}

// Fetch Cashiers for Dropdown
$cashier_query = "SELECT DISTINCT cashier_name FROM transaction_list ORDER BY cashier_name";
$cashier_query_run = mysqli_query($connection, $cashier_query);
$cashiers = [];
if (mysqli_num_rows($cashier_query_run) > 0) {
    while ($cashier = mysqli_fetch_assoc($cashier_query_run)) {
        $cashiers[] = $cashier;
    }
}


// Handle Filter Submission
$from_date = $_GET['from_date'] ?? null;
$to_date = $_GET['to_date'] ?? null;
$branch = $_GET['branch'] ?? '';
$cashier_name = $_GET['cashier_name'] ?? '';

// Prepare Query based on Filter
$query = "SELECT transaction_id, date, CONCAT(DATE_FORMAT(time, '%h:%i:%s'), DATE_FORMAT(NOW(), '%p')) AS time_with_am_pm, transaction_no, mode_of_payment, ref_no, list_of_items, sub_total, total_amount, cashier_name, branch FROM transaction_list WHERE 1=1";

$params = [];
$types = '';
if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND date BETWEEN ? AND ?";
    $params[] = $from_date;
    $params[] = $to_date;
    $types .= 'ss';
}
if (!empty($branch)) {
    $query .= " AND branch = ?";
    $params[] = $branch;
    $types .= 's';
}

if (!empty($cashier_name)) {
    $query .= " AND cashier_name = ?";
    array_push($params, $cashier_name);
}


$stmt = mysqli_prepare($connection, $query);
if (!empty($params)) {
    $typeStr = str_repeat('s', count($params));
    mysqli_stmt_bind_param($stmt, $typeStr, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="om.css">
    <style>
        .container-fluid {
            margin-top: 100px;
        }

        .dataTables_wrapper {
            margin-top: 10px !important;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="card shadow nb-4">
            <div class="card-header py-3">
                <h1>Transaction History</h1>
            </div>
            <!--<div class="card-body">
            <h6 class="m-0 font-weight-bold text-primary">
                <button type="button" class="btn btn-primary" onclick="generatePrintableReport()" style="background-color: #304B1B; border: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                    <i class="fas fa-print fa-lg"></i> <span style="font-weight: bold; text-transform: uppercase;">Print Table</span>
                </button>
    </h6>-->
            <div class="card-body">
                <form action="" method="GET"> <!-- Make sure to set the correct action attribute -->
                    <div class="form-group row" style="margin-top: 20px;">
                        <!-- From Date -->
                        <div class="col">
                            <label for="from_date"><strong>From:</strong></label>
                            <input type="date" name="from_date" id="from_date" class="form-control"
                                placeholder="From Date" required>
                        </div>

                        <!-- To Date -->
                        <div class="col">
                            <label for="to_date"><strong>To:</strong></label>
                            <input type="date" name="to_date" id="to_date" class="form-control" placeholder="To Date"
                                required disabled>
                        </div>

                        <!-- Pharmacy Assistant -->
                        <div class="col">
                            <label for="cashierNameDropdown"><strong>Pharmacy Assistant:</strong></label>
                            <select name="cashier_name" class="form-control" id="cashierNameDropdown" disabled>
                                <option value="">Select Cashier</option>
                                <?php foreach ($cashiers as $c) { ?>
                                    <option value="<?= htmlspecialchars($c['cashier_name']); ?>">
                                        <?= htmlspecialchars($c['cashier_name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Branch -->
                        <div class="col">
                            <label for="branchSelect"><strong>Branch:</strong></label>
                            <select name="branch" class="form-control" id="branchSelect" disabled>
                                <option value="">Select Branch</option>
                                <?php foreach ($branches as $b) { ?>
                                    <option value="<?= htmlspecialchars($b['branch']); ?>">
                                        <?= htmlspecialchars($b['branch']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="col">
                            <button type="submit" class="btn btn-primary"
                                style="border-radius: 5px; padding: 10px 20px; background-color: #304B1B; border: none; box-shadow: none; margin-top: 28px;">Filter</button>
                        </div>
                    </div>
                </form>
                <br>


                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead style="background-color: #304B1B; color: white;">
                        <th style="vertical-align: middle;">Transaction No.</th>
                        <th style="vertical-align: middle;">Date</th>
                        <th style="vertical-align: middle;">Time</th>
                        <th style="vertical-align: middle;">Mode of Payment</th>
                        <th style="vertical-align: middle;">Reference No.</th>
                        <th style="vertical-align: middle;">List of Items</th>
                        <th style="vertical-align: middle;">Sub Total</th>
                        <th style="vertical-align: middle;">Grand Total</th>
                        <th style="vertical-align: middle;">Cashier Name</th>
                        <th style="vertical-align: middle;">Branch</th>
                        <th style="vertical-align: middle;">Reissue of Receipt</th>
                    </thead>
                    <tbody>
                        
                        <?php
                        $current_day = date('N'); // Get the current day of the week (1 = Monday, 7 = Sunday)
                        $start_of_week = date('Y-m-d', strtotime("-$current_day days")); // Start of the week is Monday
                        $end_of_week = date('Y-m-d', strtotime("+$current_day days")); // End of the week is Sunday
                        
                        // Query to fetch total sales for each day of the current week
                        $day_sales_query = "SELECT DAYNAME(date) AS day, COALESCE(SUM(total_amount), 0) AS total_sales 
                                            FROM transaction_list 
                                            WHERE date BETWEEN ? AND ?
                                            GROUP BY DAYOFWEEK(date)";
                        $stmt = mysqli_prepare($connection, $day_sales_query);
                        mysqli_stmt_bind_param($stmt, "ss", $start_of_week, $end_of_week);
                        mysqli_stmt_execute($stmt);
                        $day_sales_result = mysqli_stmt_get_result($stmt);
                        
                        $day_sales_data = [];
                        // Initialize an associative array with zero sales for each day of the week
                        $days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach ($days_of_week as $day) {
                            $day_sales_data[$day] = 0;
                        }
                        
                        // Store the actual total sales for each day of the current week
                        if ($day_sales_result && mysqli_num_rows($day_sales_result) > 0) {
                            while ($row = mysqli_fetch_assoc($day_sales_result)) {
                                $day_sales_data[$row['day']] = $row['total_sales'];
                            }
                        }
                        
                        $product_frequencies = [];
                        if ($result && mysqli_num_rows($result) > 0) {
                            // Calculate Product Frequencies
                            $product_frequencies = [];
                            while ($row = mysqli_fetch_assoc($result)) {
                                $items = explode(',', $row['list_of_items']);
                                foreach ($items as $item) {
                                    $item = trim($item);
                                    if (!empty($item)) {
                                        if (isset($product_frequencies[$item])) {
                                            $product_frequencies[$item]++;
                                        } else {
                                            $product_frequencies[$item] = 1;
                                        }
                                    }
                                }
                                arsort($product_frequencies);

                                // Display Top 5 Selling Products
                                $top_selling_products = array_slice($product_frequencies, 0, 5);

                                $top_selling_products_data = [];
                                foreach ($top_selling_products as $product => $frequency) {
                                    $top_selling_products_data[] = ['label' => htmlspecialchars($product), 'y' => $frequency];
                                }

                                // Store this data in a session variable
                                $_SESSION['top_selling_products_data'] = $top_selling_products_data;
                                ?>
                                <!-- Display Top Selling Products -->

                                <tr>

                                    <td style="vertical-align: middle;"><?= htmlspecialchars($row['transaction_no']); ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($row['date']); ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($row['time_with_am_pm']); ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($row['mode_of_payment']); ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($row['ref_no']); ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($row['list_of_items']); ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($row['sub_total']); ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($row['total_amount']); ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($row['cashier_name']); ?></td>
                                    <td style="vertical-align: middle;"><?= htmlspecialchars($row['branch']); ?></td>
                                    <td>
                                        <form action="print_receipt.php" method="post">
                                            <input type="hidden" name="print_id" value="<?php echo $row['transaction_id']; ?>">
                                            <button type="submit" class="btn btn-action"
                                                style="border: none; background: none; line-height: 1;">
                                                <i class="fas fa-print" style="color: #0000FF; margin-top: 15px;"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td style="display: none;">
                                        <ul>
                                            <?php foreach ($top_selling_products as $product => $frequency) { ?>
                                                <li><?= htmlspecialchars($product); ?> (<?= $frequency; ?>)</li>
                                            <?php } ?>
                                        </ul>
                                    </td>
                                    <?php foreach ($day_sales_data as $day => $total_sales) { ?>
        
            <td style="display: none;"colspan="10">Total Sales for <?= $day ?>: <?= $total_sales ?></td>
        
    <?php } ?>

                                </tr>
                            <?php }
                        } else {
                            echo "No records found.";
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php include 'logout_modal.php'; ?>
        <?php include 'includes/scripts.php'; ?>
        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- DataTables JavaScript -->
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "pageLength": 10, // Display 10 entries per page
                "searching": true,
                "ordering": true, // Enable ordering/sorting
                "info": true,
                "autoWidth": false,
                "language": {
                    "paginate": {
                        "previous": "<i class='fas fa-arrow-left'></i>", // Use arrow-left icon for previous button
                        "next": "<i class='fas fa-arrow-right'></i>" // Use arrow-right icon for next button
                    }
                },
                "pagingType": "simple", // Set the pagination type to simple
                "columnDefs": [
                    { "orderable": true, "targets": [0, 3, 4] }, // ID and Product Name columns are sortable
                    { "orderable": false, "targets": '_all' } // Disable sorting for all other columns
                ],
                "order": [[0, "desc"]] // Sort by the first column (ID) in descending order
            });
        });
    </script>

    <script>
        function generatePrintableReport() {
            // Get the selected values from the form
            var fromDate = document.getElementById('from_date').value;
            var toDate = document.getElementById('to_date').value;
            var branch = document.getElementById('branchSelect').value;
            var cashierName = document.getElementById('cashierNameDropdown').value;

            // Construct the URL with query parameters
            var url = 'printable_transaction_history.php?';
            url += 'from_date=' + encodeURIComponent(fromDate) + '&';
            url += 'to_date=' + encodeURIComponent(toDate) + '&';
            url += 'branch=' + encodeURIComponent(branch) + '&';
            url += 'cashier_name=' + encodeURIComponent(cashierName);

            window.location.href = 'printable_transaction_history.php';
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fromDate = document.querySelector('input[name="from_date"]');
            const toDate = document.querySelector('input[name="to_date"]');
            const branchDropdown = document.querySelector('select[name="branch"]');
            const cashierDropdown = document.querySelector('select[name="cashier_name"]');

            fromDate.addEventListener('change', function () {
                toDate.disabled = false;
            });

            toDate.addEventListener('change', function () {
                branchDropdown.disabled = false;
                cashierDropdown.disabled = false;
            });

            branchDropdown.addEventListener('change', function () {
                if (branchDropdown.value !== "") {
                    cashierDropdown.disabled = true;
                } else {
                    cashierDropdown.disabled = false;
                }
            });

            cashierDropdown.addEventListener('change', function () {
                if (cashierDropdown.value !== "") {
                    branchDropdown.disabled = true;
                } else {
                    branchDropdown.disabled = false;
                }
            });
        });
    </script>

</body>

</html>