<?php
session_start();
require_once("ffdbConn.php"); 
$email = $_POST['email'] ?? '';
$plainPassword = $_POST['password'] ?? '';
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

try {
    $stat = $pdo->prepare("SELECT aid FROM admin WHERE email = ?");
    $stat->execute([$email]);

    if ($stat->rowCount() > 0) {
        $admin = $stat->fetch();

        $updateStmt = $pdo->prepare("UPDATE admin SET password = ? WHERE email = ?");
        $updateStmt->execute([$hashedPassword, $email]);
        $_SESSION["aid"] = $admin['aid'];
        $_SESSION["Email"] = $email;

        $_SESSION["adminlogin"] = "Welcome back!";
            header("Location: adminPage.php");
            exit;
    } else {
        $_SESSION["failedadminlogin1"] = "Email is not recognised as an Administrator";
            header("Location: ffLoginPageAdministrator.php");
            exit;
        
    }

} catch (PDOException $ex) {
    $_SESSION["failedadminlogin2"] = "Couldn't connect to databse.";
        header("Location: ffLoginPageAdministrator.php");
        exit;
}
?>