<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('includes/header_pos.php');
include('includes/navbar_pos.php');

// Database connection
include('dbconfig.php');

// Retrieve user ID from session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$user_name = "";
if ($user_info && !empty($user_info)) {
    $user_name = $user_info['first_name'] . ' ' . $user_info['mid_name'] . ' ' . $user_info['last_name'];
}

// Check if the form is submitted
if(isset($_POST['submit_time_in'])) {
    $time_in = isset($_POST['time_in']) ? $_POST['time_in'] : '';
    
    // Get the current date
    $date = date("Y-m-d");

    // Check if there's already an entry for the current date and the logged-in user
    $query_check = "SELECT * FROM user_log WHERE date = '$date' AND user_id = '$user_id'";

    $query_check_run = mysqli_query($connection, $query_check);
    if (mysqli_num_rows($query_check_run) == 0) {
        // Insert data into the database
        $query = "INSERT INTO user_log (user_id, date, pharma_name, time_in) VALUES ('$user_id', '$date', '$user_name', '$time_in')";
        $query_run = mysqli_query($connection, $query);
        if ($query_run) {
            echo '<script>alert("Time in recorded successfully!")</script>';
        } else {
            echo '<script>alert("Error: Unable to record time in.")</script>';
        }
    } else {
        echo '<script>alert("Entry for today already exists.")</script>';
    }
}

// Check if the form is submitted
if(isset($_POST['submit_time_out'])) {
    $time_out = isset($_POST['time_out']) ? $_POST['time_out'] : '';
    
    // Get the current date
    $date = date("Y-m-d");

    // Update time_out for the current date
    $query_update = "UPDATE user_log SET time_out = '$time_out' WHERE date = '$date' AND user_id = '$user_id'";
    $query_update_run = mysqli_query($connection, $query_update);
    if ($query_update_run) {
        echo '<script>alert("Time out recorded successfully!")</script>';
    } else {
        echo '<script>alert("Error: Unable to record time out.")</script>';
    }
}

$query = "SELECT user_id, date, time_in, time_out FROM user_log WHERE user_id = '$user_id'";
$query_run = mysqli_query($connection, $query);
?>


<div class="container-fluid">
    <div class="card shadow nb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Time In</h6>
        </div>
        <div class="card-body">
            <?php 
            if(mysqli_num_rows($query_run) == 0 || !isset($_POST['submit_time_in'])) { // Display the Time In form if no record exists for the current date or if Time In form was not submitted
            ?>
            <form method="post">
                <div class="form-group">
                    <label for="time_in">Time In:</label>
                    <input type="time" class="form-control" id="time_in" name="time_in">
                </div>
                <button type="submit" name="submit_time_in" class="btn btn-primary">Submit Time In</button>
            </form>
            <?php 
            } 
            ?>
        </div>
    </div>

    <?php if(mysqli_num_rows($query_run) > 0 && isset($_POST['submit_time_in'])) { ?>
    <div class="card shadow nb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Time Out</h6>
        </div>
        <div class="card-body">
            <?php 
            if(mysqli_num_rows($query_run) > 0 && isset($_POST['submit_time_in'])) { // Display the Time Out form only if Time In has been submitted and a record exists for the current date
            ?>
            <form method="post">
                <div class="form-group">
                    <label for="time_out">Time Out:</label>
                    <input type="time" class="form-control" id="time_out" name="time_out">
                </div>
                <button type="submit" name="submit_time_out" class="btn btn-primary">Submit Time Out</button>
            </form>
            <?php 
            } 
            ?>
        </div>
    </div>
    <?php } ?>

    <div class="card shadow nb-4 mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Time Log</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($query_run) > 0) {
                            while ($row = mysqli_fetch_assoc($query_run)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo $user_name; ?></td> <!-- Display the pharmacy assistant's name -->
                                    <td><?php echo date("h:i A", strtotime($row['time_in'])); ?></td>
                                    <td><?php echo date("h:i A", strtotime($row['time_out'])); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='4'>No record found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/pos_logout.php');
include('includes/scripts.php');
include('includes/footer.php');
?>

