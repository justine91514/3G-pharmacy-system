<!-- NEW LOGINCODE.php -->
<?php
include('security.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$connection = mysqli_connect("localhost", "root", "", "dbpharmacy");
include 'activity_logs.php';


if (isset($_POST['login_btn'])) {
    //----------------------------------------------------------------
    // SANITIZE INPUTS TO PREVENT INJECTION
    $qrcode_value = mysqli_real_escape_string($connection, $_POST['email']);
    $input_password = mysqli_real_escape_string($connection, $_POST['password']);

    //----------------------------------------------------------------
    // Prepare SQL statement to fetch user from database using prepared statements
    $sql = "SELECT * FROM register WHERE email=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $qrcode_value);
    $stmt->execute();
    $result = $stmt->get_result();

    //----------------------------------------------------------------
    // Check if the query returned any rows
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_all(MYSQLI_ASSOC);

        // Check if there are multiple accounts with the same email
        $matching_accounts = [];
        foreach ($user_data as $user) {
            if ($input_password === $user['password']) {
                $matching_accounts[] = $user;
            }
        }

        if (count($matching_accounts) == 1) {
            $user = $matching_accounts[0];

            //----------------------
            // LOGIN SUCCESS

            // GET CURRENT USER DATA FROM DATABASE
            $id = $user['id'];
            $email = $user['email'];
            $firstname = $user['first_name'];
            $middlename = $user['mid_name'];
            $lastname = $user['last_name'];
            $branch = $user['branch'];
            $usertype = $user['usertype'];

            // STORE DATA IN SESSION based on current user data
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['first_name'] = $firstname;
            $_SESSION['mid_name'] = $middlename;
            $_SESSION['last_name'] = $lastname;
            $_SESSION['branch'] = $branch;
            $_SESSION['usertype'] = $usertype;

            // Store user information in session
            $_SESSION['user_info'] = $user;

            /******************************
             * Define the greeting based on the current time
             ******************************/
            date_default_timezone_set('Asia/Manila');
            $currentTime = date('H');
            if ($currentTime < 12) {
                $greeting = "Good morning";
            } elseif ($currentTime < 18) {
                $greeting = "Good afternoon";
            } else {
                $greeting = "Good evening";
            }
            $_SESSION['success_toast'] = "$greeting, {$firstname}! Welcome to OUR SYSTEM!";

            /******************************
             * Redirect to user assign page based on usertype
             ******************************/
            if ($user['usertype'] == "admin") {
                logActivity("Logged-in");
                header('Location: dashboard.php');
                exit();
            } elseif ($user['usertype'] == "pharmacy_assistant") {
                logActivity("Logged-in");
                header('Location: pos.php');
                exit();
            }

        /******************************
        *   WRONG PASSWORD
        ******************************/
        } else {
            $_SESSION['status'] = "Incorrect Password. Please try again.";
            header("Location: login.php");
            exit();
        }

    /******************************
    *   EMAIL NOT FOUND
    ******************************/
    } else {
        $_SESSION['status'] = "Invalid. These credentials do not match our records.";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $connection->close();
}



// OLD LOGINCODE.PHP
// include('security.php');
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
// $connection = mysqli_connect("localhost", "root", "", "dbpharmacy");

// if (isset($_POST['login_btn'])) {
//     $email_login = $_POST['email'];
//     $password_login = $_POST['password'];

//     $query = "SELECT * FROM register WHERE email='$email_login' AND password='$password_login'";
    
//     $query_run = mysqli_query($connection, $query);
//     $user = mysqli_fetch_array($query_run);

//     if ($user) {
//         // Get the user's information from the database based on the email
//         $query_user_info = "SELECT * FROM register WHERE email='$email_login'";
//         $query_user_info_run = mysqli_query($connection, $query_user_info);
//         $user_info = mysqli_fetch_assoc($query_user_info_run);
        
//         // Store user information in session
//         $_SESSION['user_info'] = $user_info;
//         $_SESSION['user_id'] = $user_info['user_id']; 

//         $_SESSION['username'] = $email_login;
//         $_SESSION['usertype'] = $user['usertype']; // Set the usertype in the session
        
//         if ($user['usertype'] == "admin") {
//             header('Location: dashboard.php');
//         } elseif ($user['usertype'] == "pharmacy_assistant") {
//             header('Location: pos.php');
//         }
//     } else {
//         $_SESSION['status'] = 'Email or password is invalid';
//         header('Location: index.php');
//     }
// }
