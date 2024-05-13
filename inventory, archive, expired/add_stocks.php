<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="need.css">
    <script>
function changeBranch() {
    var selectedBranch = document.getElementById("branch").value;
    window.location.href = 'add_stocks.php?branch=' + selectedBranch;
    } 
    </script>
</head>
<style>

.container-fluid {
        margin-top: 50px; /* Adjust the value as needed */
    }
    .dataTables_wrapper {
    margin-top: 10px !important; /* Adjust the value as needed */
}


    /* Modal styles */
    .modal-content {
        background-color: #f8f9fc; /* Background color */
        border-radius: 10px; /* Rounded corners */
    }

    .modal-header {
        border-bottom: none; /* Remove border at the bottom of the header */
        padding: 15px 20px; /* Add padding */
        background-color: #304B1B; /* Header background color */
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
<body>

<!-- Modal -->
<div class="modal fade" id="confirmArchiveModal" tabindex="-1" role="dialog" aria-labelledby="confirmArchiveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmArchiveModalLabel">Archive Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to archive this item?
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary modal-btn mr-2" style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none;  box-shadow: none; " data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-primary modal-btn" class="btn btn-danger" id="confirmArchiveBtn" style="border-radius: 5px; padding: 10px 20px; background-color: #304B1B; border: none;  box-shadow: none; ">Archive</button>

            </div>
        </div>
    </div>
</div>


<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('includes/header.php');
include('includes/navbar2.php');
include("dbconfig.php");

$selectedBranch = isset($_GET['branch']) ? $_GET['branch'] : 'All';

// Function to check if SKU is unique for the specified product
function isSkuUnique($connection, $sku, $productName)
{
    $query = "SELECT * FROM product_list WHERE sku = '$sku' AND prod_name != '$productName'";
    $result = mysqli_query($connection, $query);
    return mysqli_num_rows($result) === 0; // Returns true if SKU is unique for the product
}

// Function to get the color status based on expiry date
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

// Establish connection to the database
$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

// Retrieve product names from the database
$query = "SELECT prod_name FROM product_list";
$query_run = mysqli_query($connection, $query);
$productNames = array();
while ($row = mysqli_fetch_assoc($query_run)) {
    $productNames[] = $row['prod_name'];
}

// Function to update stocks available in product_list table
function updateStocksAvailable($connection)
{
    // Query to update stocks available for each product
    $query = "UPDATE product_list p
              JOIN (
                  SELECT product_stock_name, SUM(quantity) AS total_stocks 
                  FROM add_stock_list 
                  GROUP BY product_stock_name
              ) s ON p.prod_name = s.product_stock_name
              SET p.stocks_available = s.total_stocks";
    mysqli_query($connection, $query);
}

// Handle form submission for adding stock
if (isset($_POST['add_stock_btn'])) {
    // Extract form data
    $sku = mysqli_real_escape_string($connection, $_POST['sku']);
    $productName = mysqli_real_escape_string($connection, $_POST['product_stock_name']);
    $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
    $price = mysqli_real_escape_string($connection, $_POST['price']);
    $branch = mysqli_real_escape_string($connection, $_POST['branch']);
    $batchNo = mysqli_real_escape_string($connection, $_POST['batch_no']);
    $expiryDate = mysqli_real_escape_string($connection, $_POST['expiry_date']);

    // Check if SKU is unique for the specified product
    if (!isSkuUnique($connection, $sku, $productName)) {
        echo "Error: SKU already exists for a different product.";
        // You can redirect back to the form or handle the error as needed
    } else {
        // Proceed with inserting the new product
        // Your insertion code here

        // Call the function to update stocks available after adding stock
        updateStocksAvailable($connection);

        // Redirect or perform any additional actions
        header('Location: add_stocks.php');
        exit();
    }
}
        
        // Add similar calls to updateStocksAvailable() function after updating or deleting a stock
        ?>
        


<!-- Modal -->
<div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                <div class="form-group">
    <label>SKU</label>
    <input type="text" name="sku" id="sku_input" class="form-control" placeholder="Enter SKU" pattern="[0-9]*" title="SKU should contain only numbers" required />
</div>


    <div class="form-group">          
    <label>Product Name</label>
    <select name="product_stock_name" class="form-control" required disabled>
        <option value="">Select Product</option> <!-- Empty option -->
        <?php
        foreach ($productNames as $productName) {
            $query = "SELECT * FROM product_list WHERE prod_name='$productName'";
            $query_run = mysqli_query($connection, $query);
            $productInfo = mysqli_fetch_assoc($query_run);
            $measurement = $productInfo['measurement'];
            $selected = ($selectedProduct == $productName) ? 'selected' : '';
            echo "<option value='$productName' data-measurement='$measurement' $selected>
                      $productName - <span style='font-size: 80%;'>$measurement</span>
                  </option>";
        }
        ?>
    </select>
</div>
<div class="form-group">
    <label>Quantity</label>
    <input type="number" name="quantity" class="form-control" placeholder="Enter Quantity" required disabled/>
</div>
<div class="form-group">
                        <label>Price</label>
                        <div class="input-group">
                            <input type="number" name="price" class="form-control" placeholder="Enter Price" required disabled />
                            <div class="input-group-append">
                                <span class="input-group-text">.00</span>
                            </div>
                        </div>
                    </div>
<div class="form-group">
    <label>Branch</label>
    <select name="branch" class="form-control" required disabled>
        <option value="" disabled selected>Select Branch</option>
        <option value="Cell Med" <?php echo ($selectedBranch === 'Cell Med') ? 'selected' : ''; ?>>Cell Med</option>
        <option value="3G Med" <?php echo ($selectedBranch === '3G Med') ? 'selected' : ''; ?>>3G Med</option>
        <option value="Boom Care" <?php echo ($selectedBranch === 'Boom Care') ? 'selected' : ''; ?>>Boom Care</option>
    </select>
</div>
<div class="form-group">
    <label>Batch Number</label>
    <select name="batch_no" class="form-control" required disabled style="width: 100%;">
        <option value="">Select Batch Number</option>
        <?php
        $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
        $query = "SELECT * FROM batch_list";
        $query_run = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($query_run)) {
            // Display each batch number without formatting
            echo "<option value='" . $row['batch_name'] . "'>" . $row['batch_name'] . "</option>";
        }
        ?>
    </select>
</div>


<div class="form-group">
    <label>Expiry Date</label>
    <input type="date" name="expiry_date" class="form-control" placeholder="Select Expiry Date" required disabled
        min="<?php echo date('Y-m-d'); ?>" />
</div>


                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-primary modal-btn mr-2" style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none;  box-shadow: none; " data-dismiss="modal">Cancel</button>
<button type="submit" name="add_stock_btn" class="btn btn-primary modal-btn" style="border-radius: 5px; padding: 10px 20px; background-color: #304B1B; border: none; box-shadow: none;">Save</button>


                </div>
            </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card shadow nb-4">
        <div class="card-header py-3">
        <h1>Stocks Available</h1>
        </div>
        <div class="card-body">

        <h6 class="m-0 font-weight-bold text-primary">
            <button type="button" class="btn btn-primary rounded-pill shadow" data-toggle="modal" data-target="#addadminprofile" style="background-color: #304B1B; border: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
            <i class="fas fa-plus-square fa-lg"></i> <span style="font-weight: bold; text-transform: uppercase;">Add Stock</span>
                </button>
            </h6>
            <div class="form-group">
            <label class="modal-label" style="color: #304B1B; top: 5px; padding-top: 15px;"> Branch</label>
        <select id="branch" name="branch" class="form-control" onchange="changeBranch()">
            <option value="All" <?php if($selectedBranch == 'All') echo 'selected'; ?>>All</option>
            <option value="Cell Med" <?php if($selectedBranch == 'Cell Med') echo 'selected'; ?>>Cell Med</option>
            <option value="3G Med" <?php if($selectedBranch == '3G Med') echo 'selected'; ?>>3G Med</option>
            <option value="Boom Care" <?php if($selectedBranch == 'Boom Care') echo 'selected'; ?>>Boom Care</option>
        </select>
    </div>
            <div class="table-responsive">

                <?php                   
$query = "SELECT add_stock_list.*, product_list.measurement 
FROM add_stock_list
JOIN product_list ON add_stock_list.product_stock_name = product_list.prod_name
WHERE ('$selectedBranch' = 'All' OR add_stock_list.branch = '$selectedBranch')
ORDER BY id DESC"; // Fetching in descending order of ID
$query_run = mysqli_query($connection, $query);


                ?>
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead style="background-color: #304B1B; color: white;">
                <th> ID </th>
                        <th> SKU </th>
                   <!-- <th> Purchase Price </th> -->
                        <th> Product Name </th>
                        <th> Quantity </th>
                        <th> Price </th>
                        <th> Branch </th>
                        <th> Batch Number </th>
                        <th> Expiry Date </th>
                        <th> Date Added </th>
                        <th> Action </th>
                       
                    </thead>
                    <tbody>
                    <?php
                    if (mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                    ?>
                        <tr>
    <td style="vertical-align: middle;"><?php echo $row['id']; ?></td>
    <td style="vertical-align: middle;"><?php echo $row['sku']; ?></td>
    <td style="vertical-align: middle;"><?php echo $row['product_stock_name']; ?> - <span style='font-size: 80%;'><?php echo $row['measurement']; ?></span></td>
    <td style="vertical-align: middle;"><?php echo $row['quantity']; ?></td>
    <td style="vertical-align: middle;"><?php echo $row['price']; ?></td>
    <td style="vertical-align: middle;"><?php echo $row['branch']; ?></td>
    <td style="vertical-align: middle;"><?php echo $row['batch_no']; ?></td>
<td style="vertical-align: middle; color: <?php echo getStatusColor($row['expiry_date']); ?>;">

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
<td style="vertical-align: middle;"><?php echo $row['date_added']; ?></td>
                            <td> 
                            <form action="edit_stock_product.php" method="post" style="display: inline-block;">
    <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
    <button type="submit" name="edit_btn" class="btn btn-action editBtn">
        <i class="fas fa-edit" style="color: #44A6F1;"></i>
    </button>
</form>
<span class="action-divider">|</span>
<form action="move.php" method="POST" id="archiveForm" style="display: inline-block;">
    <input type="hidden" name="archive_id" id="archiveId" value="">
    <button type="button" class="btn btn-action" data-toggle="modal" data-target="#confirmArchiveModal" data-id="<?php echo $row['id']; ?>">
        <i class="fas fa-archive" style="color: #FF0000;"></i>
    </button>
</form>

</td>
                        <?php
                        }
                    } else{
                        echo "No record Found";
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
// Function to move expired products to the "Expired Products" table
function moveExpiredProducts() {
    $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query to select expired products
    $expiredProductsQuery = "SELECT * FROM add_stock_list WHERE expiry_date <= CURDATE()";
    $expiredProductsResult = mysqli_query($connection, $expiredProductsQuery);

    if (!$expiredProductsResult) {
        die("Query failed: " . mysqli_error($connection));
    }

    // Loop through expired products and move them to the "Expired Products" table
    while ($expiredProduct = mysqli_fetch_assoc($expiredProductsResult)) {
        // Move expired product to the "Expired Products" table
        $moveToExpiredQuery = "INSERT INTO expired_list (sku, product_name, quantity, price, batch_no, stocks_available, expiry_date)
                             VALUES ('{$expiredProduct['sku']}', '{$expiredProduct['product_stock_name']}', '{$expiredProduct['quantity']}', '{$expiredProduct['price']}', '{$expiredProduct['batch_no']}', '{$expiredProduct['stocks_available']}', '{$expiredProduct['expiry_date']}')";
        $moveResult = mysqli_query($connection, $moveToExpiredQuery);

        if (!$moveResult) {
            die("Move query failed: " . mysqli_error($connection));
        }

        // Delete expired product from the original table
        $deleteExpiredQuery = "DELETE FROM add_stock_list WHERE id = {$expiredProduct['id']}";
        $deleteResult = mysqli_query($connection, $deleteExpiredQuery);

        if (!$deleteResult) {
            die("Delete query failed: " . mysqli_error($connection));
        }
    }

    mysqli_close($connection);
}

// Call the function to move expired products
moveExpiredProducts();
?>




        <!-- Logout Modal Popup + Logout Action -->
        <?php
            include 'logout_modal.php';
        ?>

        <?php
        include('includes/scripts.php');
        include('includes/footer.php');
        ?>
    </body>


    <script>
    $(document).ready(function() {
        // Pass archive ID to modal when button is clicked
        $('#confirmArchiveModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var archiveId = button.data('id');
            $('#archiveId').val(archiveId);
        });

        // Handle form submission upon confirmation
        $('#confirmArchiveBtn').click(function() {
            $('#archiveForm').submit();
        });
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
            }
        };
        xhr.send();
    });
</script>

<script>
document.getElementById('sku_input').addEventListener('input', function() {
    var sku = this.value.trim();
    var productNameField = document.querySelector('[name="product_stock_name"]');
    var quantityField = document.querySelector('[name="quantity"]');
    var priceField = document.querySelector('[name="price"]');
    var branchField = document.querySelector('[name="branch"]');
    var batchNoField = document.querySelector('[name="batch_no"]');
    var expiryDateField = document.querySelector('[name="expiry_date"]');

    if (sku) {
        productNameField.removeAttribute('disabled');
        quantityField.removeAttribute('disabled');
        priceField.removeAttribute('disabled');
        branchField.removeAttribute('disabled');
        batchNoField.removeAttribute('disabled'); // Enable batch number field
        expiryDateField.removeAttribute('disabled');
    } else {
        productNameField.setAttribute('disabled', 'disabled');
        quantityField.setAttribute('disabled', 'disabled');
        priceField.setAttribute('disabled', 'disabled');
        branchField.setAttribute('disabled', 'disabled');
        batchNoField.setAttribute('disabled', 'disabled'); // Disable batch number field
        expiryDateField.setAttribute('disabled', 'disabled');
    }
});

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
        "pagingType": "simple", // Set the pagination type to simple
        "columnDefs": [
            { "orderable": false, "targets": [1, 3, 4, 5, 6, 7, 8, 9] } // Disable sorting for all columns except ID, SKU, and Product Name
        ],
        "order": [[0, "desc"]] // Sort by the first column (ID) in descending order
    });
});

</script>
