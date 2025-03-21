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
            <a href="contact.html">Contact us</a>

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

<?php

require_once('ffdbConn.php');

if (isset($_POST['register'])){
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
        $stat = $pdo->prepare("INSERT INTO customer(username, first_name, last_name, password, email) VALUES (:username, :first_name, :last_name, :password, :email)");
        $stat->bindParam(':username', $username);
        $stat->bindParam(':first_name', $first_name);
        $stat->bindParam(':last_name', $last_name);
        $stat->bindParam(':password', $password);
        $stat->bindParam(':email', $email);
        
        $stat->execute();
        echo "Congratulations $username! You are now registered.";
        session_start();
        $_SESSION["username"] = $username;
        header("Location: ffLoginPage.php");
        exit();
    } catch (PDOException $ex) {
        echo "Sorry, a database error occurred!<br>";
        echo "Error details: <em>" . $ex->getMessage() . "</em>";
    }
}
?>