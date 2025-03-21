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
                <a href="aboutus.html">About Us</a>
                <a href="basket.html">Basket</a>
                <a href="account.php">Accounts</a>
                <a href="contact.html">Contact us</a>
            </nav>
        </div>

        <div class="container mt-5">
            <h2>Change Password</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" class="form-control" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" class="form-control" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password" class="btn btn-primary">Change</button>
            </form>
        </div>

        <?php
        session_start();
        require_once ("ffdbConn.php");

        if (!isset($_SESSION['Email'])) {
            exit('Login to change your password.');
        }

        if (isset($_POST['change_password'])) {
            if (!isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
                exit('<p style="color:red">Please fill in all fields.</p>');
            }
            
            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                exit('<p style="color:red">New passwords do not match.</p>');
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
                        
                        echo '<p style="color:green">Password successfully changed.</p>';
                    } else {
                        echo '<p style="color:red">Current password is incorrect.</p>';
                    }
                } else {
                    echo '<p style="color:red">User not found.</p>';
                }
            } catch (PDOException $ex) {
                echo 'Failed to connect to the database.<br>';
                echo $ex->getMessage();
            }
        }
        ?>

        <footer class="footer">
            <hr>
            <p>Join the Film Fuse community today, and let us bring the world of cinema to you.</p>
        </footer>
    </body>
</html>
