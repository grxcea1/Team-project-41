<?php
session_start();

if (isset($_POST['login'])) {
    if (!isset($_POST['email'], $_POST['password'])) {
        exit('Please fill both the email and password fields!');
    }
    
    require_once("ffdbConn.php");
    
    try {
        $stat = $pdo->prepare('SELECT uid, password FROM customer WHERE email = ?');
        $stat->execute(array($_POST['email']));
        
        if ($stat->rowCount() > 0) {
            $row = $stat->fetch();
            
            if (password_verify($_POST['password'], $row['password'])) {
                $_SESSION["uid"] = $row['uid'];  
                $_SESSION["Email"] = $_POST['email'];

                header("Location: home.php");
                exit();
            } else {
                echo "<p style='color:red'>Error logging in, password does not match</p>";
            }
        } else {
            echo "<p style='color:red'>Error logging in, email not found</p>";
        }
    } catch (PDOException $ex) {
        echo "Failed to connect to the database.<br>";
        echo $ex->getMessage();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Page</title>
    <link rel="stylesheet" href="ffRegistrationForm.css"> <!-- Linking CSS file -->
    <link rel="stylesheet" href="home.css">
</head>
<body>

<?php
require_once("ffdbConn.php");
if (isset($_SESSION['no_account'])) {
    echo "<div style='display: flex; 
            justify-content: center; 
            align-items: center;'>
            <div style='background-color: red; 
            padding: 15px 30px; 
            color: darkred; 
            border: 1px solid red; 
            margin: 20px 0; 
            font-weight: bold; 
            border-radius: 5px; 
            text-align: center;'>
                " . $_SESSION['no_account'] . "
            </div>
          </div>";
    unset($_SESSION['no_account']);
}

if (isset($_SESSION['no_account2'])) {
    echo "<div style='display: flex; 
            justify-content: center; 
            align-items: center;'>
            <div style='background-color: red; 
            padding: 15px 30px; 
            color: darkred; 
            border: 1px solid red; 
            margin: 20px 0; 
            font-weight: bold; 
            border-radius: 5px; 
            text-align: center;'>
                " . $_SESSION['no_account2'] . "
            </div>
          </div>";
    unset($_SESSION['no_account2']);
}
?>
    
    <section>
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
            </section>

        <!-- Login Form -->
    
            <div class="form-value">
                <!-- Form submits to PHP script -->
                <form method="post" action="ffLoginPage.php">
                    <h2>Login</h2>

                    <!-- Email Field -->
                    <div class="input-field">
                        <ion-icon name="mail-outline"></ion-icon>
                        <input class="input" type="text" name="email" required>
                        <label for="email">Email</label>
                    </div>

                    <!-- Password Field -->
                    <div class="input-field">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input class="input" type="password" name="password" required>
                        <label for="password">Password</label>
                    </div>

                    <!-- Remember Me Checkbox & Forgotten Password Link -->
                    <div class="forget">
                        <a href="password.php">Forgotten password?</a>
                        <br>
                    <!-- Submit Button -->
                    <button class="input-button" type="submit" name="login">Login</button>

                    <!-- Registration & Guest Links -->
                    <div class="register">
                        <p>Not Already Registered?<a href="ffRegistrationForm.php">Register here</a></p>
                        <a href="ffLoginPageAdministrator.php">Administrator?</a></p>
                    </div>
                </form>
            </div>
        
   
    <!-- Ionicons Scripts -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>



