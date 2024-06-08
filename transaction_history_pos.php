<?php
function generateTransactionNo($date, $count)
{
    // Separate the year, month, and day from the date
    $year = substr($date, 0, 2);
    $month = substr($date, 2, 2);
    $day = substr($date, 4, 2);

    // Get the current date
    $current_date = date('ymd');

    // Separate the current year, month, and day
    $current_year = substr($current_date, 0, 2);
    $current_month = substr($current_date, 2, 2);
    $current_day = substr($current_date, 4, 2);

    // If the current date is different from the provided date, reset the count to 1
    if ($current_date != $date) {
        $count = 1;
    }

    // Generate the transaction number with padded count
    return $year . $month . $day . str_pad($count, 3, '0', STR_PAD_LEFT); // Format: YYMMDDXXX
}


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include ('includes/header_pos.php');
include ('includes/navbar_pos.php');
date_default_timezone_set('Asia/Manila');

// Check if form is submitted and get the selected cashier name
if (isset($_POST['filter'])) {
    $selected_cashier = $_POST['cashier'];
    $sql_filter = ($selected_cashier != '') ? "WHERE cashier_name = '$selected_cashier'" : "";
} else {
    // If no filter is applied, default to the current cashier's name
    $cashier_name = $user_info['first_name'] . ' ' . $user_info['mid_name'] . ' ' . $user_info['last_name'];
    $sql_filter = "WHERE cashier_name = '$cashier_name'";
}
if (isset($_POST['filter_date'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Ensure dates are in the correct format for MySQL
    $from_date = date('Y-m-d', strtotime($from_date));
    $to_date = date('Y-m-d', strtotime($to_date));

    // Modify the SQL query to include the date range filter
    $sql_filter .= " AND date BETWEEN '$from_date' AND '$to_date'";
}

// Your existing SQL query modification code here
$query = "SELECT transaction_id, date, CONCAT(DATE_FORMAT(time, '%h:%i:%s'), DATE_FORMAT(NOW(), '%p')) AS time_with_am_pm, transaction_no, mode_of_payment, ref_no, list_of_items, sub_total, total_amount, cashier_name, branch FROM transaction_list $sql_filter";
$query_run = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
</head>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" href="om.css">
<style>
    .container-fluid {
        margin-top: 100px;
        /* Adjust the value as needed */
    }

    .dataTables_wrapper {
        margin-top: 10px !important;
        /* Adjust the value as needed */
    }
</style>

</html>
<div class="container-fluid">
    <div class="card shadow nb-4">
        <div class="card-header py-3">
            <h1>Transaction History</h1>
            <h6 class="m-0 font-weight-bold text-primary">

            </h6>
        </div>
        <div class="card-body">

        <form action="" method="post">
        <div class="form-group row" style="margin-top: 20px;">
        <div class="col">
        <label for="from_date"><strong>From:</strong></label>
            <input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" required>
        </div>
        <div class="col">
        <label for="to_date"><strong>To:</strong></label>
            <input type="date" id="to_date" name="to_date" class="form-control"  placeholder="To Date"
                                required disabled>
        </div>
        <div class="col">
        <button type="submit" name="filter_date" class="btn btn-primary"  style="border-radius: 5px; padding: 10px 20px; background-color: #304B1B; border: none; box-shadow: none; margin-top: 28px;">Filter</button>
        </div>
        </div>
    </form>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead style="background-color: #304B1B; color: white;">
                        <th style="vertical-align: middle;"> Transaction No. </th>
                        <th style="vertical-align: middle;"> Date </th>
                        <th style="vertical-align: middle;"> Time </th>
                        <th style="vertical-align: middle;"> Mode of Payment </th>
                        <th style="vertical-align: middle;"> Reference No. </th>
                        <th style="vertical-align: middle;"> List of Items </th>
                        <th style="vertical-align: middle;"> Sub Total </th>
                        <th style="vertical-align: middle;"> Grand Total </th>
                        <th style="vertical-align: middle;"> Cashier Name </th>
                        <th style="vertical-align: middle;"> Branch </th>
                        <th style="vertical-align: middle;"> Reissue of Reciept </th>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                ?>
                                <tr>
                                    <td style="vertical-align: middle;"> <?php echo $row['transaction_no']; ?></td>
                                    <td style="vertical-align: middle;"> <?php echo $row['date']; ?></td>
                                    <td style="vertical-align: middle;"> <?php echo $row['time_with_am_pm']; ?></td>
                                    <td style="vertical-align: middle;"> <?php echo $row['mode_of_payment']; ?></td>
                                    <td style="vertical-align: middle;"> <?php echo $row['ref_no']; ?></td>
                                    <td style="vertical-align: middle;"> <?php echo $row['list_of_items']; ?></td>
                                    <td style="vertical-align: middle;"> <?php echo $row['sub_total']; ?></td>
                                    <td style="vertical-align: middle;"> <?php echo $row['total_amount']; ?></td>
                                    <td style="vertical-align: middle;"> <?php echo $row['cashier_name']; ?></td>
                                    <td style="vertical-align: middle;"> <?php echo $row['branch']; ?></td>
                                    
                                    <td style="margin-top: 50px;">
                                        <form action="print_receipt.php" method="post">
                                            <input type="hidden" name="print_id" value="<?php echo $row['transaction_id']; ?>">
                                            <button type="submit" class="btn btn-action"
                                                style="border: none; background: none; line-height: 1;">
                                                <i class="fas fa-print" style="color: #0000FF; margin-top: 15px;"></i>
                                            </button>
                                        </form>

                                    </td>

                                </tr>
                                <?php
                            }
                        } else {
                            echo "No record Found";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Rest of the code -->



    <?php
    include ('includes/pos_logout.php');
    include ('includes/scripts.php');
    include ('includes/footer.php');
    ?>

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
$(document).ready(function() {
    // Initially disable the "To Date" input field
    $('#to_date').prop('disabled', true);

    // Listen for changes on the "From Date" input field
    $('#from_date').change(function() {
        // If a date is selected, enable the "To Date" input field
        if ($(this).val()) {
            $('#to_date').prop('disabled', false);
        } else {
            // If the "From Date" is cleared, disable the "To Date" input field again
            $('#to_date').prop('disabled', true);
        }
    });
});
</script>
