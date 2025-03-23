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
session_start();
require_once("ffdbConn.php");
if (isset($_SESSION['adminlogin'])) {
    echo "<div style='display: flex; 
            justify-content: center; 
            align-items: center;'>
            <div style='background-color: green; 
            padding: 15px 30px; 
            color: white; 
            border: 1px solid green; 
            margin: 20px 0; 
            font-weight: bold; 
            border-radius: 5px; 
            text-align: center;'>
                " . $_SESSION['adminlogin'] . "
            </div>
          </div>";
    unset($_SESSION['adminlogin']);
}

if (isset($_SESSION['failedadminlogin1'])) {
    echo "<div style='display: flex; 
            justify-content: center; 
            align-items: center;'>
            <div style='background-color: red; 
            padding: 15px 30px; 
            color: white; 
            border: 1px solid red; 
            margin: 20px 0; 
            font-weight: bold; 
            border-radius: 5px; 
            text-align: center;'>
                " . $_SESSION['failedadminlogin1'] . "
            </div>
          </div>";
    unset($_SESSION['failedadminlogin1']);
}

if (isset($_SESSION['failedadminlogin2'])) {
    echo "<div style='display: flex; 
            justify-content: center; 
            align-items: center;'>
            <div style='background-color: red; 
            padding: 15px 30px; 
            color: white; 
            border: 1px solid red; 
            margin: 20px 0; 
            font-weight: bold; 
            border-radius: 5px; 
            text-align: center;'>
                " . $_SESSION['failedadminlogin2'] . "
            </div>
          </div>";
    unset($_SESSION['failedadminlogin2']);
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
            </section>

        <!-- Login Form -->
    
            <div class="form-value">
                <!-- Form submits to PHP script -->
                <form method="post" action="adminProcess.php">
                    <h2>Admin-Login</h2>

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
                        <p>Not an Adminintrator?<a href="ffLoginPage.php">Customer</a></p>
                        <a href="home.php">Home</a></p>
                    </div>
                </form>
            </div>
        
   
    <!-- Ionicons Scripts -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>