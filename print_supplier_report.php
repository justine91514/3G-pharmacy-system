<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Report</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="om.css">
    <style>
.dataTables_wrapper {
    margin-top: 25px !important; /* Adjust the value as needed */
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

// Your PHP code for database queries and other functionality goes here
?>

<div class="container-fluid">
    <div class="card shadow nb-4">
        <div class="card-header py-3">
            <h1>Supplier Report</h1>
        </div>
        <div class="card-body">
            <h6 class="m-0 font-weight-bold text-primary">
            <button type="button" class="btn btn-primary" onclick="generatePrintableReport()" style="background-color: #259E9E; border: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
    <i class="fas fa-print fa-lg"></i> <span style="font-weight: bold; text-transform: uppercase;">Print Table</span>
</button>
            </h6>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead style="background-color: #259E9E; color: white;">
    <tr>
        <th>ID</th>
        <th>Supplier Name</th>
        <th>Contact Number</th>
        <th>Address</th>
        <th>Agreement</th>
    </tr>
</thead>
<tbody>
    <?php
    $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
    $query = "SELECT * FROM supplier_list";
    $query_run = mysqli_query($connection, $query);
    if(mysqli_num_rows($query_run) > 0) {
        while($row = mysqli_fetch_assoc($query_run)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['supplier_name'] . "</td>";
            echo "<td>" . $row['contact'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>" . $row['agreement'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No records found</td></tr>";
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

<!-- DataTables JavaScript -->
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
            { "orderable": true, "targets": [0, 1] },
            { "orderable": false, "targets": '_all' }
        ],
        "order": [[0, 'desc']] // Sorting by the second column in descending order by default
    });
});

</script>
<script>
function generatePrintableReport(selectedBranch) {
    window.location.href = 'printable_supplier_report.php?branch=' + selectedBranch;
}

function changeBranch() {
    var selectedBranch = document.getElementById("branch").value;
    window.location.href = 'printable_supplier_report.php' ;
}
</script>

</body>
</html>
