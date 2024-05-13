<?php
include("dbconfig.php");

if (isset($_POST['product_stock_name'])) {
    $product_stock_name = $_POST['product_stock_name'];

    // Update quantity in the database
    $update_query = "UPDATE add_stock_list SET quantity = quantity - 1 WHERE product_stock_name = '$product_stock_name'";
    mysqli_query($connection, $update_query);

    // Check if the update was successful
    if (mysqli_affected_rows($connection) > 0) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Product name not provided"));
}
?>
