<?php
session_start();

if (isset($_POST['login'])) {
    if (!isset($_POST['email'], $_POST['password'])) {
        exit('Please fill both the email and password fields!');
    }

    require_once("ffdbConn.php");

    try {
        $stat = $pdo->prepare('SELECT aid, password FROM admin WHERE email = ?');
        $stat->execute([$_POST['email']]);

        if ($stat->rowCount() > 0) {
            $row = $stat->fetch();
            $enteredPassword = $_POST['password'];
            $storedPassword = $row['password'];

            $passwordMatches = false;
            if (preg_match('/^\$2y\$\d{2}\$/', $storedPassword)) {
                $passwordMatches = password_verify($enteredPassword, $storedPassword);
            } else {
                $passwordMatches = ($enteredPassword === $storedPassword);
            }

            if ($passwordMatches) {
                $_SESSION["aid"] = $row['aid'];
                $_SESSION["Email"] = $_POST['email'];
                $_SESSION["adminlogin"] = "Welcome back!";
                header("Location: adminPage.php");
                exit;
            } else {
                $_SESSION["failedadminlogin"] = "Incorrect password. Please try again.";
                header("Location: ffLoginPageAdministrator.php");
                exit;
            }

        } else {
            $_SESSION["failedadminlogin2"] = "Email not recognized. Please check or contact support.";
            header("Location: ffLoginPageAdministrator.php");
            exit;
        }

    } catch (PDOException $ex) {
        $_SESSION["systemFailure"] = "Failed to connect to the database.";
        header("Location: ffLoginPageAdministrator.php");
        exit;
    }
}
?>