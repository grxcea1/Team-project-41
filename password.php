<?php
session_start();
require_once ("ffdbConn.php");

if (!isset($_SESSION["uid"]) && !isset($_SESSION["Email"])) {
    $_SESSION["loginpassword"] = "You must be logged in or registered to change your password.";
    header("Location: home.php");
    exit;
}

if (isset($_POST['change_password'])) {
    if (!isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $_SESSION["passwordupdate1"] = "Please fill in all fields.";
            header("Location: password.php");
            exit;
    }
    
    if ($_POST['new_password'] !== $_POST['confirm_password']) {
        $_SESSION["passwordupdate2"] = "New password does not match.";
            header("Location: password.php");
            exit;
    }
    
    try {
        $stat = $pdo->prepare('SELECT password FROM customer WHERE email = ?');
        $stat->execute(array($_SESSION['Email']));
        
        if ($stat->rowCount() > 0) {
            $row = $stat->fetch();
            
            if (password_verify($_POST['current_password'], $row['password'])) {
                $new_hashed_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                
                $updateStat = $pdo->prepare('UPDATE customer SET password = ? WHERE email = ?');
                $updateStat->execute(array($new_hashed_password, $_SESSION['Email']));
                
                $_SESSION["passwordupdate3"] = "Password has been sucessfully changed.";
                    header("Location: home.php");
                    exit;
            } else {
                $_SESSION["passwordupdate4"] = "The current password is incorrect, please try again.";
                    header("Location: password.php");
                    exit;
            }
        } else {
            $_SESSION["passwordupdate5"] = "This user is not found.";
                header("Location: ffLoginPage.php");
                exit;
        }
    } catch (PDOException $ex) {
        $_SESSION["passwordupdate6"] = "Database is currently down, please try again later.";
            header("Location: home.php");
            exit;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
         <meta charset="UTF-8">
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Change Password</title>
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
         <link rel="stylesheet" href="home.css">
         <link rel="shortcut icon" href="fav">
         <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    </head>
    <body>
<?php
    if (isset($_SESSION['passwordupdate1'])) {
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
                " . $_SESSION['passwordupdate1'] . "
            </div>
          </div>";
    unset($_SESSION['passwordupdate1']);
}

if (isset($_SESSION['passwordupdate2'])) {
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
                " . $_SESSION['passwordupdate2'] . "
            </div>
          </div>";
    unset($_SESSION['passwordupdate2']);
}

if (isset($_SESSION['passwordupdate4'])) {
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
                " . $_SESSION['passwordupdate4'] . "
            </div>
          </div>";
    unset($_SESSION['passwordupdate4']);
}
?>
     <!--link to js-->
    <script src="sscript.js"></script>

 
    <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>

      <div id="image-container">
        <img id="light-image" src="images/light.jpg" alt="Light Mode Image" class="mode-image" style="display: none;">
        <img id="dark-image" src="images/dark.jpg"  alt="Dark Mode Image" class="mode-image">
    </div>

 
        <header>
            <div class="logo-container">
                <div class="circle">
                    <div class="text">
                        <span class="initials">FF</span>
                    </div>
                </div>
                <div class="logo-name">Film Fuse-Change Password</div>
            </div>
        </header>

        <div class="sidebox">
            <nav class="nav-bar">
                <a href="home.php">Home</a>
                <a href="ffLoginPage.php">Login</a>
                <a href="aboutus.php">About Us</a>
                <a href="basket.php">Basket</a>
                <a href="account.php">Accounts</a>
                <a href="contact.php">Contact us</a>
            </nav>
        </div>

        <div class="container mt-5" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <h2>Change Password</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="current_password" style="color: white;">Current Password:</label>
                    <input type="password" class="form-control" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password" style="color: white;">New Password:</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password" style="color: white;">Confirm New Password:</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password" class="btn btn-primary">Change</button>
                
            </form>
        </div>
        <footer class="footer">
            <hr>
            <p>Join the Film Fuse community today, and let us bring the world of cinema to you.</p>
        </footer>
    </body>
</html>
