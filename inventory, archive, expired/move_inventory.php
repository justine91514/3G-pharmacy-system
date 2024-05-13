<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('dbconfig.php');

if(isset($_POST['restore_category_btn'])) {
    // Check if restore_id is set and not empty
    if(isset($_POST['restore_id']) && !empty($_POST['restore_id'])) {
        $restore_id = $_POST['restore_id'];

        // Fetch the archived item details from the database
        $query = "SELECT * FROM archive_list WHERE id = $restore_id";
        $result = mysqli_query($connection, $query);

        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Insert the archived item into the stock table with current timestamp
            $current_date = date('Y-m-d H:i:s');
            $insert_query = "INSERT INTO add_stock_list (id, sku, product_stock_name, quantity, price, branch, batch_no, expiry_date, date_added)
                             VALUES ('{$row['id']}', '{$row['sku']}', '{$row['product_name']}', '{$row['quantity']}',  '{$row['price']}',  '{$row['branch']}',   '{$row['batch_no']}', '{$row['expiry_date']}', '$current_date')";
            mysqli_query($connection, $insert_query);

            // Delete the item from the archive_list table
            $delete_query = "DELETE FROM archive_list WHERE id = $restore_id";
            mysqli_query($connection, $delete_query);

            // Redirect to add_stocks.php after archiving
            header("Location: add_stocks.php");
            exit();
        } else {
            // Record not found
            echo "Record not found!";
        }
    } else {
        // restore_id not provided
        echo "Restore ID not provided!";
    }
}
?>
