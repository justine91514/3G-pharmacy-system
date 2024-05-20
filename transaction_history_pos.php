<?php
function generateTransactionNo($date, $count)
{
    $year = substr($date, 0, 2);
    $month = substr($date, 2, 2);
    $day = substr($date, 4, 2);

    $current_date = date('ymd');
    $current_year = substr($current_date, 0, 2);
    $current_month = substr($current_date, 2, 2);
    $current_day = substr($current_date, 4, 2);

    if ($current_date != $date) {
        $count = 1;
    }

    return $year . $month . $day . str_pad($count, 3, '0', STR_PAD_LEFT);
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include ('includes/header_pos.php');
include ('includes/navbar_pos.php');
date_default_timezone_set('Asia/Manila');

if (isset($_POST['filter'])) {
    $selected_cashier = $_POST['cashier'];
    $sql_filter = ($selected_cashier != '') ? "WHERE cashier_name = '$selected_cashier'" : "";
} else {
    $cashier_name = $user_info['first_name'] . ' ' . $user_info['mid_name'] . ' ' . $user_info['last_name'];
    $sql_filter = "WHERE cashier_name = '$cashier_name'";
}
if (isset($_POST['filter_date'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    $from_date = date('Y-m-d', strtotime($from_date));
    $to_date = date('Y-m-d', strtotime($to_date));

    $sql_filter .= " AND date BETWEEN '$from_date' AND '$to_date'";
}

$query = "SELECT transaction_id, date, CONCAT(DATE_FORMAT(time, '%h:%i:%s'), DATE_FORMAT(NOW(), '%p')) AS time_with_am_pm, transaction_no, mode_of_payment, ref_no, list_of_items, sub_total, total_amount, cashier_name, branch FROM transaction_list $sql_filter";
$query_run = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" href="om.css">
<style>
    .container-fluid {
        margin-top: 100px;
    }

    .dataTables_wrapper {
        margin-top: 10px !important;
    }

    .see-more {
        color: #007bff;
        cursor: pointer;
        text-decoration: underline;
    }

    .see-more:hover {
        text-decoration: none;
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
            $list_of_items = $row['list_of_items'];
            $max_length = 30; // Adjust this value based on your preference
            $short_items = substr($list_of_items, 0, $max_length);
            $needs_see_more = strlen($list_of_items) > $max_length;
            ?>
            <tr>
                <td style="vertical-align: middle;"> <?php echo $row['transaction_no']; ?></td>
                <td style="vertical-align: middle;"> <?php echo $row['date']; ?></td>
                <td style="vertical-align: middle;"> <?php echo $row['time_with_am_pm']; ?></td>
                <td style="vertical-align: middle;"> <?php echo $row['mode_of_payment']; ?></td>
                <td style="vertical-align: middle;"> <?php echo $row['ref_no']; ?></td>
                <td style="vertical-align: middle;">
                    <span class="short-items"><?php echo htmlspecialchars($short_items); ?></span>
                    <?php if ($needs_see_more) { ?>
                        <span class="more-items" style="display: none;"><?php echo htmlspecialchars($list_of_items); ?></span>
                        <a href="#" class="see-more" data-items="<?php echo htmlspecialchars($list_of_items); ?>">See More</a>
                    <?php } ?>
                </td>
                <td style="vertical-align: middle;"> <?php echo $row['sub_total']; ?></td>
                <td style="vertical-align: middle;"> <?php echo $row['total_amount']; ?></td>
                <td style="vertical-align: middle;"> <?php echo $row['cashier_name']; ?></td>
                <td style="vertical-align: middle;"> <?php echo $row['branch']; ?></td>
                <td style="margin-top: 50px;">
                    <form action="print_receipt.php" method="post">
                        <input type="hidden" name="print_id" value="<?php echo $row['transaction_id']; ?>">
                        <button type="submit" class="btn btn-action" style="border: none; background: none; line-height: 1;">
                            <i class="fas fa-print" style="color: #0000FF; margin-top: 15px;"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='11'>No record Found</td></tr>";
    }
    ?>
</tbody>


            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="itemsModal" tabindex="-1" role="dialog" aria-labelledby="itemsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemsModalLabel">List of Items</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modalItemsContent"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    include ('includes/pos_logout.php');
    include ('includes/scripts.php');
    include ('includes/footer.php');
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "paging": true,
                "lengthChange": true,
                "pageLength": 10,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "language": {
                    "paginate": {
                        "previous": "<i class='fas fa-arrow-left'></i>",
                        "next": "<i class='fas fa-arrow-right'></i>"
                    }
                },
                "pagingType": "simple",
                "columnDefs": [
                    { "orderable": true, "targets": [0, 3, 4] },
                    { "orderable": false, "targets": '_all' }
                ],
                "order": [[0, "desc"]]
            });

            // Disable "To Date" input field initially
            $('#to_date').prop('disabled', true);

            // Enable "To Date" input field based on "From Date" input
            $('#from_date').change(function() {
                if ($(this).val()) {
                    $('#to_date').prop('disabled', false);
                } else {
                    $('#to_date').prop('disabled', true);
                }
            });

            // Show modal with full list of items
            $('.see-more').click(function(event) {
                event.preventDefault();
                var items = $(this).data('items');
                $('#modalItemsContent').text(items);
                $('#itemsModal').modal('show');
            });
        });
    </script>
