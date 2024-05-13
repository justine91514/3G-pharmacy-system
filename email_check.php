// email_check.php
<?php
include 'dbconfig.php'; // Include your database connection file

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $query = "SELECT * FROM register WHERE email = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "exists";
    } else {
        echo "not_exists";
    }
}
?>
