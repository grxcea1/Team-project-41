<?php
if (!isset($_SESSION["faileddatabaseconn"])) {
    $_SESSION["databaseConn"];
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Movie info</title>
        <link rel="stylesheet" href="home.css">
        <link rel="stylesheet" href="movieinfo.css">
    </head>
    <body>
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
<div style="display: inline-block; padding-left: 330px; padding-top: 70px;"> 
    <h2> Our website is currently down for maintenance,<br> we are working as hard as we can to get it back up for you,<br> thank you for your patience.</h2>
</div>
</html>
        