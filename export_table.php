<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('includes/header.php');
include('includes/navbar2.php');

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

function exportTable($conn, $tableName) {
    // Fetch data from the selected table
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);
    
    // Generate SQL data
    $sqlData = '';
    while ($row = $result->fetch_assoc()) {
        $sqlData .= "INSERT INTO $tableName VALUES (";
        foreach ($row as $value) {
            $sqlData .= "'$value',";
        }
        $sqlData = rtrim($sqlData, ',');
        $sqlData .= ");\n";
    }

    // Save SQL data to a temporary file
    $filename = tempnam(sys_get_temp_dir(), 'sql');
    file_put_contents($filename, $sqlData);

    // Return the download link
    return '<a href="download.php?file=' . basename($filename) . '">Download SQL Backup</a>';
}

function exportDatabase($conn) {
    // Fetch all table names from the database
    $sql = "SHOW TABLES";
    $result = $conn->query($sql);

    if ($result) {
        // Initialize SQL data string
        $sqlData = '';

        // Loop through each table
        while ($row = $result->fetch_row()) {
            $tableName = $row[0];
            // Fetch data from the table
            $sql = "SELECT * FROM $tableName";
            $tableResult = $conn->query($sql);
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

        // Save SQL data to a temporary file
        $filename = tempnam(sys_get_temp_dir(), 'sql');
        file_put_contents($filename, $sqlData);

        // Return the download link
        return '<a href="download.php?file=' . basename($filename) . '">Download SQL Backup</a>';
    } else {
        return "Error fetching table names: " . $conn->error;
    }
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle export here
    if (isset($_POST["export_submit"])) {
        if ($_POST["export_type"] == "specific_table") {
            // Retrieve the selected table name
            $tableName = $_POST["table_name"];
            // Export the selected table and get the download link
            $downloadLink = exportTable($conn, $tableName);
            // Output the download link
            echo $downloadLink;
        } elseif ($_POST["export_type"] == "entire_database") {
            // Export the entire database and get the download link
            $downloadLink = exportDatabase($conn);
            // Output the download link
            echo $downloadLink;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <!-- Add your CSS styles and DataTables CSS CDN link here -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="need.css">
    <style>
        /* Your CSS styles here */
    </style>
</head>
<body>

<div class="container-fluid">
    <!-- DataTables Example -->
    <div class="card shadow nb-4">
        <div class="card-header py-3">
            <h1>Back-up and Restore</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Import Container -->
                <!-- Your existing import form goes here -->
                
                <!-- Export Container -->
                <div class="col-md-6">
                    <div class="card shadow nb-3">
                        <div class="card-header py-3">
                            <h1>Export Data</h1>
                        </div>
                        <div class="card-body">
                            <div class="form-section">
                                <form id="exportForm" method="post">
                                    <div class="form-group">
                                        <label for="export_type">Export Type:</label>
                                        <select name="export_type" id="export_type" class="form-control">
                                            <option value="specific_table">Specific Table</option>
                                            <option value="entire_database">Entire Database</option>
                                        </select>
                                    </div>
                                    <div id="table_select" class="form-group" style="display:none;">
                                        <label for="table_name">Select Table:</label>
                                        <select name="table_name" id="table_name" class="form-control">
                                            <!-- Options will be populated dynamically -->
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="export_format">Export Format:</label>
                                        <select name="export_format" id="export_format" class="form-control">
                                            <option value="sql">SQL</option>
                                            <option value="csv">CSV</option>
                                            <!-- Add more export formats here if needed -->
                                        </select>
                                    </div>
                                    <button type="submit" name="export_submit" class="btn btn-primary">Export</button>
                                    <div style="margin-top: 10px;"></div>
                                    <label for="drag-drop">You may also customize your export options.</label>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var exportTypeSelect = document.getElementById('export_type');
    var tableSelect = document.getElementById('table_select');

    // Function to toggle display of table select dropdown
    function toggleTableSelect() {
        if (exportTypeSelect.value === 'specific_table') {
            tableSelect.style.display = 'block';
            fetchTableNames(); // Fetch table names when specific table option is selected
        } else {
            tableSelect.style.display = 'none';
        }
    }

    // Initial call to toggleTableSelect to set initial visibility
    toggleTableSelect();

    // Event listener for change event on export type select
    exportTypeSelect.addEventListener('change', function() {
        toggleTableSelect();
    });

    // Function to fetch table names from server and populate dropdown
    function fetchTableNames() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var tables = JSON.parse(xhr.responseText);
                    var tableSelect = document.getElementById('table_name');
                    tableSelect.innerHTML = ''; // Clear existing options
                    tables.forEach(function(table) {
                        var option = document.createElement('option');
                        option.value = table;
                        option.textContent = table;
                        tableSelect.appendChild(option);
                    });
                } else {
                    console.error('Error fetching table names:', xhr.status, xhr.statusText);
                }
            }
        };
        xhr.open('GET', 'get_table_names.php', true);
        xhr.send();
    }
});
</script>

</body>
</html>
