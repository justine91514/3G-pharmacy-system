<?php
$server_name = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "dbpharmacy";

$connection = mysqli_connect($server_name, $db_username, $db_password);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$dbconfig = mysqli_select_db($connection, $db_name);

// Check if the database selection was successful
if (!$dbconfig) {
    die("Database selection failed: " . mysqli_error($connection));
}

return $connection; // Return the connection object
?>
