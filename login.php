<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta property="og:type" content="website">
    <meta property="og:description" content="3G Pharmacy Capstone Project" />
    <meta property="og:image" content="https://i.imgur.com/J4i9dsq.png">
    <meta name="keywords" content="3G Pharmacy, Pharmacy, MIS">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="English">
    <link rel="icon" type="image/png" href="./img/3GMED.png">

    <title>3GMED | Login</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url(img/3gpharmaBG.jpg);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            /* Apply blur effect */
            -webkit-backdrop-filter: blur(10px);
            /* Safari */
            backdrop-filter: blur(5px);
            /* Standard */
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .login-container {
            background: #FFF;
            border-radius: 20px;
            box-shadow: 0px 4px 50px 0px rgba(0, 0, 0, 0.25);
            padding: 25px;
            max-width: 420px;
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
            margin: 0 auto;
            /* Set left and right margin to auto */
        }

        /* Change button color */
        .btn-primary {
            background-color: #0C96D4;
            /* Change the color code to your desired color */
            border-color: #0C96D4;
            /* Change the border color if needed */
        }

        /* Change button color on hover */
        .btn-primary:hover {
            background-color: #D9D9D9;
            /* Change the hover color code */
            border-color: #D9D9D9;
            /* Change the hover border color if needed */
            color: #000000;
        }

        /* Style for the eye icon */
        .input-group-text {
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-auto mt-auto">
            <div class="login-container">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <img src="img/3.Gmed.png" alt="Logo" class="mb-4" style="width: 200px; height: auto;">
                                <h1 class="h1 mb-4" style="color: black; font-weight: bold;">Welcome Back!</h1>
                                <p style="color: black;">Input credentials to access your account</p>
                                <hr class="my-4">
                                <?php
                                if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <strong>Error:</strong> ' . $_SESSION['status'] . '
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>';
                                    unset($_SESSION['status']);
                                }
                                ?>                             
                            </div>
                            <form class="user" action="logincode.php" method="POST">

                                <div class="form-group custom-width">
                                    <div class="input-group">
                                        <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                                    </div>
                                </div>

                                <div class="form-group custom-width">
                                    <div class="input-group">
                                        <input type="password" name="password" class="form-control" id="exampleInputPassword" placeholder="Password" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="togglePassword" onclick="togglePasswordVisibility()">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
<!--
                                <div class="form-group d-flex align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe" style="color: black;">Remember Me</label>
                                    </div>
                                    <a href="EMAIL-VERIFICATION.html" class="ml-auto" style="color: black;">Forgot Password?</a>
                                </div>
                            -->

                            <button type="submit" name="login_btn" class="btn btn-primary btn-user btn-block" style="font-size: 15px;">Log-In</button>
                                <!-- Add the necessary Font Awesome CDN for the eye icon -->
                                <script src="https://kit.fontawesome.com/your-font-awesome-kit-id.js" crossorigin="anonymous"></script>
                                <script>
                                    function togglePasswordVisibility()
                                    {
                                        var passwordInput = document.getElementById("exampleInputPassword");
                                        var eyeIcon = document.querySelector("#togglePassword i");

                                        var type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
                                        passwordInput.setAttribute("type", type);

                                        // Toggle eye icon based on password visibility
                                        eyeIcon.classList.toggle("fa-eye-slash", type === "password");
                                    }
                                </script>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<script>
    var passwordInput = document.getElementById("password");
    var togglePasswordButton = document.getElementById("togglePassword");

    togglePasswordButton.addEventListener("click", function () {
        var type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
        passwordInput.setAttribute("type", type);
