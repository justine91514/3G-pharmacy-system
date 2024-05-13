<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single Page Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        nav {
            background-color: #444;
            padding: 10px;
            text-align: center;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 10px;
        }
        section {
            padding: 20px;
            text-align: center;
        }
        footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <h1>Single Page Website</h1>
</header>

<nav>
    <a href="#home">Home</a>
    <a href="#about">About</a>
    <a href="#contact">Contact</a>
</nav>

<section id="home">
    <h2>Home</h2>
    <p>Welcome to our single page website. This is the home section.</p>
</section>

<section id="about">
    <h2>About</h2>
    <p>This is the about section. We are a fictional company providing PHP web development services.</p>
</section>

<section id="contact">
    <h2>Contact</h2>
    <p>Contact us via email at contact@example.com or give us a call at +123456789.</p>
</section>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Single Page Website. All rights reserved.</p>
</footer>

</body>
</html>
