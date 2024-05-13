
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
            height: 100vh;
            margin: 0;
            overflow: hidden;
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(5px);
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }

        .verification-container {
            text-align: center;
        }

        .verification-code {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .verification-code input {
            width: 70px;
            height: 70px;
            font-size: 29px;
            text-align: center;
            margin: 0 10px;
            border: 2px solid darkgrey;
            border-radius: 5px;
            outline: none;
        }

        .verification-code input.invalid {
            border-color: red; /* Change border color to red for invalid inputs */
        }

        .verification-code .error-message {
            color: red;
            margin-top: 5px;
            font-size: 14px;
            display: none; /* Initially hide error message */
        }

        .verification-button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .login-container {
            position: relative;
            background: #FFF;
            border-radius: 20px;
            box-shadow: 0px 4px 50px 0px rgba(0, 0, 0, 0.25);
            padding: 25px;
            max-width: 500px;
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
        }

        .btn-primary {
            background-color: #0C96D4;
            border-color: #0C96D4;
        }

        .btn-primary:hover {
            background-color: #D9D9D9;
            border-color: #D9D9D9;
            color: #000000;
        }
    </style>

</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-auto mt-auto">
                <div class="login-container" style="height: 500px;">
                    <div class="text-center">
                        <img src="img/mail.png" alt="Logo" class="mb-4" style="width: 80px; height: auto;">
                        <h1 class="h2 mb-4" style="color: black; font-weight: bold;">Email Verification</h1>
                        <p style="color: black;">Please enter the verification code sent to your email.</p>
                        <hr class="my-4">
                        <!-- Your login form here -->
                        <form id="verificationForm">
                            <div class="verification-container">
                                <div class="verification-code">
                                    <input type="text" maxlength="1" pattern="\d" oninput="validateInput(this)" required>
                                    <div class="error-message"></div> <!-- Error message for first input -->
                                    <input type="text" maxlength="1" pattern="\d" oninput="validateInput(this)" required>
                                    <div class="error-message"></div> <!-- Error message for second input -->
                                    <input type="text" maxlength="1" pattern="\d" oninput="validateInput(this)" required>
                                    <div class="error-message"></div> <!-- Error message for third input -->
                                    <input type="text" maxlength="1" pattern="\d" oninput="validateInput(this)" required>
                                    <div class="error-message"></div> <!-- Error message for fourth input -->
                                </div>
                                <a href="ENTER NEW PASSWORD.html" id="nextButton" class="btn btn-primary btn-block" onclick="return handleSubmit()">Next</a>
                                <p id="errorMessage" style="color: red; display: none;">Please enter a valid verification code.</p>
                            </div>
                            <p></p>
                            <a href="login.php" class="ml-auto" style="color:darkgray;">Back to login</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateInput(input) {
            // Clear previous error message
            var errorMessage = input.nextElementSibling;
            errorMessage.style.display = 'none';
            input.classList.remove('invalid');

            // Check if input value is not a digit
            if (!/^\d+$/.test(input.value)) {
                errorMessage.style.display = 'block';
                input.classList.add('invalid');
                input.value = ''; // Clear input if it's not a digit
            }
        }

        function checkInputs() {
            var inputs = document.querySelectorAll('.verification-code input');
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].value === '') {
                    return false;
                }
            }
            return true;
        }

        function handleSubmit() {
            var isValid = checkInputs();
            if (!isValid) {
                document.getElementById('errorMessage').style.display = 'block';
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
