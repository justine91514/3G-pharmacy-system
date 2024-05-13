<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'dbconfig.php';

/************************
RETRIEVE DATA FROM AJAX REQUEST
************************/
$scannedItems = json_decode($_POST['scannedItems'], true);

/************************
CREATE LOG FILE
************************/
$logFile = 'update_stock_quantities_log.txt';
if (!file_exists($logFile)) {
    touch($logFile);
}

/************************
UPDATE QUANTITIES
************************/
try {
    $connection->begin_transaction();

    // Sort scanned items by batch number in ascending order
    usort($scannedItems, function($a, $b) {
        return $a['batch_number'] <=> $b['batch_number'];
    });

    foreach ($scannedItems as $item) {
        $product_stock_name = $item['product_stock_name'];
        $quantity = $item['quantity'];

        /************************
            UPDATE "quantity" and "stocks_available" columns
        ************************/
        $updateQuery = "UPDATE add_stock_list SET quantity = quantity - ?, stocks_available = stocks_available - ? WHERE product_stock_name = ? ORDER BY batch_number ASC LIMIT 1";
        $stmt = $connection->prepare($updateQuery);
        $stmt->bind_param("iis", $quantity, $quantity, $product_stock_name);

        if (!$stmt->execute()) {
            throw new Exception("Error updating quantities for item $product_stock_name: " . $stmt->error);
        }

        /************************
            Log the updated item and quantity
        ************************/
        $logMessage = "Item: $product_stock_name, Quantity: $quantity\n";
        file_put_contents($logFile, $logMessage, FILE_APPEND);

        $stmt->close();
    }

    $connection->commit();
    $logMessage = "Quantities updated successfully.\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
} catch (Exception $e) {
    $connection->rollback();
    $errorMessage = "Error updating quantities: " . $e->getMessage() . "\n";
    file_put_contents($logFile, $errorMessage, FILE_APPEND);
} finally {
    $connection->close();
}
?>
