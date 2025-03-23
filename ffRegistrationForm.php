<?php

require_once('ffdbConn.php');

if (isset($_POST['register'])) {
    $email = isset($_POST['email']) ? $_POST['email'] : false;
    $username = isset($_POST['username']) ? $_POST['username'] : false;
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : false;
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : false;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : false;

    if (!($email && preg_match('/^[^@]+@[^@]+\.[^@]+$/', $email)) || !$username || !$first_name || !$last_name || !$password) {
        echo "Invalid Email or missing fields!";
        exit;
    }

    try {
        $checkEmail = $pdo->prepare("SELECT * FROM customer WHERE email = ?");
        $checkEmail->execute([$email]);

        if ($checkEmail->rowCount() > 0) {
            session_start();
            $_SESSION["failedregistration2"] = "That email is already being used. Please use a different one or login.";
            header("Location: ffLoginPage.php");
            exit;
        }
      
        $stat = $pdo->prepare("INSERT INTO customer(username, first_name, last_name, password, email) 
                               VALUES (:username, :first_name, :last_name, :password, :email)");
        $stat->bindParam(':username', $username);
        $stat->bindParam(':first_name', $first_name);
        $stat->bindParam(':last_name', $last_name);
        $stat->bindParam(':password', $password);
        $stat->bindParam(':email', $email);

        $stat->execute();
        $_SESSION["username"] = "Congratulations $username! You are now registered, now please login with your registered details";
        header("Location: ffLoginPage.php");
        exit;

    } catch (PDOException $ex) {
        session_start();
        $_SESSION["failedregistration"] = "Sorry, your details couldn't be registered. Please try again later.";
        header("Location: ffRegistrationForm.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Page</title>
    <link rel="stylesheet" href="ffRegistrationForm.css">
    <link rel="stylesheet" href="home.css">
</head>
<body>
    
    <!--link to js-->
    <script src="sscript.js"></script>

    <!-- Toggle Button to Switch Backgrounds -->
    <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>

      <!-- Light Mode and Dark Mode Images -->
      <div id="image-container">
        <img id="light-image" src="images/light.jpg" alt="Light Mode Image" class="mode-image" style="display: none;">
        <img id="dark-image" src="images/dark.jpg"  alt="Dark Mode Image" class="mode-image">
    </div>

 
     <!-- LOGO BAR -->
     <header>
        <div class="logo-container">
            <div class="circle">
                <div class="text">
                    <span class="initials">FF</span>
                </div>
            </div>
            <div class="logo-name">Film Fuse</div>
        </div>
    </header>
     <!-- Navigation menu -->
     <div class="sidebox">
        <nav class="nav-bar">
            <a href="home.php">Home</a>
            <a href="ffLoginPage.php">Login</a>
            <a href="aboutus.php">About Us</a>
            <a href="contact.php">Contact us</a>

    </div>
    
    <section>
        <!-- Registration Form -->
        <div class="box">
            <div class="form-value">
                <form method="post" action="ffRegistrationForm.php">
                    <h2>Register</h2>

                    <!-- Email Field -->
                    <div class="input-field">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input class="input" type="email" name="email" required>
                        <label for="email">Email</label>
                    </div>

                    <!-- Username Field -->
                    <div class="input-field">
                        <ion-icon name="person-outline"></ion-icon>
                        <input class="input" type="text" name="username" required>
                        <label for="username">Username</label>
                    </div>

                    <!-- First Name Field -->
                    <div class="input-field">
                        <ion-icon name="person-outline"></ion-icon>
                        <input class="input" type="text" name="first_name" required>
                        <label for="first_name">First Name</label>
                    </div>

                    <!-- Last Name Field -->
                    <div class="input-field">
                        <ion-icon name="person-outline"></ion-icon>
                        <input class="input" type="text" name="last_name" required>
                        <label for="last_name">Last Name</label>
                    </div>

                    <!-- Password Field -->
                    <div class="input-field">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input class="input" type="password" name="password" required>
                        <label for="password">Password</label>
                    </div>

                    <!-- Submit Button -->
                    <button class="input-button" type="submit" name="register">Register</button>

                    <!-- Login Link -->
                    <div class="register">
                        <p>Already have an account? <a href="ffLoginPage.php">Login here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
</body>
</html>