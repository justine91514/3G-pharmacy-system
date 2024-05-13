<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// if already login, go to destination
if (isset($_SESSION['id'])) {
    try {
        if ($_SESSION['usertype'] == "admin") {
            include 'dashboard.php';
        } elseif ($_SESSION['usertype'] == "pharmacy_assistant") {
            include 'pos.php';
        }
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
} else {
    // if user is not logged in, go to login
    include 'login.php';
}
?>
