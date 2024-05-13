<?php
// Database connection parameters
$hostname = "localhost"; // Replace with your MySQL hostname
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "dbpharmacy"; // Replace with your MySQL database name

// Connect to MySQL server
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch table names
$tables = array();
$sql = "SHOW TABLES";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
}

// Close MySQL connection
$conn->close();

// Encode table names into JSON format
echo json_encode($tables);
?>
