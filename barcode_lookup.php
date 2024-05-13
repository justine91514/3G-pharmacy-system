<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('dbconfig.php'); // Include your database connection file

if(isset($_POST['barcode'])) {
    $barcode = $_POST['barcode'];

    // Query to fetch product information based on barcode
    $query = "SELECT * FROM add_stock_list WHERE sku = :barcode";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':barcode', $barcode);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return product information as JSON
    echo json_encode($result);
} else {
    // Handle other cases or errors
    echo json_encode(['error' => 'Invalid request']);
}
?>
buburahin?