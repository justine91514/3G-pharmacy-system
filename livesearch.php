<?php
include("dbconfig.php");
if (isset($_POST['input'])) {

    $input = $_POST['input'];

    $query = "SELECT add_stock_list.*, product_list.prod_code 
          FROM add_stock_list
          JOIN product_list ON add_stock_list.product_stock_name = product_list.prod_name
          WHERE add_stock_list.sku LIKE '{$input}%'";

    $result = mysqli_query($connection, $query);

    $response = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id'];
            $sku = $row['sku'];
            $product_stock_name = $row['product_stock_name'];
            $descript = $row['descript'];
            $quantity = 1; // Set default quantity to 1
            $stocks_available = $row['stocks_available']; // Corrected
            $expiry_date = $row['expiry_date'];
            $price = $row['price'];
            $branch = $row['branch'];
            $quant2 = $row['quantity'];

            $measurement = $row['measurement'];
            
            // Build HTML for appending to the table
            $html = "<tr>
                        <td>{$product_stock_name} - <span style='font-size: 80%;'>{$measurement}</span></td>
                        <td>{$quantity}</td>
                        <td>{$stocks_available}</td>
                        <td>{$price}</td>
                    </tr>";
            // Add data to response array
            $response[] = array(
                'descript' => $descript,
                'price' => $price,
                'quant2' => $quant2,
                'product_stock_name' => $product_stock_name,
                'branch' => $branch,
                'measurement' => $measurement,
                'html' => $html
            );
        }
    } else {
        echo "<h6 class ='text-danger text-center my-3'>no data found</h6>";
        // Send JSON response with empty array
        //echo json_encode($response);
        //exit(); // Terminate script execution here
    }
    // Send JSON response
    echo json_encode($response);
}
?>

