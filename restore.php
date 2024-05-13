<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back-up and Restore</title>
    <!-- Add your CSS styles and DataTables CSS CDN link here -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="need.css">
    <style>
        .container-fluid {
            margin-top: 80px;
        }
        .card {
            margin-top: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #259E9E;
            color: #259E9E;
            padding: 15px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-body {
            padding: 20px;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .form-section h2 {
            margin-bottom: 10px;
            color: #259E9E;
        }
        .form-section form {
            display: flex;
            align-items: center;
        }
        .form-section input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        .form-section button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #259E9E;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-section button:hover {
            background-color: #207a7a;
        }
    </style>
</head>
<body>
<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    include('includes/header.php');
    include('includes/navbar2.php');
    include('activity_logs.php');
    ?>

    <div class="container-fluid">
    <!-- DataTables Example -->
    <div class="card shadow nb-4">
        <div class="card-header py-3">
        <h1 style="color: #212529;">Back-up and Restore</h1>
        </div>
    <div class="card-body">
            <h2>Exporting databases from the current server.</h2>
            <i><small>You may also customize your export options.</small></i>
            <div style="margin-top: 10px;"></div>
             <form action="export.php" method="post"> <!-- Changed action to export.php -->
                <label for="export_type">Export Type:</label>
                <select name="export_type" id="export_type" class="form-control">
                    <option value="specific_table">Specific Table</option>
                    <option value="entire_database">Entire Database</option>
                </select>

                <div id="table_select" style="display:none;">
                    <label for="table_name">Select Table:</label>
                    <select name="table_name" id="table_name" class="form-control">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <div style="margin-top: 10px;"></div>
                <button type="submit" name="export_submit" class="btn btn-primary" style="border-radius: 5px; padding: 10px 20px; background-color: #304B1B; border: none; box-shadow: none;">Export</button>
            </form>
        </div>



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

// Function to initiate download of entire database
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

        // Set headers for download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="database_backup.sql"');

        // Output SQL data to the response
        echo $sqlData;
        exit(); // Prevent any other output
    } else {
        echo "Error fetching table names: " . $conn->error;
    }
}

// Function to initiate download of specific table
function exportTable($conn, $tableName) {
    // Fetch data from the selected table
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

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
        echo "Error fetching data from table: " . $tableName . " - " . $conn->error;
    }
}
?>
    </div>

    


    <?php
        include 'logout_modal.php';
        ?>

        <?php
        include ('includes/scripts.php');
        include ('includes/footer.php');
        ?>

<script>
    document.getElementById('file').addEventListener('change', function() {
        var fileName = document.getElementById('file').files[0].name;
        document.getElementById('filename').innerText = 'Selected file: ' + fileName;
    });
</script>

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