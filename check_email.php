<?php
// Include your database connection file here
include 'dbconfig.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if the email already exists
    $email_check_query = "SELECT * FROM register WHERE email='$email' LIMIT 1";
    $result = mysqli_query($connection, $email_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        echo 'exists';
    } else {
        echo 'not_exists';
    }
}
?>
