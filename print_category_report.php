<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Report</title>
    <!-- Add your CSS styles and DataTables CSS CDN link here -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="om.css">
</head>
<style>
.container-fluid {
    margin-top: 100px; /* Adjust the value as needed */
}

.dataTables_wrapper {
    margin-top: 15px !important; /* Adjust the value as needed */
}
</style>
<body>
    <?php 
    session_start();
    include('includes/header.php');
    include('includes/navbar2.php');
    ?>


    <div class="container-fluid">
        <!-- DataTables Example -->
        <div class="card shadow nb-4">
            <div class="card-header py-3">
                <h1>Category Report</h1>
            </div>
            <div class="card-body">
                <h6 class="m-0 font-weight-bold text-primary">
                    <button type="button" class="btn btn-primary" onclick="generatePrintableReport()" style="background-color: #259E9E; border: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                        <i class="fas fa-print fa-lg"></i> <span style="font-weight: bold; text-transform: uppercase;">Print Table</span>
                    </button>
                </h6>
                <!-- Debugging: Check for PHP errors -->
                <?php
                if(isset($_SESSION['success']) && $_SESSION['success'] !='') {
                    echo '<h2 class="bg-primary text-white">' .$_SESSION['success'].'</h2>';
                    unset($_SESSION['success']);
                }
                ?>

                <!-- Debugging: Check for PHP errors -->
                <?php
                if(isset($_SESSION['status']) && $_SESSION['status'] !='') {
                    echo '<h2 class="bg-danger text-white">' .$_SESSION['status'].'</h2>';
                    unset($_SESSION['status']);
                }
                ?>

                <div class="table-responsive">
                    <?php
                    $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

                    // Debugging: Check if the connection is successful
                    if(!$connection) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    $query = "SELECT * FROM category_list ORDER BY id DESC"; // Ordering by ID in descending order
                    $query_run = mysqli_query($connection, $query);

                    // Debugging: Check if the query is executed successfully
                    if(!$query_run) {
                        die("Query failed: " . mysqli_error($connection));
                    }
                    ?>

                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead style="background-color: #259E9E; color: white;">
                            <th> ID </th>
                            <th>Category Name</th>
                        </thead>
                        <tbody>
                            <?php
                            if(mysqli_num_rows($query_run) > 0) {
                                while($row = mysqli_fetch_assoc($query_run)) {
                            ?>    
                            <tr>
                                <td> <?php echo $row['id']; ?></td>
                                <td> <?php echo $row['category_name']; ?></td>
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

        <!-- Logout Modal Popup + Logout Action -->
        <?php
        include 'logout_modal.php';
        ?>

        <?php
        include ('includes/scripts.php');
        include ('includes/footer.php');
        ?>

    
</body>
</html>

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
        "order": [[0, "desc"]] // Sort by the first column (ID) in descending order
        });
    });
</script>

<script>
    function generatePrintableReport() {
        // Redirect to the printable report page without any branch filter
        window.location.href = 'printable_category_report.php';
    }
</script>
