<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("dbconfig.php");

if (isset($_POST['archive_id'])) {
    $archive_id = $_POST['archive_id'];

    // Get data of the selected row from add_stock_list
    $query = "SELECT * FROM add_stock_list WHERE id = $archive_id";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

    // Insert the data into archive_list including the id
    $insert_query = "INSERT INTO archive_list (id, sku, product_name, quantity, price, branch, batch_no, expiry_date) 
                     VALUES ('{$row['id']}', '{$row['sku']}', '{$row['product_stock_name']}', '{$row['quantity']}',  '{$row['price']}', '{$row['branch']}',  '{$row['batch_no']}', '{$row['expiry_date']}')";
    mysqli_query($connection, $insert_query);

    // Delete the row from add_stock_list
    $delete_query = "DELETE FROM add_stock_list WHERE id = $archive_id";
    mysqli_query($connection, $delete_query);

    // Redirect back to add_stocks.php or wherever needed
    header("Location: add_stocks.php");
    exit();
}
?>
