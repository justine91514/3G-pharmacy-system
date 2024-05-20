<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'includes/header_pos.php';
include 'includes/navbar_pos.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier</title>
    <link rel="stylesheet" href="hays.css">
    <style>
        .container-fluid {
            margin-top: 93px;
            /* Adjust the value as needed */
            padding-bottom: 80px;
            /* Add padding at the bottom */
        }

        .card {
            height: 100%;
            /* Make the card fill its container */
        }

        .no-scroll {
            overflow: hidden;
        }
        
    </style>

<body class="no-scroll">

    <?php
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Error:</strong> ' . $_SESSION['status'] . '
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>';
        unset($_SESSION['status']);
    }
    ?>

    <div class="container-fluid" style="width: 90%; padding-bottom: 80px; margin-bottom: 100px;">

        <div class="card shadow nb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5" style="margin-left: 5px;"> <!-- Left Section -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h2 class="d-flex align-items-center">
                                    <img src="img/ah.png" alt="Product Info Image" class="mr-2"
                                        style="max-width: 100%; height: auto;">
                                </h2>
                            </div>
                            <div class="card-body">
                                <input type="hidden" class="form-control" id="product_stock_name" autocomplete="off">
                                <label class="input-skulabel" for="barcode" id="productInfoLabel">Barcode:</label>
                                <input type="text" class="form-control" id="barcode" autocomplete="off" readonly>
                                <label class="input-skulabel" for="descript" id="productquantLabel">Description:</label>
                                <input type="text" class="form-control" id="descript" autocomplete="off" readonly>
                                <label class="input-skulabel" for="price" id="productStocksLabel">Price:</label>
                                <input type="text" class="form-control" id="price" autocomplete="off" readonly>
                                <label class="input-skulabel" for="quantity" id="productpriceLabel">Quantity:</label>
                                <input type="text" class="form-control" id="quantity" name="quantity" autocomplete="off"
                                    readonly>
                                <div style="margin-top: 15px; margin-bottom: -25px;">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#payment"
                                        style="border-radius: 5px; padding: 10px 20px; background-color: #259E9E; border: none;">
                                        Proceed To Payment
                                    </button>
                                    <button type="button" name="void_btn" class="btn btn-primary" id="voidButton"
                                        style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; margin-left: 10px;">Void</button>
                                    <button type="button" name="delete_void_btn" class="btn btn-primary"
                                        id="delete_void_Button"
                                        style="border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; margin-left: 10px;">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Vertical Divider -->
                    <div class="col-md-6" style="border-left: 1px solid #ddd; margin-left:15px;"> <!-- Right Section -->
                        <div class="right-section card shadow mb-4"
                            style="border-left: 1px solid #ddd; margin-left: 15px; margin-top: -0px; width: 667px; overflow-x: auto;">
                            <!-- Add border -->
                            <input type="text" class="form-control" id="dbpharmacy" autocomplete="off"
                                placeholder="Input SKU...">
                            <div id="searchresult"
                                style="position: absolute; top: 100%; left: 0; right: 0; z-index: 999; background-color: #fff; border: 1px solid #ced4da; border-top: none; display: none;">
                            </div>
                            <table class="table table-bordered table-striped mt-4 table-fixed">
                                <thead>
                                    <tr>
                                        <th> Product Name </th>
                                        <th> Quantity </th>
                                        <th> Meas. </th>
                                        <th> Stocks Available </th>
                                        <th> Price </th>
                                        <th> Branch </th>
                                    </tr>
                                </thead>
                                <tbody id="scannedItems">
                                    <!-- Scanned items will be appended here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header" style="color: #304B1B;">
                <h5 class="modal-title" id="exampleModalLabel"><strong>Mode of Payment</strong></h5>
            </div>
            <form action="code.php" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="input-skulabel" for="sub_total" style="color: #304B1B;">Sub Total:</label>
                        <input type="text" class="form-control" name="sub_total" id="sub_total" autocomplete="off"
                            readonly>
                        <label style="margin-top: 10px; color: #304B1B;"><strong>Discounts:</strong></label>
                        <select id="discountSelect" name="discount" class="form-control">
                            <option value="">No Discount</option> <!-- Empty option -->
                            <?php
                            // Include the database connection file
                            include 'dbconfig.php';
                            // Fetch discount options from the database
                            $query = "SELECT * FROM discount_list";
                            $result = mysqli_query($connection, $query);
                            // Loop through the results and display each discount option
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='{$row['value']}'>{$row['discount_name']} - {$row['value']}%</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <label class="input-skulabel" for="total" style="color: #304B1B;">Grand Total:</label>
                    <input type="text" class="form-control" name="total" id="total" autocomplete="off" readonly>
                    <input type="hidden" id="payment_mode" name="mode_of_payment">
                    <div>
                        <label style="margin-top: 10px; color: #304B1B;"><strong>Select Payment Mode:</strong></label>
                        <div>
                            <input type="radio" id="cashRadio" name="mode_of_payment" value="Cash" checked>
                            <label for="cashRadio">Cash</label>
                        </div>
                        <div>
                            <input type="radio" id="gcashRadio" name="mode_of_payment" value="G-Cash">
                            <label for="gcashRadio">G-Cash</label>
                        </div>
                    </div>
                    <label style="margin-left: 15px; width: 100px; color: #304B1B;"><strong>Reference#:</strong></label>
                    <input type="text" name="ref_no" class="form-control" id="referenceInput" readonly style="margin-left: 15px; width: 450px;">
                    <label style="margin-top: 10px; color: #304B1B;"><strong>Cash:</strong></label>
                    <input type="text" class="form-control" id="cash">
                    <label style="margin-top: 10px; color: #304B1B;"><strong>Change:</strong></label>
                    <input type="text" class="form-control" id="change" readonly>
                    <label for="modal_quantity" style="display: none;">Quantity:</label>
                    <input type="text" name="quantity" class="form-control" id="modal_quantity" autocomplete="off" style="display: none;">
                </div>
                <div class="modal-footer" style="margin-top: 20px;">
                    <button type="button" class="btn btn-primary modal-btn mr-2"
                        style="margin-top: 5px; border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; box-shadow: none;"
                        data-dismiss="modal">Cancel</button>
                    <!-- Existing form fields -->
                    <input type="hidden" name="full_name"
                        value="<?php echo $user_info['first_name'] . ' ' . $user_info['mid_name'] . ' ' . $user_info['last_name']; ?>">
                    <input type="hidden" name="branch"
                        value="<?php echo htmlspecialchars($user_info['branch'] ?? 'Default Branch'); ?>">
                    <button type="submit" name="charge_btn" class="btn btn-primary" id="chargeButton"
                        style="margin-top: 5px; border-radius: 5px; padding: 10px 20px; background-color: #304B1B; border: none; box-shadow: none;"
                        disabled>Charge</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Maximum Quantity Reached Modal -->
<div class="modal fade" id="maxQuantityModal" tabindex="-1" role="dialog" aria-labelledby="maxQuantityModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="maxQuantityModalLabel">Maximum Quantity Reached</h5>
            </div>
            <div class="modal-body">
                The maximum quantity for this product has been reached.
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary modal-btn mr-2"
                        style="margin-top: 5px; border-radius: 5px; padding: 10px 20px; background-color: #EB3223; border: none; box-shadow: none;"
                        data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


    <script>
        document.getElementById('charge_btn').addEventListener('click', function () {
            // Retrieve the desired quantity value from somewhere, for example, an input field with ID 'desired_quantity'
            var desiredQuantity = document.getElementById('desired_quantity').value;
            // Populate the quantity input field
            document.getElementById('quantity').value = desiredQuantity;
            // Submit the form
            document.getElementById('charge_form').submit(); // Assuming your form has the ID 'charge_form'
        });
    </script>
    <!-- to calculate the total amount-->
    <script>
        $(document).ready(function () {
            var scannedProducts = {};
            // Function to calculate subtotal
            function calculateSubtotal() {
                var totalAmount = 0;
                $('#scannedItems tr').each(function () {
                    var quantity = parseFloat($(this).find('td:eq(1)').text());
                    var price = parseFloat($(this).find('td:eq(3)').text());
                    totalAmount += quantity * price;
                });
                return totalAmount;
            }

            // Function to update subtotal, total, and change
            function updateTotals() {
                var subtotal = calculateSubtotal();
                var discountValue = parseFloat($('#discountSelect').val());
                if (!isNaN(discountValue)) {
                    var discountedAmount = subtotal - (subtotal * (discountValue / 100));
                    $('#total').val(discountedAmount.toFixed(2));
                } else {
                    $('#total').val(subtotal.toFixed(2));
                }
                calculateChange();
            }
            // Event listener for the Delete button
            $('#delete_void_Button').click(function () {
                // Confirm if any row is selected
                if ($('#scannedItems tr.selected').length >= 0) {
                    // Remove each selected row
                    $('#scannedItems tr.selected').remove();

                    // Recalculate and update subtotal, total, and change
                    updateTotals();
                }
            });
            // Event listener for the "Void" button
            $('#voidButton').click(function () {
                // Reset all fields if "Void" is clicked
                if ($(this).text() === 'Void') {
                    $('#scannedItems tr').remove();
                    updateTotals();
                }
            });
            // Event listener for changing discount
            $('#discountSelect').change(function () {
                updateTotals();
            });
            // Function to calculate change
            function calculateChange() {
                var cash = parseFloat($('#cash').val());
                var total = parseFloat($('#total').val());

                if (!isNaN(cash)) {
                    var change = cash - total;
                    if (change >= 0) {
                        $('#change').val(change.toFixed(2));
                    } else {
                        $('#change').val('not enough money, add more');
                    }
                } else {
                    $('#change').val('');
                }
            }
        });
    </script>
    <script>
        $(document).ready(function () {
            // Store the original total amount
            var originalTotal = parseFloat($('#total').val());

            function updateProductCount() {
                var count = $('#scannedItems tr').length;
                $('#productCount').val(count);
            }
            $('#discountSelect').change(function () {
                // Get the selected discount value
                var discountValue = parseFloat($(this).val());
                // If a valid discount value is selected
                if (!isNaN(discountValue)) {
                    // Calculate the discounted total
                    var discountedTotal = originalTotal - (originalTotal * (discountValue / 100));
                    // Update the total input field with the discounted total
                    $('#total').val(discountedTotal.toFixed(2));
                } else {
                    // If no discount is selected, revert back to the original total
                    $('#total').val(originalTotal.toFixed(2));
                }
            });
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        // Event listener for radio button change
        document.querySelectorAll('input[name="mode_of_payment"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
                var referenceInput = document.getElementById('referenceInput');
                if (this.value === 'G-Cash') {
                    referenceInput.removeAttribute('readonly');
                } else {
                    referenceInput.setAttribute('readonly', true);
                }
            });
        });
        function selectPaymentMode(mode) {
            document.getElementById('payment_mode').value = mode;
        }
    </script>
    <script>
        $(document).ready(function () {
            var voidButtonClicked = false;

            // Deactivate the "Void" button initially
            $('#voidButton').prop('disabled', true);
            $('#delete_void_Button').hide(); // Hide the Delete button initially
            $('#voidButton').click(function () {
                if ($(this).text() === 'Void') {
                    voidButtonClicked = false;
                    $(this).text('Cancel Void');
                    $('#productInfoLabel').text('Product Name:');
                    $('#productquantLabel').text('Quantity:');

                    $('#productStocksLabel').text('Stocks Available:');
                    $('#productpriceLabel').text('Price:');

                    $('#barcode').val('');
                    $('#descript').val('');
                    $('#price').val('');
                    $('#quantity').val('');
                    enableTableRowSelection();

                    $('#delete_void_Button').show();
                } else {
                    voidButtonClicked = true;
                    $(this).text('Void');
                    $('#productInfoLabel').text('Barcode:');
                    $('#productquantLabel').text('Description:');
                    $('#productStocksLabel').text('Price:');
                    $('#productpriceLabel').text('Quantity:');

                    $('#barcode').val('');
                    $('#descript').val('');
                    $('#price').val('');
                    $('#quantity').val('');
                    $('#delete_void_Button').hide();
                    enableTableRowSelection();
                }
            });
            // Check the content of #scannedItems before enabling or disabling the "Void" button
            function checkTableContent() {
                if ($('#scannedItems tr').length > 0) {
                    $('#voidButton').prop('disabled', false);
                } else {
                    $('#voidButton').prop('disabled', true);
                }
            }
            function disableTableRowSelection() {
                $('#scannedItems').off('click', 'tr'); // Turn off the event listener for clicking on table rows
                $('#scannedItems tr').removeClass('selected'); // Remove the selected class from all rows
                $('#barcode').val('');
                $('#descript').val('');
                $('#price').val('');
                $('#quantity').val('');
            }
            function enableTableRowSelection() {
                $('#scannedItems').on('click', 'tr', function () {
                    if (!voidButtonClicked) {
                        if (!$(this).hasClass('selected')) {
                            $('#barcode').val('');
                            $('#descript').val('');
                            $('#price').val('');
                            $('#quantity').val('');
                        }
                        $(this).toggleClass('selected').siblings().removeClass('selected');
                        if ($(this).hasClass('selected')) {
                            var productNameWithMeasurement = $(this).find('td:eq(0)').text();
                            var quantity = $(this).find('td:eq(1)').text();
                            var stocksAvailable = $(this).find('td:eq(3)').text();
                            var price = $(this).find('td:eq(4)').text();

                            $('#barcode').val(productNameWithMeasurement);
                            $('#descript').val(quantity);
                            $('#price').val(stocksAvailable);
                            $('#quantity').val(price);
                        }
                    }
                });
            }
            // Call the checkTableContent function whenever there is a change in table content
            $('#scannedItems').on('DOMSubtreeModified', function () {
                checkTableContent();
            });
            // Check the table content initially
            checkTableContent();
        });

        //code for delete void button
        $(document).ready(function () {
            // Event listener for the Delete button
            $('#delete_void_Button').click(function () {
                // Confirm if any row is selected
                if ($('#scannedItems tr.selected').length >= 0) {
                    // Remove each selected row
                    $('#scannedItems tr.selected').remove();
                    // After deletion, clear the selected fields
                    var totalAmount = 0;
                    $('#scannedItems tr').each(function () {
                        var quantity = parseFloat($(this).find('td:eq(1)').text());
                        var price = parseFloat($(this).find('td:eq(4)').text());
                        totalAmount += quantity * price;

                    });
                    // Update Sub Total
                    $('#sub_total').val(totalAmount.toFixed(2));
                    // Check if there's a discount applied
                    var discountValue = parseFloat($('#discountSelect').val());
                    if (!isNaN(discountValue)) {
                        var discountedAmount = totalAmount - (totalAmount * (discountValue / 100));
                        $('#total').val(discountedAmount.toFixed(2));
                    } else {
                        $('#total').val(totalAmount.toFixed(2));
                    }
                    // Recalculate change
                    calculateChange();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            // Event listener para sa pagbabago sa filter ng branch
            $('#branch_filter').change(function () {
                var selectedBranch = $(this).val(); // Kunin ang value ng napiling branch
                // Gumawa ng AJAX request para kunin ang data base sa napiling branch
                $.ajax({
                    url: 'add_stocks.php',
                    method: 'POST',
                    data: { branch: selectedBranch },
                    success: function (response) {
                        // I-update ang mga elemento sa HTML base sa resulta ng AJAX request
                        $('#scannedItems').html(response); // Halimbawa, idine-display dito ang resulta sa #scannedItems
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            var originalAmount = parseFloat($('#total').val());
            var originalPrice = originalAmount; // Store original price
            var scannedProducts = {};
            $('#discountSelect').change(function () {
                var discountValue = parseFloat($(this).val());
                if (!isNaN(discountValue)) {
                    var discountedAmount = originalAmount - (originalAmount * (discountValue / 100));
                    $('#total').val(discountedAmount.toFixed(2));
                    calculateChange(); // Recalculate change when discount changes
                } else {
                    $('#total').val(originalAmount.toFixed(2));
                    calculateChange(); // Recalculate change when discount is removed
                }
            });
            $('#cash').on('input', function () {
                var cash = parseFloat($(this).val());
                // Check if the Cash input field is not empty
                if (!isNaN(cash) && cash > 0) {
                    // Enable the charge button
                    $('#chargeButton').prop('disabled', false);
                } else {
                    // Disable the charge button if empty
                    $('#chargeButton').prop('disabled', true);
                }
                calculateChange();
            });
            // Function to calculate change
            function calculateChange() {
                var cash = parseFloat($('#cash').val());
                var total = parseFloat($('#total').val());

                if (!isNaN(cash)) {
                    var change = cash - total;
                    if (change >= 0) {
                        $('#change').val(change.toFixed(2));
                    } else {
                        $('#change').val('not enough money, add more');
                    }
                } else {
                    $('#change').val('');
                }
            }

            //the code for the quantity limit is in here
            $('#dbpharmacy').keypress(function (e) {
                if (e.which === 13) {
                    var input = $(this).val();
                    var branch = $('#branch_filter').val();
                    if (input != "") {
                        $.ajax({
                            url: "livesearch.php",
                            method: "POST",
                            data: { input: input, branch: branch },
                            success: function (data) {
                                var responseData = JSON.parse(data);
                                if (responseData.length > 0) {
                                    if (parseInt(responseData[0].quant2) <= 0) {
                                        // Display out of stock modal
                                        $('#outOfStockModal').modal('show');
                                        return; // Stop further execution
                                    }
                                    $('#descript').val(responseData[0].descript);
                                    $('#price').val(responseData[0].price);
                                    $('#product_stock_name').val(productNameWithMeasurement);
                                    $('#branch').val(responseData[0].branch);

                                    var productName = responseData[0].product_stock_name;
                                    var measurement = responseData[0].measurement;
                                    // var productNameWithMeasurement = productName + ' - ' + measurement;
                                    var productNameWithMeasurement = productName;
                                    var stocksAvailable = parseInt(responseData[0].quant2);
                                    var price = responseData[0].price;
                                    var branch = responseData[0].branch;

                                    if (scannedProducts.hasOwnProperty(productNameWithMeasurement)) {
                                        var quantityInput = parseInt(scannedProducts[productNameWithMeasurement]);
                                        if (quantityInput < stocksAvailable) {
                                            scannedProducts[productNameWithMeasurement]++;
                                            $('#quantity').val(scannedProducts[productNameWithMeasurement]);
                                            $('#scannedItems td:contains("' + productNameWithMeasurement + '")').next().text(scannedProducts[productNameWithMeasurement]);
                                        } else {
                                            // Show modal or alert indicating that stock limit is reached
                                            $('#maxQuantityModal').modal('show');
                                            return; // Exit function to prevent further execution
                                        }
                                    } else {
                                        scannedProducts[productNameWithMeasurement] = 1;
                                        var html = "<tr>" +
                                            "<td>" + productNameWithMeasurement + "</td>" +
                                            "<td>" + scannedProducts[productNameWithMeasurement] + "</td>" +
                                            "<td>" + measurement + "</td>" +
                                            "<td>" + stocksAvailable + "</td>" +
                                            "<td>" + price + "</td>" +
                                            "<td>" + branch + "</td>" +
                                            "</tr>";

                                        $('#scannedItems').append(html);

                                        $('#quantity').val(scannedProducts[productNameWithMeasurement]);
                                    }

                                    // Update Total Price
                                    var totalAmount = 0;
                                    $('#scannedItems tr').each(function () {
                                        var quantity = parseFloat($(this).find('td:eq(1)').text());
                                        var price = parseFloat($(this).find('td:eq(4)').text());
                                        totalAmount += quantity * price;
                                    });
                                    $('#sub_total').val(totalAmount.toFixed(2));

                                    // Update Total Amount only if no discount applied
                                    var discountValue = parseFloat($('#discountSelect').val());
                                    if (isNaN(discountValue)) {
                                        $('#total').val(totalAmount.toFixed(2));
                                        calculateChange();
                                    }

                                    originalAmount = totalAmount;

                                    // Store scanned products in session
                                    $.ajax({
                                        url: "store_scanned_products.php",
                                        method: "POST",
                                        data: { scannedProducts: scannedProducts },
                                        success: function (response) {
                                            console.log(response);
                                        }
                                    });

                                    // After appending HTML to the table, store the productList in a hidden input field
                                    var productList = [];
                                    $('#scannedItems tr').each(function () {
                                        var productName = $(this).find('td:eq(0)').text();
                                        productList.push(productName);
                                    });

                                    $('<input>').attr({
                                        type: 'hidden',
                                        id: 'productList',
                                        name: 'productList',
                                        value: JSON.stringify(productList)
                                    }).appendTo('form');

                                    $.ajax({
                                        url: "code.php",
                                        method: "POST",
                                        data: {
                                            scannedProducts: scannedProducts,
                                            productList: JSON.stringify(productList)
                                        },
                                        success: function (response) {
                                            console.log(response);
                                        }
                                    });
                                } else {
                                    $('#descript').val('');
                                    $('#price').val('');
                                    $('#quantity').val('');
                                    $('#total').val('');
                                }
                                $('#barcode').val(input);
                            }
                        });
                    }
                    $(this).val('');
                    e.preventDefault();
                }
            });


            $('form').submit(function () {
                $('#productName').val($('#product_stock_name').val());
                $('#quantity').val($('#quantity').val());
                $('#price').val($('#price').val());
            });

            // Initial calculation of change
            calculateChange();
        });
    </script>
    <!-- ------------------------
                    UPDATE QUANTITIES
                ------------------------- -->
    <script>
        document.getElementById('chargeButton').addEventListener('click', function () {
            /************************
             Retrieve the selected products from the tbl
            ************************/
            var scannedItems = [];
            $('#scannedItems tr').each(function () {
                var productNameWithMeasurement = $(this).find('td:eq(0)').text();
                var quantity = parseFloat($(this).find('td:eq(1)').text());
                scannedItems.push({
                    product_stock_name: productNameWithMeasurement,
                    quantity: quantity
                });
            });
            /************************
             Update the quantities
            ************************/
            $.ajax({
                url: 'update_stock_quantities.php',
                type: 'POST',
                data: { scannedItems: JSON.stringify(scannedItems) },
                success: function (response) {
                    console.log(response);
                    document.getElementById('charge_form').submit();
                },
                error: function (xhr, status, error) {
                    console.error('Error updating stock quantities:', error);
                }
            });
        });
    </script>
</body>
<!-- Out of Stock Modal -->
<div class="modal fade" id="outOfStockModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Out of Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                The selected product is currently out of stock.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</html>
<?php
include ('includes/pos_logout.php');
include 'includes/scripts.php';
include 'includes/footer.php';
?>