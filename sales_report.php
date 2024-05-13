<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="om.css">
    <style>
        .dataTables_wrapper {
            margin-top: 25px !important;
            /* Adjust the value as needed */
        }

        .container-fluid {
            margin-top: 100px;
            /* Adjust the value as needed */
        }
    </style>
</head>

<body>

    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include ('includes/header.php');
    include ('includes/navbar2.php');
    include ('activity_logs.php');
    // Initialize variables
    $total_earnings = 0;
    $earnings_by_date = [];
    $branch_name = '';

    foreach ($earnings_by_date as $date => $earnings) {
        $total_earnings += $earnings; // Increment total earnings
    }

    // Establish connection to the database
    $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

    // Check if the form is submitted and dates are set
    if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
        // Get the selected dates
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];

        // Establish connection to the database
        $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
        // Get all dates within the selected range
        $current_date = date('Y-m-d', strtotime($start_date));
        $end_date = date('Y-m-d', strtotime($end_date));

        // Get the selected pharmacy assistant
        $selected_assistant = isset($_GET['pharmacy_assistant']) ? $_GET['pharmacy_assistant'] : '';

        // Prepare the WHERE clause for filtering by pharmacy assistant
        $assistant_filter = '';
        if (!empty($selected_assistant)) {
            $assistant_filter = "AND cashier_name = '$selected_assistant'";
        }

        // Get the selected branch
        $selected_branch = isset($_GET['branch']) ? $_GET['branch'] : '';

        // Prepare the WHERE clause for filtering by branch
        $branch_filter = '';
        $branch_name = '';
        if (!empty($selected_branch)) {
            $branch_filter = "AND branch = '$selected_branch'";
            $branch_name = $selected_branch; // Set the branch name to display in the table
        }

        $query = "SELECT date, SUM(total_amount) AS earnings FROM transaction_list WHERE date = '$current_date' $assistant_filter $branch_filter GROUP BY date";

        while ($current_date <= $end_date) {
            // Query to get earnings for each date
            $query = "SELECT date, SUM(total_amount) AS earnings FROM transaction_list WHERE date = '$current_date' $assistant_filter $branch_filter GROUP BY date";
            $query_run = mysqli_query($connection, $query);
            $row = mysqli_fetch_assoc($query_run);
            $earnings = $row['earnings'] ?? 0;

            // Store earnings for each date
            $earnings_by_date[$current_date] = $earnings;

            // Increment total earnings
            $total_earnings += $earnings;

            // Move to the next date
            $current_date = date('Y-m-d', strtotime($current_date . ' + 1 day'));
        }
    }
    ?>

    <div class="container-fluid">
        <div class="card shadow nb-4">
            <div class="card-header py-3">
                <h1 class="mb-0">Sales Report</h1>
            </div>
            <div class="card-body">
                <h6 class="m-0 font-weight-bold text-primary">
                    <button type="button" class="btn btn-primary"
                        onclick="generatePrintableReport('<?php echo $selected_branch; ?>', '<?php echo $start_date; ?>', '<?php echo $end_date; ?>', '<?php echo $selected_assistant; ?>')"
                        style="background-color: #259E9E; border: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                        <i class="fas fa-print fa-lg"></i> <span
                            style="font-weight: bold; text-transform: uppercase;">Print Table</span>
                    </button>
                </h6>
                <div class="card-body">
                    <form id="earningsForm" method="GET">
                        <div class="form-group row">
                            <label for="start_date" class="col-sm-3 col-form-label"><strong>From:</strong></label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="end_date" class="col-sm-3 col-form-label"><strong>To:</strong></label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pharmacy_assistant" class="col-sm-3 col-form-label"><strong>Pharmacy
                                    Assistant:</strong></label>
                            <div class="col-sm-9">
                                <select name="pharmacy_assistant" id="pharmacy_assistant" class="form-control">
                                    <option value="Select Name">Select Name</option>
                                    <option value="">All</option> <!-- Option for all assistants -->
                                    <?php
                                    // Query to fetch pharmacy assistants' names
                                    $query = "SELECT CONCAT(first_name, ' ', mid_name, ' ', last_name) AS full_name FROM register WHERE usertype = 'pharmacy_assistant'";
                                    $result = mysqli_query($connection, $query);

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . $row['full_name'] . "'>" . $row['full_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="branch" class="col-sm-3 col-form-label"><strong>Branch:</strong></label>
                            <div class="col-sm-9">
                                <select name="branch" id="branch" class="form-control">
                                    <option value="">Select Branch</option> <!-- Option for all branches -->
                                    <?php
                                    // Query to fetch branches
                                    $query_branches = "SELECT DISTINCT branch FROM register";
                                    $result_branches = mysqli_query($connection, $query_branches);

                                    while ($row_branch = mysqli_fetch_assoc($result_branches)) {
                                        echo "<option value='" . $row_branch['branch'] . "'>" . $row_branch['branch'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" id="computeButton"
                                style="border-radius: 5px; padding: 10px 20px; background-color: #259E9E; border: none; box-shadow: none;"
                                disabled>Compute</button>
                        </div>
                    </form>



                    <?php if (isset($total_earnings)) { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead style="background-color: #259E9E; color: white;">
                                    <tr>
                                        <?php if (!empty($selected_assistant) && $selected_assistant !== "All") { ?>
                                            <!-- Display assistant column only if one is selected -->
                                            <th>Pharmacy Assistant</th>
                                        <?php } ?>
                                        <?php if (!empty($branch_name)) { ?>
                                            <!-- Display branch column if a branch is selected -->
                                            <th>Branch</th>
                                        <?php } ?>
                                        <th>Date</th>
                                        <th>Earnings</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($earnings_by_date as $date => $earnings) { ?>
                                        <tr>
                                            <?php if (!empty($selected_assistant) && $selected_assistant !== "All") { ?>
                                                <!-- Display assistant column only if one is selected -->
                                                <td><?php echo $selected_assistant; ?></td>
                                            <?php } ?>
                                            <?php if (!empty($branch_name)) { ?> <!-- Display branch name if selected -->
                                                <td><?php echo $branch_name; ?></td>
                                            <?php } ?>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $earnings; ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <?php if (!empty($selected_assistant) && $selected_assistant !== "All") { ?>
                                            <!-- Display empty cell if no assistant is selected -->
                                            <td></td>
                                        <?php } ?>
                                        <?php if (!empty($branch_name)) { ?>
                                            <!-- Display empty cell if no branch is selected -->
                                            <td></td>
                                        <?php } ?>
                                        <td>Total</td>
                                        <td>₱<?php echo number_format($total_earnings, 2, '.', ''); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>

                    <!-- Modals and other HTML elements -->
                </div>
            </div>
        </div>
</body>

</html>

<?php
include ('includes/scripts.php');
include ('includes/footer.php');
?>

<!-- Logout Modal Popup + Logout Action -->
<?php
include 'logout_modal.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var startDateInput = document.getElementById("start_date");
        var endDateInput = document.getElementById("end_date");
        var pharmacyAssistant = document.getElementById("pharmacy_assistant");
        var branchSelect = document.getElementById("branch");
        var computeButton = document.getElementById("computeButton");

        // Disable inputs and selectors initially
        endDateInput.disabled = true;
        pharmacyAssistant.disabled = true;
        branchSelect.disabled = true;
        computeButton.disabled = true;

        // Add change event listeners to start date input
        startDateInput.addEventListener("change", function () {
            endDateInput.disabled = false; // Enable end date once start date is selected
            enableForm(); // Check if the form can be enabled
        });

        // Add change event listeners to end date input
        endDateInput.addEventListener("change", function () {
            pharmacyAssistant.disabled = false; // Enable pharmacy assistant selector once end date is selected
            branchSelect.disabled = false; // Enable branch selector once end date is selected
            enableForm(); // Check if the form can be enabled
        });

        // Add change event listener to pharmacy assistant selector
        pharmacyAssistant.addEventListener("change", function () {
            // Disable branch selector if pharmacy assistant is selected (excluding the "Select Name" option)
            branchSelect.disabled = pharmacyAssistant.value !== 'Select Name';

        });

        // Add change event listener to branch selector
        branchSelect.addEventListener("change", function () {
            // Disable pharmacy assistant selector if branch is selected
            pharmacyAssistant.disabled = branchSelect.value !== '';
        });

        function enableForm() {
            var startDate = startDateInput.value;
            var endDate = endDateInput.value;

            // Enable the Compute button if start date and end date are selected
            computeButton.disabled = !(startDate && endDate);
        }
    });
</script>

<!-- DataTables JavaScript -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function () {
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
                { "orderable": true, "targets": [0, 1] },
                { "orderable": false, "targets": '_all' }
            ],
            "order": [[0, 'desc']] // Sorting by the second column in descending order by default
        });
    });

</script>
<script>
    function generatePrintableReport(selectedBranch, startDate, endDate, selectedAssistant) {

        window.location.href = 'printable_sales_report.php?branch=' + selectedBranch + '&start_date=' + startDate + '&end_date=' + endDate + '&pharmacy_assistant=' + selectedAssistant;
    }

    function changeBranch() {
        var selectedBranch = document.getElementById("branch").value;
        var startDate = document.getElementById("start_date").value; // Get the selected start date
        var endDate = document.getElementById("end_date").value; // Get the selected end date
        var selectedAssistant = document.getElementById("pharmacy_assistant").value; // Get the selected pharmacy assistant

        // Redirect to the printable sales report page with selected parameters


        window.location.href = 'printable_sales_report.php?branch=' + selectedBranch + '&start_date=' + startDate + '&end_date=' + endDate + '&pharmacy_assistant=' + selectedAssistant;
    }

</script>