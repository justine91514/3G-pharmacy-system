<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST['scannedProducts'])) {
    $_SESSION['scannedProducts'] = $_POST['scannedProducts'];
    echo "Scanned products saved successfully";
} else {
    echo "Error: Scanned products not received";
}
?>
