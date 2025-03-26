<?php
session_start();
require_once ("ffdbConn.php");

if (!isset($_SESSION["aid"]) || !isset($_SESSION["Email"])) {
    $_SESSION["loginpassword"] = "You must be logged in as Admin to change your password.";
    header("Location: home.php");
    exit;
}

if (isset($_POST['change_password'])) {
    if (!isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $_SESSION["passwordupdatea1"] = "Please fill in all fields.";
        header("Location: adminPassword.php");
        exit;
    }

    if ($_POST['new_password'] !== $_POST['confirm_password']) {
        $_SESSION["passwordupdatea2"] = "New password does not match.";
        header("Location: adminPassword.php");
        exit;
    }

    try {
        $stat = $pdo->prepare('SELECT password FROM admin WHERE email = :email');
        $stat->bindParam(':email', $_SESSION['Email']);
        $stat->execute();

        if ($stat->rowCount() > 0) {
            $row = $stat->fetch();
            $storedPassword = $row['password'];
            $enteredPassword = $_POST['current_password'];

            if (preg_match('/^\$2y\$\d{2}\$/', $storedPassword)) {
                $passwordMatches = password_verify($enteredPassword, $storedPassword);
            } else {
                $passwordMatches = ($enteredPassword === $storedPassword);
            }

            if ($passwordMatches) {
                $newHashedPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $updateStat = $pdo->prepare('UPDATE admin SET password = :password WHERE email = :email');
                $updateStat->bindParam(':password', $newHashedPassword);
                $updateStat->bindParam(':email', $_SESSION['Email']);
                $updateStat->execute();

                $_SESSION["passwordupdatea3"] = "Password has been successfully changed.";
                header("Location: adminPassword.php");
                exit;
            } else {
                $_SESSION["passwordupdatea4"] = "The current password is incorrect, please try again.";
                header("Location: adminPassword.php");
                exit;
            }
        } else {
            $_SESSION["passwordupdatea5"] = "This user is not found.";
            header("Location: home.php");
            exit;
        }

    } catch (PDOException $ex) {
        $_SESSION["passwordupdatea6"] = "Database is currently down, please try again later.";
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
    if (isset($_SESSION['passwordupdatea1'])) {
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
                " . $_SESSION['passwordupdatea1'] . "
            </div>
          </div>";
    unset($_SESSION['passwordupdatea1']);
}

if (isset($_SESSION['passwordupdatea2'])) {
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
                " . $_SESSION['passwordupdatea2'] . "
            </div>
          </div>";
    unset($_SESSION['passwordupdatea2']);
}

if (isset($_SESSION['passwordupdatea3'])) {
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
                " . $_SESSION['passwordupdatea3'] . "
            </div>
          </div>";
    unset($_SESSION['passwordupdatea3']);
}

if (isset($_SESSION['passwordupdatea4'])) {
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
                " . $_SESSION['passwordupdatea4'] . "
            </div>
          </div>";
    unset($_SESSION['passwordupdatea4']);
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
                <a href="adminPage.php">Inventory</a>
                <a href="orders.php">Orders</a>
                <a href="returns.php">Returns</a>
                <a href="customerDetails.php">Customer Management</a>
                <a href="add_Product.php">Add Products</a>
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
        </footer>
    </body>
</html>
