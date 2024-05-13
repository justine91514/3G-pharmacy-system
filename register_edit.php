<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
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
    $query = "SELECT * FROM register WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);

    foreach ($query_run as $row) {
        ?>
        <div class="container-fluid container-custom">
            <div class="container-wrapper">
                <div class="card shadow nb-4">
                    <div class="card-header py-3" style="background-color: #259E9E; color: white; border-bottom: none;">
                        <h6 class="m-0 font-weight-bold" style="color: white;">Edit Account</h6>
                    </div>
                    <div class="card-body">
                        <form action="code.php" method="POST">
                            <input type="hidden" name="edit_id" value="<?php echo $row['id'] ?>">
                            <div class="form-group">
                                <label class="modal-label" style="color: #259E9E;">First Name</label>
                                <input type="text" name="edit_firstname" value="<?php echo $row['first_name'] ?>"
                                    class="form-control" placeholder="Input First Name" required />
                            </div>
                            <div class="form-group">
                                <label class="modal-label" style="color: #259E9E;">Middle Name</label>
                                <input type="text" name="edit_mid_name" value="<?php echo $row['mid_name'] ?>"
                                    class="form-control" placeholder="Input Middle Name" required />
                            </div>
                            <div class="form-group">
                                <label class="modal-label" style="color: #259E9E;">Last Name</label>
                                <input type="text" name="edit_lastname" value="<?php echo $row['last_name'] ?>"
                                    class="form-control" placeholder="Input Last Name" required />
                            </div>
                            <div class="form-group">
                                <label class="modal-label" style="color: #259E9E;">Email</label>
                                <input type="email" name="edit_email" value="<?php echo $row['email'] ?>" class="form-control"
                                    placeholder="Input Email" required />
                            </div>
                            <div class="form-group">
                                <label class="modal-label" style="color: #259E9E;">Password</label>
                                <input type="password" name="edit_password" value="<?php echo $row['password'] ?>"
                                    class="form-control" placeholder="Input Password" required />
                            </div>
                            <div class="form-group">
                                <label class="modal-label" style="color: #259E9E;">Branch</label>
                                <select name="branch" class="form-control" required>
                                    <option value="" disabled>Select Branch</option>
                                    <option value="Cell Med" <?php if ($row['branch'] == 'Cell Med')
                                        echo 'selected'; ?>>Cell Med
                                    </option>
                                    <option value="3G Med" <?php if ($row['branch'] == '3G Med')
                                        echo 'selected'; ?>>3G Med
                                    </option>
                                    <option value="Boom Care" <?php if ($row['branch'] == 'Boom Care')
                                        echo 'selected'; ?>>Boom
                                        Care</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="modal-label" style="color: #259E9E;">UserType</label>
                                <select name="usertype" class="form-control" required>
                                    <option value="" disabled>Select UserType</option>
                                    <option value="admin" <?php if ($row['usertype'] == 'admin')
                                        echo 'selected'; ?>>Admin</option>
                                    <option value="pharmacy_assistant" <?php if ($row['usertype'] == 'pharmacy_assistant')
                                        echo 'selected'; ?>>Pharmacy Assistant</option>
                                </select>
                            </div>

                            <div class="modal-footer" style="background-color: #ffff; border-top: 1px solid #ccc;">
                                <a href="register.php" class="btn btn-danger"
                                    style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; margin-top: 5px; margin-bottom: -15px;">Cancel</a>
                                <button type="submit" name="updatebtn" class="btn btn-primary modal-btn"
                                    style="border-radius: 5px; padding: 10px 20px; background-color: #259E9E; border: none; margin-top: 5px; margin-bottom: -15px;">Update</button>
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
        include ('includes/scripts.php');
        include ('includes/footer.php');
        ?>
        </body>

        </html>