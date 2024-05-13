<?php
// Include database connection
include('dbconfig.php'); // Adjust the path according to your file structure
include('activity_logs.php');
// Check if export form is submitted
if(isset($_POST['export_submit'])) {
    // Handle export based on selected type (specific_table or entire_database)
    if($_POST['export_type'] == 'specific_table') {
        // Call exportTable function with table name
        if(isset($_POST['table_name'])) {
            $tableName = $_POST['table_name'];
            exportTable($connection, $tableName);
            logActivity("Exported a Table");

        } else {
            echo "Error: Table name not provided.";
        }
    } else if ($_POST['export_type'] == 'entire_database') {
        // Call exportDatabase function
        exportDatabase($connection);
        logActivity("Exported a Database");
    }
}

// Function to export entire database
function exportDatabase($connection) {
    // Fetch all table names from the database
    $sql = "SHOW TABLES";
    $result = $connection->query($sql);

    if ($result) {
        // Initialize SQL data string
        $sqlData = '';

        // Loop through each table
        while ($row = $result->fetch_row()) {
            $tableName = $row[0];
            // Fetch data from the table
            $sql = "SELECT * FROM $tableName";
            $tableResult = $connection->query($sql);
            if ($tableResult) {
                // Generate SQL data for the table
                while ($tableRow = $tableResult->fetch_assoc()) {
                    $sqlData .= "INSERT INTO $tableName VALUES (";
                    foreach ($tableRow as $value) {
                        $sqlData .= "'$value',";
                    }
                    $sqlData = rtrim($sqlData, ',');
                    $sqlData .= ");\n";
                }
            }
        }

        // Set headers for download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="database_backup.sql"');

        // Output SQL data to the response
        echo $sqlData;
        exit(); // Prevent any other output
    } else {
        echo "Error fetching table names: " . $connection->error;
    }
}

// Function to export specific table
function exportTable($connection, $tableName) {
    // Fetch data from the specified table
    $sql = "SELECT * FROM $tableName";
    $result = $connection->query($sql);

    if ($result) {
        // Initialize SQL data string
        $sqlData = '';

        // Generate SQL data for the table
        while ($row = $result->fetch_assoc()) {
            $sqlData .= "INSERT INTO $tableName VALUES (";
            foreach ($row as $value) {
                $sqlData .= "'$value',";
            }
            $sqlData = rtrim($sqlData, ',');
            $sqlData .= ");\n";
        }

        // Set headers for download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $tableName . '_backup.sql"');

        // Output SQL data to the response
        echo $sqlData;
        exit(); // Prevent any other output
    } else {
        echo "Error fetching data from table: " . $tableName . " - " . $connection->error;
    }
}
?>
