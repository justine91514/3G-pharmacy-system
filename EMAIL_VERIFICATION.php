<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

// Enable error reporting during development
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email address from the POST data
    $email = $_POST["email"];

    // Check if the email address is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // If the email address is invalid, return an error
        http_response_code(400); // Bad Request
        echo "Invalid email";
        exit;
    }

    // Generate a random 4-digit verification code
    $verificationCode = sprintf("%04d", rand(0, 9999));

    // Instantiate PHPMailer
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // SMTP server address
        $mail->SMTPAuth   = true;
        $mail->Username   = 'artnathanielcondez12@gmail.com'; // SMTP account username
        $mail->Password   = 'artyyy121321';   // SMTP account password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('artnathanielcondez12@gmail.com', 'Art Nathaniel Condez');
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(false);
        $mail->Subject = 'Verification Code';
        $mail->Body    = 'Your verification code is: ' . $verificationCode;

        $mail->send();

        // Return the verification code to the client-side JavaScript
        echo $verificationCode;
    } catch (Exception $e) {
        // If there's an error sending the email, return an error
        http_response_code(500); // Internal Server Error
        echo "Error sending email: {$mail->ErrorInfo}";
    }
} else {
    // If the request method is not POST, return an error
    http_response_code(405); // Method Not Allowed
    echo "Method not allowed";
}
?>
