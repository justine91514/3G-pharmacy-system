<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Branch</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="om.css">
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
        background-color: #EB3223;
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
    session_start();
    include ('includes/header.php');
    include ('includes/navbar2.php');
    ?>

    <!-- Modal -->
    <div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #259E9E; color: white; border-bottom: none;">
                    <h5 class="modal-title" id="exampleModalLabel">Add Branch</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="code.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label style="color: #259E9E;">Add Branch</label>
                            <input type="text" name="branch" class="form-control modal-input"
                                placeholder="Input Branch" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary modal-btn mr-2"
                            style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none;  box-shadow: none; "
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary modal-btn" name="branchbtn"
                            style="border-radius: 5px; padding: 10px 20px; background-color: #259E9E; border: none; box-shadow: none;">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this category?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2"
                        style="border-radius: 5px; padding: 10px 20px; background-color: #828282; border: none; box-shadow: none;"
                        data-dismiss="modal">Cancel</button>
                    <!-- Ensure form submission on button click -->
                    <form id="deleteForm" action="code.php" method="POST">
                        <input type="hidden" id="delete_id" name="delete_id">
                        <button type="submit" name="delete_cat_btn" class="btn btn-danger"
                            style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; box-shadow: none;">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <div class="container-fluid">
        <!-- DataTables Example -->
        <!-- DataTables Example -->
        <div class="card shadow nb-4">
            <div class="card-header py-3">
                <h1>Add Branch</h1>
            </div>
            <div class="card-body">

                <h6 class="m-0 font-weight-bold text-primary">
                    <button type="button" class="btn btn-primary rounded-pill shadow mb-3" data-toggle="modal"
                        data-target="#addadminprofile"
                        style="background-color: #259E9E; border: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                        <i class="fas fa-plus-square fa-lg"></i> <span
                            style="font-weight: bold; text-transform: uppercase;">Add New Branch</span>
                    </button>
                </h6>
                <!-- this code is for the admin profile added (makikita sa code.php)-->
                <?php
                if (isset($_SESSION['success']) && $_SESSION['success'] != '') {
                    echo '<h2 class="bg-primary text-white">' . $_SESSION['success'] . '</h2>';
                    unset($_SESSION['success']);
                }
                ?>
                <!-- this code is for the admin profile added  -->

                <!-- this code is for the Password and confirm password does not match (makikita sa code.php)-->
                <?php
                if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                    echo '<h2 class="bg-danger text-white">' . $_SESSION['status'] . '</h2>';
                    unset($_SESSION['status']);
                }
                ?>
                <!-- this code is for the Password and confirm password does not match (makikita sa code.php)-->


                <div class="table-responsive">

                    <?php
                    $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

                    $query = "SELECT * FROM add_branch_list";
                    $query_run = mysqli_query($connection, $query);
                    ?>

                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead style="background-color: #259E9E; color: white;">
                            <th> ID </th>
                            <th> Branch Name </th>
                            <th> Action </th>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    ?>
                                    <tr>
                                        <td style="vertical-align: middle;"><?php echo $row['id']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['branch']; ?></td>
                                        <td style="vertical-align: middle;">

                                            <form action="edit_.php" method="post" style="display: inline-block;">
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
            </div>
        </div>


        <?php
        include 'logout_modal.php';
        ?>

        <?php
        include ('includes/scripts.php');
        include ('includes/footer.php');
        ?>

        <script>
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var modal = $(this);
                modal.find('#delete_id').val(id);
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
        <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
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
                        { "orderable": true, "targets": [0, 1] }, // ID and Product Name columns are sortable
                        { "orderable": false, "targets": '_all' } // Disable sorting for all other columns
                    ],
                    "order": [[0, "desc"]] // Sort by the first column (ID) in descending order
                });
            });
        </script>