<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include ('includes/header.php');
include ('includes/navbar2.php');
include ('activity_logs.php');
date_default_timezone_set('Asia/Manila');
$selectedBranch = isset($_GET['branch']) ? $_GET['branch'] : 'All';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Account</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="om.css">
    <script>
        function changeBranch() {
            var selectedBranch = document.getElementById("branch").value;
            window.location.href = 'register.php?branch=' + selectedBranch;
        }
    </script>
    <style>
        .container-fluid {
            margin-top: 100px;
            /* Adjust the value as needed */
        }

        /* CSS to style disabled rows */
        .disabled-row {
            background-color: #f2f2f2;
            /* Light gray background color */
            color: #999;
            /* Gray text color */
        }
    </style>
</head>

<body>
    <!-- Modal -->
    <div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #259E9E; color: white; border-bottom: none;">
                    <h5 class="modal-title" id="exampleModalLabel">Add Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="code.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">First Name</label>
                            <input type="text" name="first_name" class="form-control" placeholder="Input First Name"
                                required />
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Middle Name</label>
                            <input type="text" name="mid_name" class="form-control" placeholder="Input Middle Name"
                                required />
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Last Name</label>
                            <input type="text" name="last_name" class="form-control" placeholder="Input Last Name"
                                required />
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Email</label>
                            <input type="email" id="emailInput" name="email" class="form-control"
                                placeholder="Input Email" required />
                            <span id="emailWarning" style="color: red; display: none;">Please enter a valid email
                                address.</span>
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Password</label>
                            <input type="text" id="password" name="password" class="form-control"
                                placeholder="Input Password" required />
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Confirm Password</label>
                            <input type="text" id="confirmPassword" name="confirmpassword" class="form-control"
                                placeholder="Confirm Password" required />
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Branch</label>
                            <select name="branch" class="form-control" required>
                                <option value="" disabled selected>Select Branch</option>
                                <option value="Cell Med">Cell Med</option>
                                <option value="3G Med">3G Med</option>
                                <option value="Boom Care">Boom Care</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">UserType</label>
                            <select name="usertype" class="form-control">
                                <option value="" disabled selected>Select UserType</option>
                                <option value="admin">admin</option>
                                <option value="pharmacy_assistant">pharmacy_assistant</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary modal-btn mr-2"
                            style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none;  box-shadow: none; "
                            data-dismiss="modal">Cancel</button>
                        <button id="submitButton" type="submit" class="btn btn-primary modal-btn" name="registerbtn"
                            style="border-radius: 5px; padding: 10px 20px; background-color: #259E9E; border: none; box-shadow: none;"
                            onclick="return validatePassword()">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card shadow nb-4">
            <div class="card-header py-3">
                <h1>User Management</h1>
            </div>
            <div class="card-body">
                <h6 class="m-0 font-weight-bold text-primary">
                    <button type="button" class="btn btn-primary shadow" data-toggle="modal"
                        data-target="#addadminprofile"
                        style="background-color: #FFFFFF; border: 2px solid #259E9E; color: #000000; border-radius: 10px; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                        <i class="fas fa-user-plus fa-lg" style="color: #000000;"></i> <span
                            style="font-weight: bold; text-transform: uppercase;">Add New Account</span>
                    </button>

                </h6>
                <div class="form-group">
                    <label class="modal-label" style="top: 5px; padding-top: 15px;"> Branch</label>
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
                        <!-- Add more options for other branches as needed -->
                    </select>
                </div>
                <div class="table-responsive">

                    <?php
                    $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

                    // Query to fetch product details with total quantity per branch
                    $query = "SELECT p.id, p.prod_name, p.categories, p.type, p.measurement, 
                          SUM(CASE WHEN a.branch = 'Cell Med' THEN a.quantity ELSE 0 END) AS 'Cell Med',
                          SUM(CASE WHEN a.branch = '3G Med' THEN a.quantity ELSE 0 END) AS '3G Med',
                          SUM(CASE WHEN a.branch = 'Boom Care' THEN a.quantity ELSE 0 END) AS 'Boom Care',
                          p.prescription 
                          FROM product_list p
                          LEFT JOIN add_stock_list a ON p.prod_name = a.product_stock_name";

                    // If a specific branch is selected, filter the products for that branch
                    if ($selectedBranch != 'All') {
                        $query .= " WHERE a.branch = '$selectedBranch'";
                    }

                    $query .= " GROUP BY p.id";

                    $query_run = mysqli_query($connection, $query);
                    ?>
                </div>
                <?php
                if (isset($_SESSION['success']) && $_SESSION['success'] != '') {
                    echo '<h2 class="bg-primary text-white">' . $_SESSION['success'] . '</h2>';
                    unset($_SESSION['success']);
                }
                ?>
                <?php
                if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                    echo '<h2 class="bg-danger text-white">' . $_SESSION['status'] . '</h2>';
                    unset($_SESSION['status']);
                }
                ?>
                <div class="table-responsive">
                    <?php
                    $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
                    $query = "SELECT * FROM register";
                    if ($selectedBranch != 'All') {
                        $query .= " WHERE branch = '$selectedBranch'";
                    }
                    $query_run = mysqli_query($connection, $query);
                    ?>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead style="background-color: #259E9E; color: white;">
                            <th> ID </th>
                            <th> First Name </th>
                            <th> Middle Name </th>
                            <th> Last Name </th>
                            <th> Email</th>
                            <th> Password</th>
                            <th> Branch</th>
                            <th> UserType</th>
                            <th> Action </th>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    ?>
                                    <tr>
                                        <td style="vertical-align: middle;"><?php echo $row['id']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['first_name']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['mid_name']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['last_name']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['email']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['password']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['branch']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['usertype']; ?></td>
                                        <td style="vertical-align: middle;">

                                            <form action="register_edit.php" method="post" style="display: inline-block;">
                                                <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                                <button type="submit" name="edit_btn" class="btn btn-action editBtn">
                                                    <i class="fas fa-edit" style="color: #44A6F1;"></i>
                                                </button>
                                            </form>
                                            <span class="action-divider">|</span>
                                            <button class="btn btn-action" style="border: none; background: none;"
                                                data-toggle="modal" data-target="#deleteModal"
                                                data-id="<?php echo $row['id']; ?>">
                                                <i class="fas fa-trash-alt" style="color: #FF0000;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "No record Found";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Delete Confirmation -->
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Delete Account</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this account?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary mr-2"
                                    style="border-radius: 5px; padding: 10px 20px; background-color: #828282; border: none; box-shadow: none;"
                                    data-dismiss="modal">Cancel</button>
                                <!-- Ensure form submission on button click -->
                                <form id="deleteForm" action="code.php" method="POST">
                                    <input type="hidden" id="delete_id" name="delete_id">
                                    <button type="submit" name="delete_btn" class="btn btn-danger"
                                        style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; box-shadow: none;">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function validateEmail() {
                        var email = document.getElementById('emailInput').value;
                        var emailWarning = document.getElementById('emailWarning');
                        var submitButton = document.getElementById('submitButton');

                        if (!email.endsWith('@gmail.com')) {
                            emailWarning.style.display = 'block';
                            submitButton.disabled = true;
                        } else {
                            emailWarning.style.display = 'none';
                            submitButton.disabled = false;
                        }
                    }

                    document.getElementById('emailInput').addEventListener('input', validateEmail);
                </script>
                <!-- Logout Modal Popup + Logout Action -->
                <?php
                include 'logout_modal.php';
                ?>
                <?php
                include ('includes/scripts.php');
                include ('includes/footer.php');
                ?>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
    $('#deleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var modal = $(this);
        modal.find('#delete_id').val(id);
    });
</script>

<script>
    function validatePassword() {
        var password = document.getElementById('password').value;
        var confirmPassword = document.getElementById('confirmPassword').value;

        // Check if passwords match
        if (password !== confirmPassword) {
            alert("Passwords do not match");
            return false;
        }

        // Check if password is at least 8 characters long
        if (password.length < 8) {
            alert("Password must be at least 8 characters long");
            return false;
        }
        // Add further action here, like form submission
    }
</script>


</div>
<?php
include ('includes/scripts.php');
include ('includes/footer.php');
?>

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
                { "orderable": true, "targets": [0, 4, 7] }, // ID and Product Name columns are sortable
                { "orderable": false, "targets": '_all' } // Disable sorting for all other columns
            ],
            "order": [[0, "desc"]] // Sort by the first column (ID) in descending order
        });
    });
</script>