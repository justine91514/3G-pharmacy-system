<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//LOG BEFORE LOGOUT
include 'activity_logs.php'; //connected on activity_logs.php
logActivity("Logged-out");

// Unset all session variables
session_unset();

//DESTROY THE SESSION
session_destroy();

//BACK TO LOGIN AFTER LOGUT
header('Location: login.php');
exit();