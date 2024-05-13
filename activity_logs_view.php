<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('includes/header.php');
include('includes/navbar2.php');
date_default_timezone_set('Asia/Manila');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>
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
            <h1>Activity Logs</h1>
            <h6 class="m-0 font-weight-bold text-primary"></h6>
        </div>
        <div class="card-body">
        <h6 class="m-0 font-weight-bold text-primary">
            <button type="button" class="btn btn-primary" onclick="generatePrintableReport()" style="background-color: #304B1B; border: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
    <i class="fas fa-print fa-lg"></i> <span style="font-weight: bold; text-transform: uppercase;">Print Table</span>
</button>
            </h6>
            <div class="table-responsive">

                <?php
                    $connection = mysqli_connect("localhost","root","","dbpharmacy");
                    $query = "SELECT id, user, user_type, activity_description, time FROM activity_logs";
                    $query_run = mysqli_query($connection, $query);
                ?>
                
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <!-- HEADER VALUES -->
                    <thead style="background-color: #304B1B; color: white;">
                        <th>ID</th>
                        <th>User</th>
                        <th>UserType</th>
                        <th>Activity Description</th>
                        <th>Time</th>
                    </thead>

                    <!-- DATA -->
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0)
                        {
                            while($row = mysqli_fetch_assoc($query_run))
                            {
                                ?>    
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['user']; ?></td>
                                    <td><?php echo $row['user_type']; ?></td>
                                    <td><?php echo $row['activity_description']; ?></td>
                                    <td><?php echo date('M j, Y g:i A', strtotime($row['time'])); ?></td>

                                </tr>
                                <?php
                            }
                        }
                        else{
                            echo "No record Found";
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

<!-- Logout Modal Popup + Logout Action -->
<?php
    include 'logout_modal.php';
?>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>

<!-- DataTables JavaScript -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
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
                { "orderable": true, "targets": [0, 1, 2] }, // ID and Product Name columns are sortable
                { "orderable": false, "targets": '_all' } // Disable sorting for all other columns
            ],
            "order": [[0, "desc"]] // Sort by the first column (ID) in descending order
        });
    });
</script>

<script>
function generatePrintableReport(selectedBranch) {
    window.location.href = 'printable_activity_report.php?branch=' + selectedBranch;
}

function changeBranch() {
    var selectedBranch = document.getElementById("branch").value;
    window.location.href = 'printable_activity_report.php' ;
}
</script>

</body>
</html>
