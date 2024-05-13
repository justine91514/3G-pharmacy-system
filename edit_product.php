<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="om.css">
    <style>
        .container-custom {
            margin-top: 100px;
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .modal-label {
            color: #259E9E;
        }

        .container-wrapper {
            padding-bottom: 50px;
            /* Adjust the padding at the bottom */
        }
    </style>
</head>

</html>
<?php
session_start();
include ('includes/header.php');
include ('includes/navbar2.php');

$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

if (isset($_POST['edit_btn'])) {
    $id = $_POST['edit_id'];
    $query = "SELECT * FROM product_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    foreach ($query_run as $row) {
        ?>
        <div class="container-fluid container-custom">
            <div class="container-wrapper">
                <div class="card shadow nb-4">
                    <div class="card-header py-3" style="background-color: #259E9E; color: white; border-bottom: none;">
                        <h6 class="m-0 font-weight-bold" style="color: white;">Edit Product</h6>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <input type="hidden" name="edit_id" value="<?php echo $row['id'] ?>">
                            <div class="form-group">
                                <label> Product Name </label>
                                <input type="text" name="prod_name" value="<?php echo $row['prod_name'] ?>" class="form-control"
                                    placeholder="Input Product Name" required />
                            </div>
                            <div class="form-group">
                                <label> Category </label>
                                <select name="categories" class="form-control" required>
                                    <option value="" disabled>Select Category</option>
                                    <?php
                                    $category_query = "SELECT * FROM category_list";
                                    $category_result = mysqli_query($connection, $category_query);

                                    while ($category_row = mysqli_fetch_assoc($category_result)) {
                                        echo '<option value="' . $category_row['category_name'] . '" ' . (($row['categories'] == $category_row['category_name']) ? 'selected' : '') . '>' . $category_row['category_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label> Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="" disabled>Select Type</option>
                                    <?php
                                    $type_query = "SELECT * FROM product_type_list";
                                    $type_result = mysqli_query($connection, $type_query);

                                    while ($type_row = mysqli_fetch_assoc($type_result)) {
                                        echo '<option value="' . $type_row['type_name'] . '" ' . (($row['type'] == $type_row['type_name']) ? 'selected' : '') . '>' . $type_row['type_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label> Unit </label>
                                <select name="unit" class="form-control" required>
                                    <option value="" disabled>Select Unit</option>
                                    <?php
                                    $type_query = "SELECT * FROM unit_list";
                                    $type_result = mysqli_query($connection, $type_query);

                                    while ($type_row = mysqli_fetch_assoc($type_result)) {
                                        echo '<option value="' . $type_row['unit_name'] . '" ' . (($row['unit'] == $type_row['unit_name']) ? 'selected' : '') . '>' . $type_row['unit_name'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="measurementInput">Measurement</label>
                                <input type="text" id="measurementInput" name="measurement"
                                    value="<?php echo $row['measurement'] ?>" class="form-control"
                                    placeholder="Input Measurement" required />
                                <div id="measurementWarning" style="display: none; color: red;">Measurement must be a positive
                                    number and should not start with zero.</div>
                            </div>
                            <!-- Existing code ... -->
                            <div class="form-group">
                                <label class="modal-label" style="color: #259E9E;">Prescription</label>
                                <div class="form-check">
                                    <input type="checkbox" name="prescription" class="form-check-input"
                                        id="prescriptionCheckbox" value="1" <?php if ($row['prescription'] == 1)
                                            echo 'checked'; ?> />
                                    <label class="form-check-label" for="prescriptionCheckbox"
                                        style="color: #555; font-weight: normal;">Prescription required</label>
                                </div>
                                <!-- <div class="form-check">
    <input type="checkbox" name="discounted" class="form-check-input" id="generic_discount_Checkbox" value="1" <?php if ($row['discounted'] == 1)
                echo 'checked'; ?> />
    <label class="form-check-label" for="generic_discount_Checkbox" style="color: #555; font-weight: normal;">Generic Discount required</label>
</div> -->
                            </div>

                            <div class="modal-footer" style="background-color: #ffff; margin-top: 10px;">
                                <a href="product.php" class="btn btn-danger"
                                    style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; margin-top: 8px; margin-bottom: -15px;">Cancel</a>
                                <button type="submit" name="updateproductbtn" id="submitButton"
                                    class="btn btn-primary modal-btn"
                                    style="border-radius: 5px; padding: 10px 20px; background-color: #259E9E; border: none; margin-top: 8px; margin-bottom: -15px;">Update</button>
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
            function validateMeasurement() {
                var measurement = document.getElementById('measurementInput').value.trim();
                var measurementWarning = document.getElementById('measurementWarning');
                var submitButton = document.getElementById('submitButton');

                if (parseFloat(measurement) <= 0 || measurement.startsWith('0') || measurement.startsWith('-')) {
                    measurementWarning.style.display = 'block';
                    submitButton.disabled = true;
                } else {
                    measurementWarning.style.display = 'none';
                    submitButton.disabled = false;
                }
            }

            document.getElementById('measurementInput').addEventListener('input', validateMeasurement);
        </script>
        <?php
        include ('includes/scripts.php');
        include ('includes/footer.php');
        ?>
        </body>

        </html>