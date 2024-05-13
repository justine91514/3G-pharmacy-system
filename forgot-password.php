
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url(img/pharmacy2.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh; /* Adjusted to 100vh */
            margin: 0;
            overflow: hidden;
            /* Apply blur effect */
            -webkit-backdrop-filter: blur(10px); /* Safari */
            backdrop-filter: blur(5px); /* Standard */
        }

        .login-container {
            position: relative; /* Added for positioning progress bar */
            background: #FFF;
            border-radius: 20px;
            box-shadow: 0px 4px 50px 0px rgba(0, 0, 0, 0.25);
            padding: 25px;
            max-width: 420px;
            /* Add padding at the bottom for progress bar */
            padding-bottom: 30px;
        }

        .brand-text {
            font-size: 2em;
        }

        .slogan-text {
            margin-top: 20px;
            color: #FFF;
            text-align: center;
            font-family: Tapestry;
            font-size: 35px;
            font-style: normal;
            font-weight: 400;
            line-height: 35px;
        }

        .btn-primary {
            width: 50%;
            border-radius: 15px;
            box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
            display: block;
            margin: 0 auto; /* Set left and right margin to auto */
        }

        /* Change button color */
        .btn-primary {
            background-color: #0C96D4; /* Change the color code to your desired color */
            border-color: #0C96D4; /* Change the border color if needed */
        }

        /* Change button color on hover */
        .btn-primary:hover {
            background-color: #D9D9D9; /* Change the hover color code */
            border-color: #D9D9D9; /* Change the hover border color if needed */
            color: #000000;
        }

    </style>

</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-auto mt-auto">
                <div class="login-container" style="height: 500px;"> <!-- Adjusted height -->
                    <div class="text-center">
                        <img src="img/fingerprint.png" alt="Logo" class="mb-4" style="width: 100px; height: auto;">
                        <h1 class="h1 mb-4" style="color: black; font-weight: bold;">Forgot Password?</h1>
                        <p style="color: black;">No worries, we’ll send you reset instructions.</p>
                        <hr class="my-4">
                        <!-- Your login form here -->
                        <form onsubmit="return validateEmail();">
                            <!-- Username field with label outside -->
                            <div class="form-group text-left">
                                <label for="EMAIL" style="color: black;">Email</label>
                                <input type="email" class="form-control" id="username" placeholder="Please enter your email" required>
                            </div>
                            <button id="nextButton" class="btn btn-primary btn-block" type="submit">Next</button>
                            <p id="errorText" style="color: red;"></p> <!-- Error message -->
                            <!-- Your submit button goes here -->
                            <a href="login.php" class="ml-auto" style="color: darkgrey;">Back to login</a>
                        </form>
                        
                        <script>
                            function validateEmail() {
                                var emailInput = document.getElementById("username").value;
                                var errorText = document.getElementById("errorText");
                                var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                        
                                if (emailInput.trim() === "") {
                                    // If input is empty
                                    errorText.textContent = "Please enter your email.";
                                    return true;
                                } else if (!emailPattern.test(emailInput.trim())) {
                                    // If input doesn't match email pattern
                                    errorText.textContent = "Invalid email.";
                                    return true;
                                } else {
                                    // If input is a valid email
                                    errorText.textContent = ""; // Clear any previous error message
                                    window.location.href = "verification.php";
                                    return false; // To prevent form submission (since we're manually redirecting)
                                }
                            }
                        </script>
                        