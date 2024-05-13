<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="om.css">

    <style>
        .dataTables_wrapper {
            margin-top: 20px !important;
            /* Adjust the value as needed */
        }

        .container-fluid {
            margin-top: 100px;
            /* Adjust the value as needed */
        }
    </style>
</head>

<body>

    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include ('includes/header.php');
    include ('includes/navbar2.php');
    $selectedBranch = isset($_GET['branch']) ? $_GET['branch'] : 'All';

    // Check if parameter indicating updated stocks available is present in URL
    if (isset($_GET['updated_stocks_available'])) {
        $updated_stocks_available = $_GET['updated_stocks_available'];
        // Use the updated stocks available value
    } else {
        // Use the regular query to fetch product details
        $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
        $query = "SELECT id, prod_name, categories, type, measurement, stocks_available, description, prescription FROM product_list";
        $query_run = mysqli_query($connection, $query);
    }
    ?>

    <!-- Modal -->
    <div class="modal fade" id="addadminprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #259E9E; color: white; border-bottom: none;">
                    <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="code.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Product Name</label>
                            <input type="text" name="prod_name" class="form-control" placeholder="Input Product Name"
                                required
                                style="border-radius: 5px; border: 1px solid #ccc; padding: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        </div>
                    
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Category</label>
                            <select name="categories" class="form-control" required
                                style="border-radius: 5px; border: 1px solid #ccc; padding: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <option value="" disabled selected>Select Category</option>
                                <?php
                                $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
                                $query = "SELECT * FROM category_list";
                                $query_run = mysqli_query($connection, $query);
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    echo "<option value='" . $row['category_name'] . "'>" . $row['category_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;"> Type</label>
                            <select name="type" class="form-control" required
                                style="border-radius: 5px; border: 1px solid #ccc; padding: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <option value="" disabled selected>Select Type</option>
                                <?php
                                $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
                                $query = "SELECT * FROM product_type_list";
                                $query_run = mysqli_query($connection, $query);
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    echo "<option value='" . $row['type_name'] . "'>" . $row['type_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Product Unit</label>
                            <select name="unit" class="form-control" required
                                style="border-radius: 5px; border: 1px solid #ccc; padding: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                                <option value="" disabled selected>Select Product Unit</option>
                                <?php
                                $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
                                $query = "SELECT * FROM unit_list";
                                $query_run = mysqli_query($connection, $query);
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    echo "<option value='" . $row['unit_name'] . "'>" . $row['unit_name'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="measurementInput" class="modal-label"
                                style="color: #259E9E;">Measurement</label>
                            <input type="text" id="measurementInput" name="measurement" class="form-control"
                                placeholder="Input Measurement" required
                                style="border-radius: 5px; border: 1px solid #ccc; padding: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                            <div id="measurementWarning" style="display: none; color: red;">Measurement must be a
                                positive number and should not start with zero.</div>
                        </div>
                        <div class="form-group">
                            <label class="modal-label" style="color: #259E9E;">Prescription</label>
                            <div class="form-check">
                                <input type="checkbox" name="prescription" class="form-check-input"
                                    id="prescriptionCheckbox" value="1" />
                                <label class="form-check-label" for="prescriptionCheckbox"
                                    style="color: #555; font-weight: normal;">Prescription required</label>
                            </div>

                            <!-- fix later -->
                            <!-- <div class="form-check"> ->
        <input type="checkbox" name="discounted" class="form-check-input" id="generic_discount_Checkbox" value="1" />
        <label class="form-check-label" for="generic_discount_Checkbox" style="color: #555; font-weight: normal;">Generic Discount required</label>
    </div>-->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary modal-btn mr-2"
                            style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none;  box-shadow: none; "
                            data-dismiss="modal">Cancel</button>
                        <button type="submit" id="submitButton" class="btn btn-primary modal-btn" name="add_prod_btn"
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
                    <h5 class="modal-title" id="deleteModalLabel">Delete Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-2"
                        style="border-radius: 5px; padding: 10px 20px; background-color: #828282; border: none; box-shadow: none;"
                        data-dismiss="modal">Cancel</button>
                    <!-- Ensure form submission on button click -->
                    <form id="deleteForm" action="code.php" method="POST">
                        <input type="hidden" id="delete_id" name="delete_id">
                        <button type="submit" name="delete_prod_btn" class="btn btn-danger"
                            style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; box-shadow: none;">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">

        <!-- DataTables Example -->
        <div class="card shadow nb-4">
            <div class="card-header py-3">
                <h1>Product</h1>
            </div>
            <div class="card-body">
                <h6 class="m-0 font-weight-bold text-primary">
                    <button type="button" class="btn btn-primary rounded-pill shadow" data-toggle="modal"
                        data-target="#addadminprofile"
                        style="background-color: #259E9E; border: none; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);">
                        <i class="fas fa-plus-square fa-lg"></i> <span
                            style="font-weight: bold; text-transform: uppercase;">Add New Product</span>
                    </button>
                </h6>
                <div class="table-responsive">
                    <?php
                    $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

                    // Query to fetch product details with total quantity per branch
                    $query = "SELECT p.id, p.prod_name, p.description, p.prod_code, p.categories, p.type, p.unit, p.measurement, 
                          SUM(CASE WHEN a.branch = 'Cell Med' THEN a.quantity ELSE 0 END) AS 'Cell Med',
                          SUM(CASE WHEN a.branch = '3G Med' THEN a.quantity ELSE 0 END) AS '3G Med',
                          SUM(CASE WHEN a.branch = 'Boom Care' THEN a.quantity ELSE 0 END) AS 'Boom Care',
                          p.prescription,  p.discounted 
                          FROM product_list p
                          LEFT JOIN add_stock_list a ON p.prod_name = a.product_stock_name";

                    // If a specific branch is selected, filter the products for that branch
                    if ($selectedBranch != 'All') {
                        $query .= " WHERE a.branch = '$selectedBranch'";
                    }

                    $query .= " GROUP BY p.id";

                    $query_run = mysqli_query($connection, $query);
                    ?>
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead style="background-color: #259E9E; color: white;">
                            <th style="vertical-align: middle;">ID </th>
                            <th style="vertical-align: middle;">Product Name </th>
                            <!-- <th> Product Code </th>-->
                            <th style="vertical-align: middle;">Category </th>
                            <th style="vertical-align: middle;">Type </th>
                            <th style="vertical-align: middle;">Unit </th>
                            <th style="vertical-align: middle;">Measurement </th>
                            <th style="vertical-align: middle;">Stocks Available</th>
                            <th style="vertical-align: middle;">Prescription </th>
                            <!--    <th> Has Discount </th>-->
                            <th style="vertical-align: middle;">Action</th>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    ?>
                                    <tr>
                                        <td style="vertical-align: middle;"><?php echo $row['id']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['prod_name']; ?></td>

                                        <td style="vertical-align: middle;"><?php echo $row['categories']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['type']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['unit']; ?></td>
                                        <td style="vertical-align: middle;"><?php echo $row['measurement']; ?></td>
                                        <td style="vertical-align: middle;">
                                            <?php
                                            if ($selectedBranch === 'All') {
                                                // Calculate the total quantity available across all branches
                                                $totalStocks = $row['Cell Med'] + $row['3G Med'] + $row['Boom Care'];
                                                echo $totalStocks;
                                            } else {
                                                // Display the quantity available for the selected branch
                                                echo $row[$selectedBranch];
                                            }
                                            ?>
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <?php echo ($row['prescription'] == 1) ? 'Yes' : 'No'; ?>
                                        </td>
                                        <!--  <td><?php echo ($row['discounted'] == 1) ? 'Yes' : 'No'; ?></td>-->
                                        <td style="vertical-align: middle;">
                                            <div style="display: inline-flex; justify-content: center; align-items: center;">
                                                <form action="edit_product.php" method="post" style="display: inline-block;">
                                                    <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="edit_btn" class="btn btn-action editBtn">
                                                        <i class="fas fa-edit" style="color: #44A6F1;"></i>
                                                    </button>
                                                </form>
                                            </div>

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
        function validateMeasurement() {
            var measurement = document.getElementById('measurementInput').value.trim();
            var measurementWarning = document.getElementById('measurementWarning');
            var submitButton = document.getElementById('submitButton');

            if (parseFloat(measurement) <= 0 || measurement.startsWith('0')) {
                measurementWarning.style.display = 'block';
                submitButton.disabled = true;
            } else {
                measurementWarning.style.display = 'none';
                submitButton.disabled = false;
            }
        }

        document.getElementById('measurementInput').addEventListener('input', validateMeasurement);
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
                    { "orderable": true, "targets": [0, 1, 2, 3, 4] }, // ID and Product Name columns are sortable
                    { "orderable": false, "targets": '_all' } // Disable sorting for all other columns
                ],
                "order": [[0, "desc"]] // Sort by the first column (ID) in descending order
            });
        });
    </script>

</body>

</html>