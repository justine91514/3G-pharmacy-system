<?php
// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection or any necessary files
    include 'dbconfig.php'; // Include your database connection file
    
    // Get form data
    $supplier = mysqli_real_escape_string($connection, $_POST['supplier']);
    $items = mysqli_real_escape_string($connection, $_POST['product']);
    $branch = mysqli_real_escape_string($connection, $_POST['branch']);
    $quantity = intval($_POST['quantity']); // Convert to integer to avoid SQL injection
    
    // Perform any necessary validation
    
    // Insert the purchase order into the database
    $query = "INSERT INTO purchase_orders (supplier, product, branch, quantity, date_added) VALUES ('$supplier', '$items', '$branch', '$quantity', CURDATE())";
    $result = mysqli_query($connection, $query);
    
    // Check if the query was successful
    if ($result) {
        header("Location: purchase_order.php");
        exit();
    } else {
        // Handle error
        echo "Error: " . mysqli_error($connection);
    }
}
?>
