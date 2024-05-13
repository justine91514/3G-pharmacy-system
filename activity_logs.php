<?php

function logActivity($activityDescription)
{
    $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
    if ($connection === false) {
        echo "Failed to connect to the database.";
        return;
    }

    //GET CURRENT USER
    $first_name = $_SESSION['first_name'];
    $mid_name = $_SESSION['mid_name'];
    $last_name = $_SESSION['last_name'];
    $loggedUser = $first_name . ' ' . $mid_name . ' ' . $last_name;
    //GET CURRENT USER USERTYPE
    $loggedUser_UserType = $_SESSION['usertype'];

    //INSERT DATA
    try {
        $stmt = $connection->prepare("INSERT INTO activity_logs (user, user_type, activity_description) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $loggedUser, $loggedUser_UserType, $activityDescription);

        if ($stmt->execute()) {
            // echo "Activity logged successfully";
        } else {
            echo "Error executing query";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    $stmt->close();
    $connection->close();
}