<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management Report</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="om.css">
    <script>
function changeBranch() {
    var selectedBranch = document.getElementById("branch").value;
    window.location.href = 'print_user_report.php?branch=' + selectedBranch;
}
</script>
<style>
.dataTables_wrapper {
    margin-top: 10px !important; /* Adjust the value as needed */
}
.container-fluid {
    margin-top: 100px; /* Adjust the value as needed */
}
</style>
</head>
<body>

<?php
session_start();
include('includes/header.php');
include('includes/navbar2.php');
include("dbconfig.php");

$selectedBranch = isset($_GET['branch']) ? $_GET['branch'] : 'All';

// Your PHP code for database queries and other functionality goes here
?>

<div class="container-fluid">
    <div class="card shadow nb-4">
        <div class="card-header py-3">
            <h1>User Management Report</h1>
        </div>
        <div class="card-body">
            <h6 class="m-0 font-weight-bold text-primary">
                <button type="button" class="btn btn-primary shadow" onclick="generatePrintableReport('<?php echo $selectedBranch; ?>')" style="background-color: #FFFFFF; border: 2px solid #259E9E; color: #000000; border-radius: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                    <i class="fas fa-print fa-lg" style="color: #000000;"></i> 
                    <span style="font-weight: bold; text-transform: uppercase;">Print Table</span>
                </button>
            </h6>

            <div class="form-group">
                <label class="modal-label" style="top: 5px; padding-top: 15px;"> Branch</label>
                <select id="branch" name="branch" class="form-control" onchange="changeBranch()">
                    <option value="All" <?php if($selectedBranch == 'All') echo 'selected'; ?>>All</option>
                    <option value="Cell Med" <?php if($selectedBranch == 'Cell Med') echo 'selected'; ?>>Cell Med</option>
                    <option value="3G Med" <?php if($selectedBranch == '3G Med') echo 'selected'; ?>>3G Med</option>
                    <option value="Boom Care" <?php if($selectedBranch == 'Boom Care') echo 'selected'; ?>>Boom Care</option>
                    <!-- Add more options for other branches as needed -->
                </select>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead style="background-color: #259E9E; color: white;">
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Branch</th>
                            <th>Usertype</th>
                            <!-- Add more table headers if needed -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
                        $query = "SELECT * FROM register";
                        if ($selectedBranch != 'All') {
                            $query .= " WHERE branch = '$selectedBranch'";
                        }
                        $query_run = mysqli_query($connection, $query);
                        if(mysqli_num_rows($query_run) > 0) {
                            while($row = mysqli_fetch_assoc($query_run)) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['first_name'] . "</td>";
                                echo "<td>" . $row['mid_name'] . "</td>";
                                echo "<td>" . $row['last_name'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['password'] . "</td>";
                                echo "<td>" . $row['branch'] . "</td>";
                                echo "<td>" . $row['usertype'] . "</td>";
                                // Add more table data if needed
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals and other HTML elements -->

<?php
        include 'logout_modal.php';
        ?>

        <?php
        include ('includes/scripts.php');
        include ('includes/footer.php');
        ?>

<!-- JavaScript code including AJAX, sorting, and DataTables initialization -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
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
            { "orderable": true, "targets": [0, 4, 7] },
            { "orderable": false, "targets": '_all' }
        ],
        "order": [[0, 'desc']] // Sorting by the fifth column in descending order by default
    });
});
</script>
<script>
function generatePrintableReport(selectedBranch) {
    window.location.href = 'printable_user_report.php?branch=' + selectedBranch;
}


</script>

</body>
</html>
