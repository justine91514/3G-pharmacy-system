<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('includes/header_pos.php');
include('includes/navbar_pos.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier</title>
    <style>
        .container {
            max-width: 80%;
            display: flex;
            align-items: flex-start; /* Align items to the start (left side) */
        }

        .left-section {
            flex: 1;
        }

        .right-section {
            flex: 1;
            margin-left: 20px; /* Add margin between the two sections */
        }

        .product-info {
            text-align: left;
            margin-bottom: 20px;
        }

        .input-skulabel {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-section">
            <input type="text" class="form-control" id="dbpharmacy" autocomplete="off"
                placeholder="Search Transac no ...">
            <div id="searchresult" style="position: absolute; top: 100%; left: 0; right: 0; z-index: 999; background-color: #fff; border: 1px solid #ced4da; border-top: none; display: none;"></div>

            <table class="table table-bordered table-striped mt-4">
                <thead>
                    <tr>                                           
                        <th> Product Name </th>
                        <th> Quantity </th>
                        <th> Stocks Available </th>
                        <th> Price </th>
                    </tr>
                </thead>
                <tbody id="scannedItems">
                    <!-- Scanned items will be appended here -->
                </tbody>
            </table>
        </div>

        <div class="right-section">
            <div class="product-info">
                <h2>PRODUCT INFO</h2>
                <label class="input-skulabel" for="barcode">Barcode:</label>
                <input type="text" class="form-control" id="barcode" autocomplete="off">
                
                <label class="input-skulabel" for="descript">Description:</label>
                <input type="text" class="form-control" id="descript" autocomplete="off">
                
                <label class="input-skulabel" for="price">Price:</label>
                <input type="text" class="form-control" id="price" autocomplete="off">
                
                <label class="input-skulabel" for="quantity">Quantity:</label>
                <input type="text" class="form-control" id="quantity" autocomplete="off">

                <label class="input-skulabel" for="total">Total Amount:</label>
                <input type="text" class="form-control" id="total" autocomplete="off" readonly>

                <div class="container-fluid">

                <h6 class="m-0 font-weight-bold text-primary">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#payment">
                      Proceed To Payment
                    </button>
                </h6>
        
           <!-- Modal -->
                <div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Mode of Payment</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="code.php" method="POST">
                                <div class="modal-body">
                                    <div class="form-group">
                                    <label>Discounts</label>
                                    <label>Discounts</label>
                                        <select id="discountSelect" name="discount" class="form-control" required>
                                            
                                            <option value="">No Discount</option> <!-- Empty option -->
                                            <?php
                                            // Include the database connection file
                                            include('dbconfig.php');
                                            
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" name="categorybtn" class="btn btn-primary">Charge</button>
                            </div>
                        </form>

                        </div>
                    </div>
                </div>
        

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
    // Store the original amount
    var originalAmount = parseFloat($('#total').val());

    var scannedProducts = {};

    // Event listener for changes in the discount dropdown
    $('#discountSelect').change(function() {
        var totalAmount = parseFloat($('#total').val()); // Get the current total amount
        var discountValue = parseFloat($(this).val()); // Get the selected discount value
        
        // Check if a discount is selected
        if (!isNaN(discountValue)) {
            // Calculate the discounted total amount
            var discountedAmount = originalAmount - (originalAmount * (discountValue / 100));
            // Update the total amount input field with the discounted value
            $('#total').val(discountedAmount.toFixed(2)); // Assuming you want to display 2 decimal places
        } else {
            // If no discount selected, reset the total amount to the original value
            $('#total').val(originalAmount.toFixed(2)); // Reset to original amount
        }
    });

    // Event listener for barcode scanner
    $('#dbpharmacy').keypress(function(e) {
        if (e.which === 13) { // 13 is the ASCII code for Enter key
            var input = $(this).val();
            if (input != "") {
                $.ajax({
                    url: "livesearch.php",
                    method: "POST",
                    data: {
                        input: input
                    },

                    success: function(data) {
                        // Parse JSON response
                        var responseData = JSON.parse(data);
                        // Check if data exists
                        if (responseData.length > 0) {
                            // Update descript, price, and quantity fields
                            $('#descript').val(responseData[0].descript);
                            $('#price').val(responseData[0].price);
                            
                            if (scannedProducts.hasOwnProperty(responseData[0].descript)) {
                                scannedProducts[responseData[0].descript]++;
                                // Update the quantity in the input field
                                $('#quantity').val(scannedProducts[responseData[0].descript]);
                                // Update the quantity in the existing table row
                                $('#scannedItems td:contains("' + responseData[0].descript + '")').next().text(scannedProducts[responseData[0].descript]);
                            } else {
                                scannedProducts[responseData[0].descript] = 1;
                                // Append new rows to the table
                                var html = responseData[0].html.replace("<td></td>", "<td>" + scannedProducts[responseData[0].descript] + "</td>");
                                $('#scannedItems').append(html);
                                // Update the quantity in the input field
                                $('#quantity').val(scannedProducts[responseData[0].descript]);
                            }

                            // Calculate total amount
                            var totalAmount = 0;
                            $('#scannedItems tr').each(function() {
                                var quantity = parseFloat($(this).find('td:eq(1)').text());
                                var price = parseFloat($(this).find('td:eq(3)').text());
                                totalAmount += quantity * price;
                            });
                            // Set total amount in the input field
                            $('#total').val(totalAmount.toFixed(2)); // Assuming you want to display 2 decimal places
                            // Update original amount
                            originalAmount = totalAmount;
                        } else {
                            // If no data found, clear the fields
                            $('#descript').val('');
                            $('#price').val('');
                            $('#quantity').val('');
                            // Clear total amount
                            $('#total').val('');
                        }
                        // Populate the Barcode input field with the scanned value
                        $('#barcode').val(input);
                    }
                });
            }
            // Clear the input field after scanning
            $(this).val('');
            // Prevent form submission
            e.preventDefault();
        }
    });
});

</script>
             
</body>
</html>

    <!-- Logout Modal Popup + Logout Action -->
    <?php
        include 'logout_modal.php';
    ?>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>