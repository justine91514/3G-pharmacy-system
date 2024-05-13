<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to import database from SQL file
function importDatabase($conn, $file) {
    // Check if file exists
    if (!file_exists($file)) {
        echo "Error: File not found.";
        return;
    }
    
    // Read SQL file
    $sql = file_get_contents($file);

    // Check if SQL content is empty
    if (empty($sql)) {
        echo "Error: SQL file is empty.";
        return;
    }

    // Execute multi query
    if ($conn->multi_query($sql)) {
        echo "Database imported successfully from $file";
    } else {
        echo "Error importing database: " . $conn->error;
    }
}

// Database connection parameters
$hostname = "localhost"; // Replace with your MySQL hostname
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "dbpharmacy"; // Replace with your MySQL database name

// Create connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if(isset($_POST['import_submit'])) {
    // Check if file is uploaded
    if(isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['file']['tmp_name'];
        
        // Import database
        importDatabase($conn, $file);
    } else {
        echo "Please select a file to import.";
    }
}
?>
