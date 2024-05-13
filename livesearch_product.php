<?php
include("dbconfig.php");

if(isset($_GET['sku'])) {
    $sku = $_GET['sku'];
    $query = "SELECT product_stock_name, descript FROM add_stock_list WHERE sku = '$sku'";
    $result = mysqli_query($connection, $query);
    
    if($result) {
        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $product_name = $row['product_stock_name'];
            $descript = $row['descript'];
            $response = array("product_name" => $product_name, "descript" => $descript);
            echo json_encode($response);
        } else {
            echo "No data found for SKU: $sku";
        }
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

?>
