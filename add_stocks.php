<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="om.css">
    <script>
        function changeTableFormat() {
            var selectedBranch = document.getElementById("branch").value;
            window.location.href = 'add_stocks.php?branch=' + selectedBranch;
        }
    </script>
</head>
<style>
    .container-fluid {
        margin-top: 100px;
        /* Adjust the value as needed */
    }

    .dataTables_wrapper {
        margin-top: 10px !important;
        /* Adjust the value as needed */
    }


    /* Modal styles */
    .modal-content {
        background-color: #f8f9fc;
        /* Background color */
        border-radius: 10px;
        /* Rounded corners */
    }

    .modal-header {
        border-bottom: none;
        /* Remove border at the bottom of the header */
        padding: 15px 20px;
        /* Add padding */
        background-color: #304B1B;
        /* Header background color */
        color: #fff;
        /* Header text color */
        border-radius: 10px 10px 0 0;
        /* Rounded corners only at the top */
    }

    .modal-body {
        padding: 20px;
        /* Add padding */
    }

    .modal-footer {
        border-top: none;
        /* Remove border at the top of the footer */
        padding: 15px 20px;
        /* Add padding */
        background-color: #f8f9fc;
        /* Footer background color */
        border-radius: 0 0 10px 10px;
        /* Rounded corners only at the bottom */
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
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include ('includes/header.php');
    include ('includes/navbar2.php');
    include ("dbconfig.php");
    include ("notification_logic2.php");

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

    $query = "SELECT supplier_name FROM supplier_list";
    $query_run = mysqli_query($connection, $query);
    $suppNames = array();
    while ($row = mysqli_fetch_assoc($query_run)) {
        $suppNames[] = $row['supplier_name'];
    }

    $query = "SELECT description FROM product_list";
    $query_run = mysqli_query($connection, $query);
    $descNames = array();
    while ($row = mysqli_fetch_assoc($query_run)) {
        $descNames[] = $row['description'];
    }





    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $selectedProduct = $_POST['product_stock_name'];
    }
    $expired_products_query = "SELECT * FROM add_stock_list WHERE expiry_date < CURDATE()";
    $expired_products_result = mysqli_query($connection, $expired_products_query);

    while ($expired_product = mysqli_fetch_assoc($expired_products_result)) {
        // Move expired product to expired_list
        $move_to_expired_query = "INSERT INTO expired_list (sku, product_name, measurement, descript, quantity, price, Supplier, expiry_date)
                             VALUES ('{$expired_product['sku']}', '{$expired_product['product_stock_name']}', '{$expired_product['measurement']}',  '{$expired_product['descript']}',  '{$expired_product['quantity']}', '{$expired_product['price']}', '{$expired_product['supp_name']}', '{$expired_product['expiry_date']}')";
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
                    <h5 class="modal-title" id="exampleModalLabel">Add Stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="code.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>SKU</label>
                            <input type="number" name="sku" id="sku_input" class="form-control" placeholder="Input SKU"
                                required />
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

                                    $selected = ($selectedProduct == $productName) ? 'selected' : '';
                                    echo "<option value='$productName' $selected>
                      $productName </span>
                  </option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Measurement</label>
                            <input type="text" name="measurement" class="form-control" placeholder="Input Measurement"
                                required disabled />
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="descript" class="form-control" placeholder="Input Description"
                                required disabled />
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" id="quantityInput" name="quantity" class="form-control"
                                placeholder="Input Quantity" required disabled />
                            <span id="quantityWarning" style="color: red; display: none;">Quantity must be a positive
                                number and should not start with zero.</span>
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" id="priceInput" name="price" class="form-control"
                                placeholder="Input Price" required disabled />
                            <span id="priceWarning" style="color: red; display: none;">Price must be a positive number
                                and should not start with zero.<span>
                        </div>

                        <div class="form-group">
                            <label>Supplier Name</label>
                            <select name="supp_name" class="form-control" required disabled>
                                <option value="" disabled selected>Select Supplier</option>
                                <?php
                                foreach ($suppNames as $suppName) {
                                    $selected = ($selectedSupplier == $suppName) ? 'selected' : '';
                                    echo "<option value='$suppName' $selected>
                                    $suppName </span>
                  </option>";
                                }
                                ?>
                            </select>
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
                        <button type="button" class="btn btn-primary modal-btn mr-2"
                            style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none;  box-shadow: none; "
                            data-dismiss="modal">Cancel</button>
                        <button id="submitButton" name="add_stock_btn" class="btn btn-primary modal-btn"
                            style="border-radius: 5px; padding: 10px 20px; background-color: #304B1B; border: none; box-shadow: none;">Save</button>
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
                    <button type="button" class="btn btn-primary rounded-pill shadow" data-toggle="modal"
                        data-target="#addadminprofile"
                        style="background-color: #304B1B; border: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                        <i class="fas fa-plus-square fa-lg"></i> <span
                            style="font-weight: bold; text-transform: uppercase;">Add Stock</span>
                    </button>
                </h6>
                <div class="form-group">
                    <label class="modal-label" style="color: black; top: 5px; padding-top: 15px;"> Branch</label>
                    <select id="branch" name="branch" class="form-control" onchange="changeTableFormat()" required>
                        <option value="All" <?php echo ($selectedBranch === 'All') ? 'selected' : ''; ?>>All</option>
                        <option value="Cell Med" <?php echo ($selectedBranch === 'Cell Med') ? 'selected' : ''; ?>>Cell
                            Med</option>
                        <option value="3G Med" <?php echo ($selectedBranch === '3G Med') ? 'selected' : ''; ?>>3G Med
                        </option>
                        <option value="Boom Care" <?php echo ($selectedBranch === 'Boom Care') ? 'selected' : ''; ?>>Boom
                            Care</option>
                    </select>
                </div>
                <div class="table-responsive" style="max-width: 100%; overflow-x: auto;">
                    <?php
                    $query = "SELECT add_stock_list.*
                   FROM add_stock_list
                   JOIN product_list ON add_stock_list.product_stock_name = product_list.prod_name
                   WHERE ('$selectedBranch' = 'All' OR add_stock_list.branch = '$selectedBranch')";
                    $query_run = mysqli_query($connection, $query);
                    ?>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead style="background-color: #304B1B; color: white;">
                            <th style="vertical-align: middle;">ID</th>
                            <th style="vertical-align: middle;">SKU</th>
                            <!-- <th style="vertical-align: middle;">Purchase Price</th> -->
                            <th style="vertical-align: middle;">Product Name</th>
                            <th style="vertical-align: middle;">Measurement</th>
                            <th style="vertical-align: middle;">Description</th>
                            <th style="vertical-align: middle;">Quantity</th>
                            <th style="vertical-align: middle;">Price</th>
                            <th style="vertical-align: middle;">Supplier</th>
                            <th style="vertical-align: middle;">Branch</th>
                            <th style="vertical-align: middle;">Batch No.</th>
                            <th style="vertical-align: middle;">Expiry Date</th>
                            <th style="vertical-align: middle;">Date Added</th>
                            <th style="vertical-align: middle;">Action</th>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    $quantity = $row['quantity'];
                                    $quantityClass = ($quantity < 20) ? 'low-quantity' : '';

                                    if ($quantity == 0) {

                                        // Move the data to the out_of_stock_list table
                                        $move_to_out_of_stock_query = "INSERT INTO out_of_stock_list (sku, product_stock_name, descript, quantity, price, Supplier, branch, batch_number, expiry_date, date_added)
                                                                       VALUES ('{$row['sku']}', '{$row['product_stock_name']}', '{$row['descript']}', '{$row['quantity']}', '{$row['price']}',  '{$row['supp_name']}', '{$row['branch']}', '{$row['batch_number']}', '{$row['expiry_date']}', '{$row['date_added']}')";
                                        mysqli_query($connection, $move_to_out_of_stock_query);

                                        // Delete the data from the add_stock_list table
                                        $delete_query = "DELETE FROM add_stock_list WHERE id = {$row['id']}";
                                        mysqli_query($connection, $delete_query);
                                    }
                                    ?>
                                    <?php
                                    if ($row['quantity'] != 0) {
                                        echo '<tr>';
                                        echo '<td style="vertical-align: middle;">' . $row['id'] . '</td>';
                                        echo '<td style="vertical-align: middle;">' . $row['sku'] . '</td>';
                                        echo '<td style="vertical-align: middle;">' . $row['product_stock_name'] . '</td>';
                                        echo '<td style="vertical-align: middle;">' . $row['measurement'] . '</td>';
                                        echo '<td style="vertical-align: middle;">' . $row['descript'] . '</td>';
                                        echo '<td style="vertical-align: middle;">';
                                        if ($row['quantity'] < 20) {
                                            echo '<span style="color: orange;">' . $row['quantity'] . ' <i class="fas fa-exclamation-triangle icon" style="color: orange; margin-left: -4px;"></i></span>';
                                        } else {
                                            echo '<span style="color: green;">' . $row['quantity'] . ' <i class="fas fa-check-circle icon" style="color: green; margin-left: -4px;"></i></span>';
                                        }
                                        echo '</td>';
                                        echo '<td style="vertical-align: middle;">' . $row['price'] . '</td>';
                                        echo '<td style="vertical-align: middle;">' . $row['supp_name'] . '</td>';
                                        echo '<td style="vertical-align: middle;">' . $row['branch'] . '</td>';
                                        echo '<td style="vertical-align: middle;">' . $row['batch_number'] . '</td>';
                                        echo '<td style="vertical-align: middle; color: ' . getStatusColor($row['expiry_date']) . ';">';
                                        echo $row['expiry_date'] . ' ';
                                        if (getStatusColor($row['expiry_date']) == 'red') {
                                            echo '<i class="fas fa-exclamation-circle icon" style="color: red; margin-left: -5px;"></i>';
                                        } elseif (getStatusColor($row['expiry_date']) == 'orange') {
                                            echo '<i class="fas fa-exclamation-triangle icon" style="color: orange; margin-left: -5px;"></i>';
                                        } elseif (getStatusColor($row['expiry_date']) == 'green') {
                                            echo '<i class="fas fa-check-circle icon" style="color: green; margin-left: -5px;"></i>';
                                        }
                                        echo '<div class="overlay">';
                                        if (getStatusColor($row['expiry_date']) == 'red') {
                                            //echo "This product has expired!";
                                        } elseif (getStatusColor($row['expiry_date']) == 'orange') {
                                            //echo "This product is expiring soon!";
                                        } elseif (getStatusColor($row['expiry_date']) == 'green') {
                                            //echo "This product is still valid.";
                                        }
                                        echo '</div>';
                                        echo '</td>';
                                        echo '<td style="vertical-align: middle;">' . $row['date_added'] . '</td>';
                                        echo '<td>';
                                        echo '<form action="edit_stock_product.php" method="post" style="display: inline-block; margin-left: -9px;">';
                                        echo '<input type="hidden" name="edit_id" value="' . $row['id'] . '">';
                                        echo '<button type="submit" name="edit_btn" class="btn btn-action editBtn">';
                                        echo '<i class="fas fa-edit" style="color: #44A6F1;"></i>';
                                        echo '</button>';
                                        echo '</form>';
                                        echo '<span class="action-divider" style="margin-left: -10px;">|</span>';
                                        echo '<form action="archivemove.php" method="POST" id="archiveForm" style="display: inline-block; margin-left: -10px;">';
                                        echo '<input type="hidden" name="archive_id" id="archiveId" value="' . $row['id'] . '">';
                                        echo '<button type="button" class="btn btn-action" data-toggle="modal" data-target="#confirmArchiveModal" data-id="' . $row['id'] . '">';
                                        echo '<i class="fas fa-archive" style="color: #FF0000;"></i>';
                                        echo '</button>';
                                        echo '</form>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>

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
            <!-- Modal -->
            <div class="modal fade" id="confirmArchiveModal" tabindex="-1" role="dialog"
                aria-labelledby="confirmArchiveModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmArchiveModalLabel">Archive Confirmation</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to archive this stock?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary modal-btn mr-2"
                                style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none;  box-shadow: none; "
                                data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary modal-btn" class="btn btn-danger"
                                id="confirmArchiveBtn"
                                style="border-radius: 5px; padding: 10px 20px; background-color: #304B1B; border: none;  box-shadow: none; ">Archive</button>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Logout Modal Popup + Logout Action -->
            <?php
            include 'logout_modal.php';
            ?>
        </div>
        <?php
        include ('includes/scripts.php');
        include ('includes/footer.php');
        ?>
</body>

<script>
    function validateInputs() {
        var quantity = document.getElementById('quantityInput').value.trim(); // Get the trimmed input value
        var price = document.getElementById('priceInput').value.trim(); // Get the trimmed input value
        var quantityWarning = document.getElementById('quantityWarning');
        var priceWarning = document.getElementById('priceWarning');
        var submitButton = document.getElementById('submitButton');

        if (parseFloat(quantity) <= 0 || quantity.startsWith('0')) { // Check if quantity is zero or starts with zero
            quantityWarning.style.display = 'block';
            submitButton.disabled = true;
        } else {
            quantityWarning.style.display = 'none';
        }

        if (parseFloat(price) <= 0 || price.startsWith('0')) { // Check if price is zero or starts with zero
            priceWarning.style.display = 'block';
            submitButton.disabled = true;
        } else {
            priceWarning.style.display = 'none';
        }

        if (parseFloat(quantity) > 0 && parseFloat(price) > 0) {
            submitButton.disabled = false;
        }
    }

    document.getElementById('quantityInput').addEventListener('input', validateInputs);
    document.getElementById('priceInput').addEventListener('input', validateInputs);
</script>

<script>
    $(document).ready(function () {
        // Pass archive ID to modal when button is clicked
        $('#confirmArchiveModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var archiveId = button.data('id');
            $('#archiveId').val(archiveId);
        });

        // Handle form submission upon confirmation
        $('#confirmArchiveBtn').click(function () {
            $('#archiveForm').submit();
        });
    });
</script>

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
        var measureField = document.querySelector('[name="measurement"]');
        var quantityField = document.querySelector('[name="quantity"]');
        var descriptionField = document.querySelector('[name="descript"]');
        var quantityField = document.querySelector('[name="quantity"]');
        var priceField = document.querySelector('[name="price"]');
        var supplierField = document.querySelector('[name="supp_name"]');
        var branchField = document.querySelector('[name="branch"]');
        var expiryDateField = document.querySelector('[name="expiry_date"]');

        if (sku) {
            productNameField.removeAttribute('disabled');
            measureField.removeAttribute('disabled');
            descriptionField.removeAttribute('disabled');
            quantityField.removeAttribute('disabled');
            priceField.removeAttribute('disabled');
            supplierField.removeAttribute('disabled');
            branchField.removeAttribute('disabled');
            expiryDateField.removeAttribute('disabled');
        } else {
            productNameField.setAttribute('disabled', 'disabled');
            measureField.removeAttribute('disabled', 'disabled');
            descriptionField.setAttribute('disabled', 'disabled');
            quantityField.setAttribute('disabled', 'disabled');
            priceField.setAttribute('disabled', 'disabled');
            supplierField.setAttribute('disabled', 'disabled');
            branchField.setAttribute('disabled', 'disabled');
            expiryDateField.setAttribute('disabled', 'disabled');
        }
    });
</script>

</html>
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
            "columnDefs": [
                { "orderable": false, "targets": [1, 3, 4, 5, 6, 7, 8, 9, 10, 11] } // Disable sorting for all columns except ID, SKU, and Product Name
            ],
            "order": [[0, "desc"]] // Sort by the first column (ID) in descending order
        });
    });

</script>