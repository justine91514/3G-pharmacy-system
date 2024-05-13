<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="om.css">
    <script>
        function changeBranch() {
            var selectedBranch = document.getElementById("branch").value;
            window.location.href = 'print_inventory_report.php?branch=' + selectedBranch;
        }
    </script>
</head>
<style>
    .dataTables_wrapper {
        margin-top: 10px !important;
        /* Adjust the value as needed */
    }

    .container-fluid {
        margin-top: 100px;
        /* Adjust the value as needed */
    }

    table.dataTable th {
        border: none !important;
    }
</style>

<body>

    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include ('includes/header.php');
    include ('includes/navbar2.php');
    include ("dbconfig.php");
    include ('activity_logs.php');

    $selectedBranch = isset($_GET['branch']) ? $_GET['branch'] : 'All';

    ?>
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

    $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
    $query = "SELECT prod_name FROM product_list";
    $query_run = mysqli_query($connection, $query);
    $productNames = array();
    while ($row = mysqli_fetch_assoc($query_run)) {
        $productNames[] = $row['prod_name'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selectedProduct = $_POST['product_stock_name'];
    }
    $expired_products_query = "SELECT * FROM add_stock_list WHERE expiry_date < CURDATE()";
    $expired_products_result = mysqli_query($connection, $expired_products_query);

    while ($expired_product = mysqli_fetch_assoc($expired_products_result)) {
        // Move expired product to expired_list
        $move_to_expired_query = "INSERT INTO expired_list (sku, product_name, descript, quantity, stocks_available, price, expiry_date)
                             VALUES ('{$expired_product['sku']}', '{$expired_product['product_stock_name']}','{$expired_product['descript']}',  '{$expired_product['quantity']}', '{$expired_product['stocks_available']}', '{$expired_product['price']}', '{$expired_product['expiry_date']}')";
        mysqli_query($connection, $move_to_expired_query);

        // Delete expired product from add_stock_list
        $delete_expired_query = "DELETE FROM add_stock_list WHERE id = {$expired_product['id']}";
        mysqli_query($connection, $delete_expired_query);
    }
    // Update stocks_available separately for each branch or all branches
    $update_stocks_query = "UPDATE add_stock_list a
                        JOIN (
                            SELECT product_stock_name, branch, SUM(quantity) as total_quantity
                            FROM add_stock_list
                            GROUP BY product_stock_name, branch
                        ) t ON a.product_stock_name = t.product_stock_name AND a.branch = t.branch
                        SET a.stocks_available = t.total_quantity
                        WHERE ('$selectedBranch' = 'All' OR a.branch = '$selectedBranch')";
    mysqli_query($connection, $update_stocks_query);

    $expiring_soon_query = "SELECT COUNT(*) as expiring_soon_count FROM add_stock_list WHERE expiry_date BETWEEN CURDATE() AND CURDATE() + INTERVAL 7 DAY";
    $expiring_soon_result = mysqli_query($connection, $expiring_soon_query);
    $expiring_soon_count = 0;

    if ($expiring_soon_result) {
        $expiring_soon_row = mysqli_fetch_assoc($expiring_soon_result);
        $expiring_soon_count = $expiring_soon_row['expiring_soon_count'];
    }



    updateStocksAvailable();



    function updateStocksAvailable()
    {
        $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

        // Query to get total stocks available for each product
        $query = "SELECT product_stock_name, SUM(quantity) AS total_stocks FROM add_stock_list GROUP BY product_stock_name";
        $result = mysqli_query($connection, $query);

        // Loop through the result to update stocks available in the product_list table
        while ($row = mysqli_fetch_assoc($result)) {
            $product_stock_name = $row['product_stock_name'];
            $total_stocks = $row['total_stocks'];

            // Query to update stocks available in the product_list table
            $update_query = "UPDATE product_list SET stocks_available = $total_stocks WHERE prod_name = '$product_stock_name'";
            mysqli_query($connection, $update_query);
        }
    }
    ?>


    <!-- Modal -->
    <div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                            <input type="text" name="sku" id="sku_input" class="form-control" placeholder="Enter SKU"
                                required />
                        </div>
                        <div class="form-group">
                            <label>Purchase Price</label>
                            <input type="text" name="purchase_price" class="form-control"
                                placeholder="Enter Purchase Price" required />
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
                            <label>Description</label>
                            <input type="text" name="descript" class="form-control" placeholder="Enter Description"
                                required disabled />
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" name="quantity" class="form-control" placeholder="Enter Quantity"
                                required disabled />
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control" placeholder="Enter Price" required
                                disabled />
                        </div>
                        <div class="form-group">
                            <label>Branch</label>
                            <select name="branch" class="form-control" required disabled>
                                <option value="" disabled selected>Select Branch</option>
                                <option value="Cell Med" <?php echo ($selectedBranch === 'Cell Med') ? 'selected' : ''; ?>>Cell Med</option>
                                <option value="3G Med" <?php echo ($selectedBranch === '3G Med') ? 'selected' : ''; ?>>3G
                                    Med</option>
                                <option value="Boom Care" <?php echo ($selectedBranch === 'Boom Care') ? 'selected' : ''; ?>>Boom Care</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" class="form-control" placeholder="Select Expiry Date"
                                required disabled min="<?php echo date('Y-m-d'); ?>" />
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add_stock_btn" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card shadow nb-4">
            <div class="card-header py-3">
                <h1>Inventory Report</h1>
            </div>
            <div class="card-body">
                <h6 class="m-0 font-weight-bold text-primary">
                    <button type="button" class="btn btn-primary"
                        onclick="generatePrintableReport('<?php echo $selectedBranch; ?>'); logActivity('Print Table Opened')"
                        style="background-color: #304B1B; border: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                        <i class="fas fa-print fa-lg"></i> <span
                            style="font-weight: bold; text-transform: uppercase;">Print Table</span>
                    </button>

                </h6>

                <div class="form-group">
                    <label class="modal-label" style="color: black; top: 5px; padding-top: 15px;"> Branch</label>
                    <select id="branch" name="branch" class="form-control" onchange="changeBranch()">
                        <option value="All" <?php if ($selectedBranch == 'All')
                            echo 'selected'; ?>>All</option>
                        <option value="Cell Med" <?php if ($selectedBranch == 'Cell Med')
                            echo 'selected'; ?>>Cell Med
                        </option>
                        <option value="3G Med" <?php if ($selectedBranch == '3G Med')
                            echo 'selected'; ?>>3G Med</option>
                        <option value="Boom Care" <?php if ($selectedBranch == 'Boom Care')
                            echo 'selected'; ?>>Boom Care
                        </option>
                    </select>
                </div>

                <div class="table-responsive">

                    <?php
                    $query = "SELECT add_stock_list.*, product_list.measurement 
FROM add_stock_list
JOIN product_list ON add_stock_list.product_stock_name = product_list.prod_name
WHERE ('$selectedBranch' = 'All' OR add_stock_list.branch = '$selectedBranch')
ORDER BY id DESC"; // Assuming 'id' is the column representing the last added items
                    $query_run = mysqli_query($connection, $query);

                    ?>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead style="background-color: #304B1B; color: white;">
                        <th style="vertical-align: middle;">ID</th>
                            <th style="vertical-align: middle;">SKU</th>
                            <!-- <th style="vertical-align: middle;">Purchase Price</th> -->
                            <th style="vertical-align: middle;">Product Name</th>
                            <th style="vertical-align: middle;">Description</th>
                            <th style="vertical-align: middle;">Quantity</th>
                            <th style="vertical-align: middle;">Price</th>
                            <th style="vertical-align: middle;">Branch</th>
                            <th style="vertical-align: middle;">Batch No.</th>
                            <th style="vertical-align: middle;">Expiry Date</th>
                            <th style="vertical-align: middle;">Date Added</th>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    ?>
                                    <tr>
                                    <td style="vertical-align: middle;"><?php echo $row['id']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['sku']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['product_stock_name']; ?> - <span style="font-size: 80%;"><?php echo $row['measurement']; ?></span></td>
<td style="vertical-align: middle;"><?php echo $row['descript']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['quantity']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['price']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['branch']; ?></td>
<td style="vertical-align: middle;"><?php echo $row['batch_number']; ?></td>
<td style="vertical-align: middle; color: <?php echo getStatusColor($row['expiry_date']); ?>;">
    <?php
    echo $row['expiry_date'];
    // Add Font Awesome icons based on expiration status
    if (getStatusColor($row['expiry_date']) == 'red') {
        echo ' <i class="fas fa-exclamation-circle" style="color: red;"></i>';
    } elseif (getStatusColor($row['expiry_date']) == 'orange') {
        echo ' <i class="fas fa-exclamation-triangle" style="color: orange;"></i>';
    } elseif (getStatusColor($row['expiry_date']) == 'green') {
        echo ' <i class="fas fa-check-circle" style="color: green;"></i>';
    }
    ?>
</td>
<td style="vertical-align: middle;"><?php echo $row['date_added']; ?></td>

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
<script>
    document.getElementById('sku_input').addEventListener('change', function () {
        var sku = this.value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'livesearch_product.php?sku=' + sku, true);
        xhr.onload = function () {
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
    document.getElementById('sku_input').addEventListener('input', function () {
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



<!-- DataTables JavaScript -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
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
            "order": [[0, "desc"]], // Sort by the first column (ID) in descending order
            "columnDefs": [
                { "orderable": true, "targets": [0, 2] }, // ID and Product Name columns are sortable
                { "orderable": false, "targets": '_all' } // Disable sorting for all other columns
            ]
        });
    });
</script>

<script>
    function generatePrintableReport(selectedBranch) {
        // Redirect to the printable report page with the selected branch
        window.location.href = 'printable_inventory_report.php?branch=' + selectedBranch;
    }
</script>