<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Page</title>
    <link rel="stylesheet" href="ffLoginPage.css"> <!-- Linking your CSS file -->
</head>
<body>
    
    <section>
        <!-- Background Video -->
        <video src="images/image 4.mp4" loop muted autoplay id="bg-video" type="video/mp4"></video>
        
        <!-- Login Form -->
        <div class="box">
            <div class="form-value">
                <!-- Form submits to your PHP script -->
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
                        <label><input type="checkbox"> Remember Me <a href="#">Forgotten password?</a></label>
                    </div>

                    <!-- Submit Button -->
                    <button class="input-button" type="submit" name="login">Login</button>

                    <!-- Registration & Guest Links -->
                    <div class="register">
                        <p>Not Already Registered? <a href="ffRegistrationForm.php">Register here</a></p>
                        <p>Or <a href="guestHome.php">Enter as Guest</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Ionicons Scripts -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</body>
</html>

<?php
if (isset($_POST['login'])){
    if ( !isset($_POST['email'], $_POST['password']) ) {
     exit('Please fill both the username and password fields!');
    }
    require_once ("ffdbConn.php");
    try {
        $stat = $pdo->prepare('SELECT password FROM customer WHERE email = ?');
        $stat->execute(array($_POST['email']));
        if ($stat->rowCount()>0){ 
            $row=$stat->fetch();
            if (password_verify($_POST['password'], $row['password'])){ 
                session_start();
                $_SESSION["Email"]=$_POST['email'];
                //header("Location:Home.php");
                echo "Logged in";
                exit();
            } else {
             echo "<p style='color:red'>Error logging in, password does not match </p>";
             }
        } else {
          echo "<p style='color:red'>Error logging in, Username not found </p>";
        } 
    }
    catch(PDOException $ex) {
        echo("Failed to connect to the database.<br>");
        echo($ex->getMessage());
        exit;
    }
}
?>


