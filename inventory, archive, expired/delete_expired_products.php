<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('dbconfig.php');

if (isset($_POST['delete_category_btn'])) {
    $id_to_delete = $_POST['delete_id'];
    echo "ID to delete: " . $id_to_delete; // Add this line for debugging
    $delete_query = "DELETE FROM expired_list WHERE id = '$id_to_delete'";
    echo "Delete query: " . $delete_query; // Add this line for debugging
    $delete_result = mysqli_query($connection, $delete_query);

    if ($delete_result) {
        header('Location: expired_products.php'); // Redirect back to the page with the table
        exit();
    } else {
        header('Location: expired_products.php'); // Redirect back to the page with the table
        exit();
    }
}
?>
