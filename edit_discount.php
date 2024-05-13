<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Discount</title>
    <link rel="stylesheet" href="ack.css">
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
    $query = "SELECT * FROM discount_list WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    foreach ($query_run as $row) {
        ?>
        <div class="container-fluid container-custom">
            <div class="container-wrapper">
                <div class="card shadow nb-4">
                    <div class="card-header py-3" style="background-color: #259E9E; color: white; border-bottom: none;">
                        <h6 class="m-0 font-weight-bold" style="color: white;">Edit Discount</h6>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <input type="hidden" name="edit_id" value="<?php echo $row['id'] ?>">
                            <div class="form-group">
                                <label style="color: #259E9E;">Discount Name</label>
                                <input type="text" name="edit_discount" value="<?php echo $row['discount_name'] ?>"
                                    class="form-control" placeholder="Enter Category" required />
                            </div>
                            <div class="form-group">
    <label for="editValueInput" style="color: #259E9E;">Value (%)</label>
    <input type="number" id="editValueInput" name="edit_value" value="<?php echo $row['value'] ?>" class="form-control"
        placeholder="Enter Value" required />
    <div id="editValueWarning" style="display: none; color: red;">Value must be a positive number and should not start with zero.</div>
</div>

                            <div class="modal-footer" style="background-color: #ffff; border-top: 1px solid #ccc;">
                                <a href="add_discount.php" class="btn btn-danger"
                                    style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; margin-top: 5px; margin-bottom: -15px;">Cancel</a>
                                <button type="submit" name="updatediscountbtn" id="submitButton" class="btn btn-primary modal-btn"
                                    style="border-radius: 5px; padding: 10px 20px; background-color: #259E9E; border: none; margin-top: 5px; margin-bottom: -15px;">Update</button>


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
    function validateEditValue() {
        var value = parseFloat(document.getElementById('editValueInput').value.trim());
        var valueWarning = document.getElementById('editValueWarning');
        var submitButton = document.getElementById('submitButton');

        if (value <= 0 || value.toString().startsWith('0') || value.toString().startsWith('-')) {
            valueWarning.style.display = 'block';
            submitButton.disabled = true;
        } else {
            valueWarning.style.display = 'none';
            submitButton.disabled = false;
        }
    }

    document.getElementById('editValueInput').addEventListener('input', validateEditValue);
</script>
            <?php
            include ('includes/scripts.php');
            include ('includes/footer.php');
            ?>
            </body>

            </html>