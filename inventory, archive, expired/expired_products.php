<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expired Products</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="need.css">
</head>
<style>
    .dataTables_wrapper {
    margin-top: 20px !important; /* Adjust the value as needed */
}
.container-fluid {
        margin-top: 50px; /* Adjust the value as needed */
    }


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

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this product?</p>
      </div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary mr-2" style="border-radius: 5px; padding: 10px 20px; background-color: #828282; border: none; box-shadow: none;" data-dismiss="modal">Cancel</button>
    <!-- Ensure form submission on button click -->
    <form id="deleteForm" action="delete_expired_products.php" method="POST">
        <input type="hidden" id="delete_id" name="delete_id">
        <button type="submit" name="delete_category_btn" class="btn btn-danger" style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; box-shadow: none;">Delete</button>
    </form>
</div>

      </div>
    </div>
  </div>
</div>

<?php
function getStatusColor($productName, $expiryDate)
{
    $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

    // Check if the product is in the expired_list table
    $check_expired_query = "SELECT COUNT(*) as count FROM expired_list WHERE product_name = '$productName'";
    $check_expired_result = mysqli_query($connection, $check_expired_query);

    if ($check_expired_result) {
        $check_expired_row = mysqli_fetch_assoc($check_expired_result);

        if ($check_expired_row['count'] > 0) {
            // Product is in expired_list, set color to red
            return 'red';
        }
    }

    // Product is not in expired_list, continue with the original logic
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
include('notification_logic2.php');
?>

<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="card shadow nb-4">
        <div class="card-header py-3">
            <h1>Expired Products</h1>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
                
                // Fetch data from the database
                $query = "SELECT expired_list.*, product_list.measurement 
                          FROM expired_list
                          JOIN product_list ON expired_list.product_name = product_list.prod_name";
                $result = mysqli_query($connection, $query);
                ?>
<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead style="background-color: #EB3223; color: white;">
                        <th>ID</th>
                        <th>SKU</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Batch Number</th>

                        <th>Expiry Date</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <tr>
                            <td style="vertical-align: middle;"><?php echo $row['id']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['sku']; ?></td>
<td style="vertical-align: middle;">
    <?php 
    echo $row['product_name']; 
    if(isset($row['measurement'])) {
        echo ' - <span style="font-size: 80%;">' . $row['measurement'] . '</span>';
    }
    ?>
</td>
<td style="vertical-align: middle;"><?php echo $row['quantity']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['price']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['batch_no']; ?></td>
<td style="vertical-align: middle; color: <?php echo getStatusColor($row['product_name'], $row['expiry_date']); ?>;">
    <?php 
        $expiryDate = new DateTime($row['expiry_date'] . ' ');

        // Add one day if the product is in the expired_list table
        if (getStatusColor($row['product_name'], $row['expiry_date']) == 'red') {
            $expiryDate->modify('+1 day');
        }

        $formattedDate = $expiryDate->format('Y-m-d');

        // Apply red color to the entire date text
        echo '<span style="color: red;">' . $formattedDate . '</span>';

        // Add space
        echo ' ';

        // Add Font Awesome icons based on expiration status
        $statusColor = getStatusColor($row['product_name'], $row['expiry_date']);
        if ($statusColor == 'red' || $statusColor == 'orange' || $statusColor == 'green') {
            echo '<i class="fas ';
            if ($statusColor == 'red') {
                echo 'fa-exclamation-circle icon" style="color: red;"></i>';
            } elseif ($statusColor == 'orange') {
                echo 'fa-exclamation-triangle icon" style="color: orange;"></i>';
            } elseif ($statusColor == 'green') {
                echo 'fa-check-circle icon" style="color: green;"></i>';
            }
        }
    ?>
    <div class="overlay">
        <!-- Overlay content based on expiration status -->
        <?php 
            if ($statusColor == 'red') {
                echo "This product has expired!";
            } elseif ($statusColor == 'orange') {
                echo "This product is expiring soon!";
            } elseif ($statusColor == 'green') {
                echo "This product is still valid.";
            }
        ?>
    </div>
</td>

<style>
    .icon {
        position: relative; /* Ensure that the overlay is positioned relative to the td */
    }

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
        display: block; /* Show the overlay when the td is hovered */
        
    }
</style>

<td>
    <button class="btn btn-action" style="border: none; background: none;" data-toggle="modal" data-target="#deleteModal" data-id="<?php echo $row['id']; ?>">
        <i class="fas fa-trash-alt" style="color: #FF0000;"></i>
    </button>
</td>
                            </tr>
                        <?php
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
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('#delete_id').val(id);
    });
</script>

<script>
    document.getElementById('sku_input').addEventListener('change', function() {
        var sku = this.value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'livesearch_product.php?sku=' + sku, true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                var data = JSON.parse(xhr.responseText);
                document.querySelector('[name="product_stock_name"]').value = data.product_name;
                document.querySelector('[name="descript"]').value = data.descript;
            }
        };
        xhr.send();
    });
</script>

<script>
    document.getElementById('sku_input').addEventListener('input', function() {
        var sku = this.value.trim();
        var productNameField = document.querySelector('[name="product_stock_name"]');
        var descriptionField = document.querySelector('[name="descript"]');
        var quantityField = document.querySelector('[name="quantity"]');
        var priceField = document.querySelector('[name="price"]');
        var branchField = document.querySelector('[name="branch"]');
        var expiryDateField = document.querySelector('[name="expiry_date"]');

        if (sku) {
            productNameField.removeAttribute('disabled');
            descriptionField.removeAttribute('disabled');
            quantityField.removeAttribute('disabled');
            priceField.removeAttribute('disabled');
            branchField.removeAttribute('disabled');
            expiryDateField.removeAttribute('disabled');
        } else {
            productNameField.setAttribute('disabled', 'disabled');
            descriptionField.setAttribute('disabled', 'disabled');
            quantityField.setAttribute('disabled', 'disabled');
            priceField.setAttribute('disabled', 'disabled');
            branchField.setAttribute('disabled', 'disabled');
            expiryDateField.setAttribute('disabled', 'disabled');
        }
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
                if (columnIndex === 3 || columnIndex === 4 || columnIndex === 7) { // Columns to sort
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
            "ordering": true, // Enable ordering/sorting
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