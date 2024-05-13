<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Stocks</title>
    <link rel="stylesheet" href="ack.css">
    <style>

.container-custom {
        margin-top: 50px; /* Adjust the value as needed */
        padding-bottom: 20px; /* Adjust the padding at the bottom */
    }

                body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .modal-footer {
        border-top: none; /* Remove border at the top of the footer */
        padding: 15px 20px; /* Add padding */
        background-color: #f8f9fc; /* Footer background color */
        border-radius: 0 0 10px 10px; /* Rounded corners only at the bottom */
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
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .modal-label {
            color: #304B1B;
        }
        .container-wrapper {
    padding-bottom: 50px; /* Adjust the padding at the bottom */
}
    </style>
</head>
</html>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('includes/header.php');
include('includes/navbar2.php');

$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

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
    <h6 class="m-0 font-weight-bold" style="color: white;">Edit Product</h6>
</div>
                <div class="card-body">
                    <form action="code.php" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo $row['id'] ?>">
                        <div class="form-group">
                            <label>SKU</label>
                            <input type="text" name="sku" value="<?php echo $row['sku'] ?>" class="form-control" placeholder="Enter SKU"required />
                        </div>
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="product_stock_name" value="<?php echo $row['product_stock_name'] ?>" class="form-control" placeholder="Enter Category" readonly required />
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" name="quantity" value="<?php echo $row['quantity'] ?>" class="form-control" placeholder="Enter Quantity" required />
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" value="<?php echo $row['price'] ?>" class="form-control" placeholder="Enter Price" required />
                        </div>
                        <div class="form-group">
                            <label> Branch </label>
                            <select name="branch" class="form-control" required>
                                <option value="" disabled>Select Branch</option>
                                <option value="Cell Med" <?php echo ($row['branch'] == 'Cell Med') ? 'selected' : ''; ?>>Cell Med</option>
                                <option value="3G Med" <?php echo ($row['branch'] == '3G Med') ? 'selected' : ''; ?>>3G Med</option>
                                <option value="Boom Care" <?php echo ($row['branch'] == 'Boom Care') ? 'selected' : ''; ?>>Boom Care</option>
                            </select>
                            <div class="form-group" style="margin-top: 15px;">
                            <label>Batch Number</label>
                            <input type="text" name="batch_no" value="<?php echo $row['batch_no'] ?>" class="form-control" placeholder="Enter Price" required />
                        </div>
                        <div class="form-group" style="margin-top: 15px;">
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" value="<?php echo $row['expiry_date']; ?>" class="form-control" placeholder="Select Expiry Date" required />
                        </div>
                        <div class="modal-footer">
    <button type="button" class="btn btn-primary modal-btn mr-2" style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; margin-top: 10px;">Cancel</button>
    <button type="submit" name="categorybtn" class="btn btn-primary modal-btn" style="border-radius: 5px; padding: 10px 20px; background-color: #304B1B; border: none; margin-top: 10px;">Update</button>
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

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>
