<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="need.css">
</head>
<style>
        /* Modal styles */
        .modal-content {
        background-color: #f8f9fc; /* Background color */
        border-radius: 10px; /* Rounded corners */
    }

    .modal-header {
        border-bottom: none; /* Remove border at the bottom of the header */
        padding: 15px 20px; /* Add padding */
        background-color: #EB3223; /* Header background color */
        color: #fff; /* Header text color */
        border-radius: 10px 10px 0 0; /* Rounded corners only at the top */
    }

    .modal-body {
        padding: 20px; /* Add padding */
    }

    .modal-footer {
        border-top: none; /* Remove border at the top of the footer */
        padding: 15px 20px; /* Add padding */
        background-color: #f8f9fc; /* Footer background color */
        border-radius: 0 0 10px 10px; /* Rounded corners only at the bottom */
    }

    /* Close button style */
    .modal-header .close {
        display: none;
    }

    .modal-body label {
        color: #304B1B;
        font-weight: bold;
    }
    
    </style>
</html>

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Delete Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this item?
      </div>
      <div class="modal-footer">
    <button type="button" class="btn btn-secondary mr-2" style="border-radius: 5px; padding: 10px 20px; background-color: #828282; border: none; box-shadow: none;" data-dismiss="modal">Cancel</button>
    <!-- Ensure form submission on button click -->
    <form id="deleteForm" action="delete_archive.php" method="POST">
        <input type="hidden" id="delete_id" name="delete_id">
        <button type="submit" name="delete_category_btn" class="btn btn-danger" style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; box-shadow: none;">Delete</button>
    </form>
</div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmRestoreModal" tabindex="-1" role="dialog" aria-labelledby="confirmRestoreModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    <div class="modal-header" style="background-color: #304B1B;">
        <h5 class="modal-title" id="confirmRestoreModalLabel">Restore Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to restore this item?
      </div>
      <div class="modal-footer">
    <button type="button" class="btn btn-secondary mr-2" style="border-radius: 5px; padding: 10px 20px; background-color: #828282; border: none; box-shadow: none;" data-dismiss="modal">Cancel</button>
    <!-- Ensure form submission on button click -->
    <form id="moveForm" action="move_inventory.php" method="POST">
    <input type="hidden" id="restore_id" name="restore_id">
    <button type="submit" name="restore_category_btn" class="btn btn-success" style="border-radius: 5px; padding: 10px 20px; background-color: #304B1B; border: none; box-shadow: none;">Restore</button>
    </form>
</div>
    </div>
  </div>
</div>

<?php
function getStatusColor($expiryDate)
{
    $currentDate = date('Y-m-d');
    $expiryDateObj = new DateTime($expiryDate);
    $currentDateObj = new DateTime($currentDate);

    if ($expiryDateObj < $currentDateObj) {
        // Expired (red)
        return 'red';
    } else {
        $daysDifference = $currentDateObj->diff($expiryDateObj)->days;

        if ($daysDifference <= 7) {
            // Expiring within a week (orange)
            return 'orange';
        } else {
            // Still valid (green)
            return 'green';
        }
    }
}

?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('includes/header.php');
include('includes/navbar2.php');
?>

<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="card shadow nb-4">
        <div class="card-header py-3">
            <h1>Archive Product</h1>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <?php
$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

// Update stocks_available based on the sum of quantities for each product


// Fetch updated data
$query = "SELECT archive_list.id, 
                 archive_list.sku,   
                 archive_list.product_name,
                 archive_list.quantity, 
                 archive_list.price, 
                 archive_list.branch, 
                 archive_list.batch_no, 
                 archive_list.expiry_date, 
                 product_list.measurement
          FROM archive_list
          JOIN product_list ON archive_list.product_name = product_list.prod_name";

$query_run = mysqli_query($connection, $query);
?>


                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead style="background-color: #828282; color: white;">
                        <th>ID</th>
                        <th>SKU</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Branch</th>
                        <th>Batch Number</th>
                        <th>Expiry Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                        ?>
                                <tr>
                                <td style="vertical-align: middle;"><?php echo $row['id']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['sku']; ?></td>
<td style="vertical-align: middle;">
    <?php echo $row['product_name']; ?> -
    <span style='font-size: 80%;'>
        <?php echo $row['measurement']; ?>
    </span>
</td>
<td style="vertical-align: middle;"><?php echo $row['quantity']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['price']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['branch']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['batch_no']; ?></td>
<td style="color: <?php echo getStatusColor($row['expiry_date']); ?>; vertical-align: middle;">
    <?php 
        echo $row['expiry_date'] . ' '; 
        // Add Font Awesome icons based on expiration status
        if (getStatusColor($row['expiry_date']) == 'red') {
            echo '<i class="fas fa-exclamation-circle icon" style="color: red;"></i>'; // Add a class for the icon
        } elseif (getStatusColor($row['expiry_date']) == 'orange') {
            echo '<i class="fas fa-exclamation-triangle icon" style="color: orange;"></i>'; // Add a class for the icon
        } elseif (getStatusColor($row['expiry_date']) == 'green') {
            echo '<i class="fas fa-check-circle icon" style="color: green;"></i>'; // Add a class for the icon
        }
    ?>
    <div class="overlay">
        <!-- Overlay content based on expiration status -->
        <?php 
            if (getStatusColor($row['expiry_date']) == 'red') {
                echo "This product has expired!";
            } elseif (getStatusColor($row['expiry_date']) == 'orange') {
                echo "This product is expiring soon!";
            } elseif (getStatusColor($row['expiry_date']) == 'green') {
                echo "This product is still valid.";
            }
        ?>
    </div>
</td>

<style>
    .overlay {
        position: absolute;
        background-color: black;
        color: white;
        padding: 5px;
        border-radius: 5px;
        z-index: 1;
        display: none; /* Initially hide the overlay */
    }

    .icon:hover + .overlay {
        display: block; /* Show the overlay when the icon is hovered */
    }
</style>
   <td>
   <button class="btn btn-action" style="border: none; background: none;" data-toggle="modal" data-target="#confirmDeleteModal" data-id="<?php echo $row['id']; ?>">
        <i class="fas fa-trash-alt" style="color: #FF0000;"></i>
    </button>
<span class="action-divider">|</span>
    <button class="btn btn-action" style="border: none; background: none;" data-toggle="modal" data-target="#confirmRestoreModal" data-id="<?php echo $row['id']; ?>">
        <i class="fas fa-undo" style="color: #219653;"></i>
    </button>
</form>
</td>
                                </tr>
                        <?php
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

    <!-- Logout Modal Popup + Logout Action -->
    <?php
        include 'logout_modal.php';
    ?>

    <?php
    include('includes/scripts.php');
    include('includes/footer.php');
    ?>

<script>
    // Set the delete ID when modal is shown
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id'); // Extract info from data-* attributes
        var modal = $(this);
        modal.find('#delete_id').val(id);
    });
</script>

<script>
    // Set the restore ID when modal is shown
    $('#confirmRestoreModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id'); // Extract info from data-* attributes
        var modal = $(this);
        modal.find('#restore_id').val(id);
    });
</script>
<script>
    var ascending = true;

    function sortTable(columnIndex) {
        var table, rows, switching, i, x, y, shouldSwitch;
        table = document.getElementById("dataTable");
        switching = true;
        var icon = document.getElementById("sortIcon");
        if (ascending) {
            icon.classList.remove("fa-sort");
            icon.classList.add("fa-sort-up");
        } else {
            icon.classList.remove("fa-sort-up");
            icon.classList.add("fa-sort-down");
        }
        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[columnIndex];
                y = rows[i + 1].getElementsByTagName("TD")[columnIndex];
                if (ascending) {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
            }
        }
        ascending = !ascending;
    }
</script>
        <!-- DataTables JavaScript -->
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
        <script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "pageLength": 10, // Display 10 entries per page
            "searching": true,
            "ordering": true, // Disable ordering/sorting
            "info": true,
            "autoWidth": false,
            "language": {
                "paginate": {
                    "previous": "<i class='fas fa-arrow-left'></i>", // Use arrow-left icon for previous button
                    "next": "<i class='fas fa-arrow-right'></i>" // Use arrow-right icon for next button
                }
            },
            "pagingType": "simple" // Set the pagination type to simple
        });
    });
</script>
