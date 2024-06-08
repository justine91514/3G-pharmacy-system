<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stocks</title>
</head>
<style>
    .container-fluid {
        margin-top: 100px;
        /* Adjust the value as needed */

        .container-custom {
            margin-top: 50px;
            /* Adjust the value as needed */
            padding-bottom: 20px;
            /* Adjust the padding at the bottom */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #304B1B;
        }

        input[type="text"],
        select,
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            background-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        /* Applying custom styles to the select element */
        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 6px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .modal-label {
            color: #304B1B;
        }

        .container-wrapper {
            padding-bottom: 50px;
            /* Adjust the padding at the bottom */
        }
    }
</style>

</html>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include ('includes/header.php');
include ('includes/navbar2.php');

$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
$query = "SELECT supplier_name FROM supplier_list";
    $query_run = mysqli_query($connection, $query);
    $suppNames = array();
    while ($row = mysqli_fetch_assoc($query_run)) {
        $suppNames[] = $row['supplier_name'];
    }


if (isset($_POST['edit_btn'])) {
    $id = $_POST['edit_id'];
    $query = "SELECT * FROM add_stock_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    foreach ($query_run as $row) {
        ?>
        <div class="container-fluid container-custom">
            <div class="container-wrapper">
                <div class="card shadow nb-4">
                    <div class="card-header py-3" style="background-color: #304B1B; color: white; border-bottom: none;">
                        <h6 class="m-0 font-weight-bold" style="color: white;">Edit Stock</h6>
                    </div>

                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <input type="hidden" name="edit_id" value="<?php echo $row['id'] ?>">
                            <div class="form-group">
                                <label>SKU</label>
                                <input type="text" name="sku" value="<?php echo $row['sku'] ?>" class="form-control"
                                    placeholder="Enter SKU" readonly required />
                            </div>
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="product_stock_name" value="<?php echo $row['product_stock_name'] ?>"
                                    class="form-control" placeholder="Enter Category" readonly required />
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="descript" value="<?php echo $row['descript'] ?>" class="form-control"
                                    placeholder="Enter Description" required />
                            </div>
                            <div class="form-group">
                                <label for="quantityInput">Quantity</label>
                                <input type="number" id="quantityInput" name="quantity" value="<?php echo $row['quantity'] ?>"
                                    class="form-control" placeholder="Enter Quantity" required />
                                <div id="quantityWarning" style="display: none; color: red;">Quantity must be a positive number and should not start with zero.</div>
                            </div>
                            <div class="form-group">
    <label>Supplier Name</label>
    <select name="supp_name" class="form-control" required>
        <?php
        foreach ($suppNames as $suppName) {
            $selected = ($selectedSupplier == $suppName) ? 'selected' : '';
            echo "<option value='$suppName' $selected>$suppName</option>";
        }
        ?>
    </select>
</div>
                            <div class="form-group">
                                <label for="priceInput">Price</label>
                                <input type="number" id="priceInput" name="price" value="<?php echo $row['price'] ?>"
                                    class="form-control" placeholder="Enter Price" required />
                                <div id="priceWarning" style="display: none; color: red;">Price must be a positive number and should not start with zero.</div>
                            </div>
                            <div class="form-group">
                                <label> Branch </label>
                                <select name="branch" class="form-control" required>
                                    <option value="" disabled>Select Branch</option>
                                    <option value="Cell Med" <?php echo ($row['branch'] == 'Cell Med') ? 'selected' : ''; ?>>Cell
                                        Med</option>
                                    <option value="3G Med" <?php echo ($row['branch'] == '3G Med') ? 'selected' : ''; ?>>3G Med
                                    </option>
                                    <option value="Boom Care" <?php echo ($row['branch'] == 'Boom Care') ? 'selected' : ''; ?>>
                                        Boom Care</option>
                                </select>

                                <div class="form-group" style="margin-top: 15px;">
                                    <label>Expiry Date</label>
                                    <input type="date" name="expiry_date" value="<?php echo $row['expiry_date']; ?>"
                                        class="form-control" placeholder="Select Expiry Date" required />
                                </div>
                            </div>
                            <div class="modal-footer" style="background-color: #ffff; border-top: 1px solid #ccc;">
                                <a href="add_stocks.php" class="btn btn-danger"
                                    style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; margin-top: 8px; margin-bottom: -15px;">Cancel</a>
                                <button type="submit" id="submitButton" name="update_stocks_btn"
                                    class="btn btn-primary modal-btn"
                                    style="border-radius: 5px; padding: 10px 20px; background-color: #304B1B; border: none; margin-top: 8px; margin-bottom: -15px;">Update</button>
                            </div>
                    </div>
                </div>
                <?php
    }
}
?>

        <script>
            $(document).ready(function () {
                $('.date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true
                });
            });
        </script>

        <script>
            function validateInputs() {
                var quantity = document.getElementById('quantityInput').value.trim();
                var price = document.getElementById('priceInput').value.trim();
                var quantityWarning = document.getElementById('quantityWarning');
                var priceWarning = document.getElementById('priceWarning');
                var submitButton = document.getElementById('submitButton');

                if (parseFloat(quantity) <= 0 || quantity.startsWith('0')) {
                    quantityWarning.style.display = 'block';
                    submitButton.disabled = true;
                } else {
                    quantityWarning.style.display = 'none';
                }

                if (parseFloat(price) <= 0 || price.startsWith('0')) {
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
        <?php
        include ('includes/scripts.php');
        include ('includes/footer.php');
        ?>