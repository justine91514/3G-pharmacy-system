<?php
include 'dbconfig.php'; // Include your database connection file

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "UPDATE register SET is_disabled = 1 WHERE id = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Account disabled successfully.";
    } else {
        echo "Failed to disable account.";
    }
}
?>
