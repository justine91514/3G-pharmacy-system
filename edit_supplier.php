<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Supplier</title>
    <link rel="stylesheet" href="ack.css">
    <style>

.container-custom {
        margin-top: 100px; /* Adjust the value as needed */
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
            color: #259E9E;
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
            color: #259E9E;
        }
        .container-wrapper {
    padding-bottom: 50px; /* Adjust the padding at the bottom */
}
    </style>
</head>
</html>
<?php
session_start();
include('includes/header.php');
include('includes/navbar2.php');

$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

if (isset($_POST['edit_btn'])) {
    $id = $_POST['edit_id'];
    $query = "SELECT * FROM supplier_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    foreach ($query_run as $row) {
        ?>
        <div class="container-fluid container-custom">
        <div class="container-wrapper">
            <div class="card shadow nb-4">
            <div class="card-header py-3" style="background-color: #259E9E; color: white; border-bottom: none;">
    <h6 class="m-0 font-weight-bold" style="color: white;">Edit Supplier</h6>
</div>
                <div class="card-body">
                    <form action="code.php" method="POST">
                        <input type="hidden" name="edit_id" value="<?php echo $row['id'] ?>">
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Supplier Name</label>
                            <input type="text" name="supplier_name"  value="<?php echo $row['supplier_name'] ?>" class="form-control" placeholder="Input Supplier Name" required style="border-radius: 5px; border: 1px solid #ccc; padding: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Contact Number (Philippine Format)</label>
                            <input type="text" name="contact" value="<?php echo $row['contact'] ?>" class="form-control" placeholder="Input Contact Number" required 
                                pattern="(?:\+?63|0)\d{10}" 
                                title="Valid formats: 0999-123-4567, 63999-123-4567, or 02-123-4567"
                                style="border-radius: 5px; border: 1px solid #ccc; padding: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Address</label>
                            <input type="text" name="address"  value="<?php echo $row['address'] ?>" class="form-control" placeholder="Input Address" required style="border-radius: 5px; border: 1px solid #ccc; padding: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        </div>
                        <div class="form-group">
    <label class="modal-label" style="color: #259E9E;">Agreement</label>
    <select name="agreement" class="form-control" required style="border-radius: 5px; border: 1px solid #ccc; padding: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
        <option value="" disabled selected>Select Agreement Type</option>
        <option value="Consignment"<?php if ($row['agreement'] == 'Consignment') echo ' selected'; ?>>Consignment</option>
        <option value="Concession"<?php if ($row['agreement'] == 'Concession') echo ' selected'; ?>>Concession</option>
        <option value="Supply"<?php if ($row['agreement'] == 'Supply') echo ' selected'; ?>>Supply</option>
        <option value="Purchase"<?php if ($row['agreement'] == 'Purchase') echo ' selected'; ?>>Purchase</option>
    </select>
</div>
<div class="modal-footer" style="background-color: #ffff; border-top: 1px solid #ccc;">
    <a href="add_supplier.php" class="btn btn-danger" style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; margin-top: 5px; margin-bottom: -15px;">Cancel</a>
    <button type="submit" name="updatesupplierbtn" class="btn btn-primary modal-btn" style="border-radius: 5px; padding: 10px 20px; background-color: #259E9E; border: none; margin-top: 5px; margin-bottom: -15px;">Update</button>
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
</body>
</html>
