<?php
session_start();
require_once("ffdbConn.php");

if (!isset($_SESSION["uid"]) && !isset($_SESSION["Email"])) {
    $_SESSION["accountupdate"] = "You must be logged in or registered to update your account.";
    header("Location: home.php");
    exit;
}

$uid = $_SESSION['uid'];

try {
    $customerQuery = "SELECT username, first_name, last_name, email FROM customer WHERE uid = ?";
    $stmt = $pdo->prepare($customerQuery);
    $stmt->bindParam(1, $uid, PDO::PARAM_INT);
    $stmt->execute();

    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$customer) {
        $_SESSION["aupdate1"] = "User not found or customer data couldn't be retrieved, please try again later.";
            header("Location: home.php");
            exit;
    }
} catch (PDOException $ex) {
    $_SESSION["aupdate1"] = "User not found or customer data couldn't be retrieved, please try again later.";
            header("Location: home.php");
            exit;
}
if (isset($_POST['update_account'])) {
    if (!isset($_POST['username'], $_POST['first_name'], $_POST['last_name'], $_POST['email'])) {
        $_SESSION["aupdate2"] = "Please fill in all fields.";
            header("Location: accountUpdate.php");
            exit;
    }

    try {
        $checkUsername = $pdo->prepare('SELECT username FROM customer WHERE username = ? AND uid != ?');
        $checkUsername->execute(array($_POST['username'], $uid));

        $checkEmail = $pdo->prepare('SELECT email FROM customer WHERE email = ? AND uid != ?');
        $checkEmail->execute(array($_POST['email'], $uid));

        if ($checkUsername->rowCount() > 0) {
            $_SESSION["aupdate3"] = "Username has already been taken, please try a different one.";
            header("Location: accountUpdate.php");
            exit;
            $_SESSION["aupdate4"] = "Email has already been taken, please try a different one.";
            header("Location: accountUpdate.php");
            exit;
        } else {
            $updateStat = $pdo->prepare('UPDATE customer SET username = ?, first_name = ?, last_name = ?, email = ? WHERE uid = ?');
            $updateStat->execute(array(
                $_POST['username'],
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $uid
            ));

            $_SESSION["aupdate5"] = "Successfully updated account details.";
            header("Location: account.php");
            exit;

        }
    } catch (PDOException $ex) {
        $_SESSION["aupdate6"] = "Could not connect to database, please try again later.";
            header("Location: home.php");
            exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Fuse - Update Account</title>
    <link rel="stylesheet" href="home.css">
    <link rel="shortcut icon" href="fav">
</head>
<body>
<?php
if (isset($_SESSION['aupdate2'])) {
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
                " . $_SESSION['aupdate2'] . "
            </div>
          </div>";
    unset($_SESSION['aupdate2']);
}

if (isset($_SESSION['aupdate3'])) {
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
                " . $_SESSION['aupdate3'] . "
            </div>
          </div>";
    unset($_SESSION['aupdate3']);
}

if (isset($_SESSION['aupdate4'])) {
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
                " . $_SESSION['aupdate4'] . "
            </div>
          </div>";
    unset($_SESSION['aupdate4']);
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

    <div class="sidebox">
        <nav class="nav-bar">
            <a href="home.php">Home</a>
            <a href="ffLoginPage.php">Login</a>
            <a href="aboutus.php">About Us</a>
            <a href="basket.php">Basket</a>
            <a href="account.php">Account</a>
            <a href="contact.php">Contact us</a>
        </nav>
    </div>

    <div class="container mt-5" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
        <h2>Update Account Information</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username" style="color: white;">Username:</label>
                <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($customer['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="first_name" style="color: white;">First Name:</label>
                <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($customer['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name" style="color: white;">Last Name:</label>
                <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($customer['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email" style="color: white;">Email:</label>
                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required>
            </div>
            <button type="submit" name="update_account" class="btn btn-primary">Update</button>
        </form>
    </div>

    <footer class="footer">
        <hr>
        <p>Join the Film Fuse community today, and let us bring the world of cinema to you.</p>
    </footer>
</body>
</html>
