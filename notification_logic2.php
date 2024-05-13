<?php
$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

// Query to get the expiring soon count and details for add_stock_list
$expiring_soon_query = "SELECT COUNT(*) as expiring_soon_count, GROUP_CONCAT(product_stock_name) as expiring_soon_products FROM add_stock_list WHERE expiry_date BETWEEN CURDATE() AND CURDATE() + INTERVAL 7 DAY";
$expiring_soon_result = mysqli_query($connection, $expiring_soon_query);
$expiring_soon_data = mysqli_fetch_assoc($expiring_soon_result);
$expiring_soon_count = $expiring_soon_data['expiring_soon_count'];
$expiring_soon_products = $expiring_soon_data['expiring_soon_products'];

// Query to get the expiring soon count and details for buffer_stock_list
$expiring_soon_buffer_query = "SELECT COUNT(*) as expiring_soon_buffer_count, GROUP_CONCAT(buffer_stock_name) as expiring_soon_buffer_products FROM buffer_stock_list WHERE expiry_date BETWEEN CURDATE() AND CURDATE() + INTERVAL 7 DAY";
$expiring_soon_buffer_result = mysqli_query($connection, $expiring_soon_buffer_query);
$expiring_soon_buffer_data = mysqli_fetch_assoc($expiring_soon_buffer_result);
$expiring_soon_buffer_count = $expiring_soon_buffer_data['expiring_soon_buffer_count'];
$expiring_soon_buffer_products = $expiring_soon_buffer_data['expiring_soon_buffer_products'];

// Query to get the count of expired products
$expired_count_query = "SELECT COUNT(*) as expired_count FROM expired_list";
$expired_count_result = mysqli_query($connection, $expired_count_query);
$expired_count_data = mysqli_fetch_assoc($expired_count_result);
$expired_count = $expired_count_data['expired_count'];

// Query to get the count of low stock items
$low_stock_count_query = "SELECT COUNT(*) as low_stock_count FROM add_stock_list WHERE quantity < 20";
$low_stock_count_result = mysqli_query($connection, $low_stock_count_query);
$low_stock_count_data = mysqli_fetch_assoc($low_stock_count_result);
$low_stock_count = $low_stock_count_data['low_stock_count'];

$out_count_query = "SELECT COUNT(*) as out_count FROM out_of_stock_list";
$out_count_result = mysqli_query($connection, $out_count_query);
$out_count_data = mysqli_fetch_assoc($out_count_result);
$out_count = $out_count_data['out_count'];
?>

<!-- the script of badge icon ay nasa bwiset na scripts.php -->