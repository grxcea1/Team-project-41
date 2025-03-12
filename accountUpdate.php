<?php
session_start();
require_once("ffdbConn.php");

if (!isset($_SESSION['uid'])) {
    exit('User not logged in');
}

$uid = $_SESSION['uid'];

try {
    $customerQuery = "SELECT username, first_name, last_name, email FROM customer WHERE uid = ?";
    $stmt = $pdo->prepare($customerQuery);
    $stmt->bindParam(1, $uid, PDO::PARAM_INT);
    $stmt->execute();

    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$customer) {
        exit('User data not found.');
    }
} catch (PDOException $ex) {
    echo 'Failed to retrieve customer data.<br>';
    echo $ex->getMessage();
}
if (isset($_POST['update_account'])) {
    if (!isset($_POST['username'], $_POST['first_name'], $_POST['last_name'], $_POST['email'])) {
        exit('<p style="color:red">Please fill in all fields.</p>');
    }

    try {
        $checkUsername = $pdo->prepare('SELECT username FROM customer WHERE username = ? AND uid != ?');
        $checkUsername->execute(array($_POST['username'], $uid));

        $checkEmail = $pdo->prepare('SELECT email FROM customer WHERE email = ? AND uid != ?');
        $checkEmail->execute(array($_POST['email'], $uid));

        if ($checkUsername->rowCount() > 0) {
            echo '<script>alert("The username is already taken. Please choose another one.")</script>';
        } elseif ($checkEmail->rowCount() > 0) {
            echo '<script>alert("The email is already taken. Please choose another one.")</script>';
        } else {
            $updateStat = $pdo->prepare('UPDATE customer SET username = ?, first_name = ?, last_name = ?, email = ? WHERE uid = ?');
            $updateStat->execute(array(
                $_POST['username'],
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $uid
            ));

            echo '<script>window.location.href = "account.php";</script>';
            exit;

        }
    } catch (PDOException $ex) {
        echo 'Failed to connect to the database.<br>';
        echo $ex->getMessage();
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
</head>
<body>
    <video class="video-background" autoplay loop muted>
        <source src="images/image4.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

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
            <a href="home.html">Home</a>
            <a href="ffLoginPage.php">Login</a>
            <a href="aboutus.html">About Us</a>
            <a href="basket.html">Basket</a>
            <a href="account.html">Account</a>
            <a href="contact.html">Contact us</a>
        </nav>
    </div>

    <div class="container mt-5">
        <h2>Update Account Information</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($customer['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($customer['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($customer['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
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
