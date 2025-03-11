<?php
session_start();

if (!isset($_SESSION["uid"])) {
    die("Error: User not logged in");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once("ffdbConn.php");
    
    $uid = $_SESSION["uid"];
    $totalPrice = $_POST['totalPrice'];
    $cartData = $_POST['cartData'];
    $cartArray = json_decode($cartData, true);

    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO orders (orderAmount, orderDate, uid) VALUES (?,?,?)");
    $stmt->execute([$totalPrice,date("Y-m-d"),$uid]);
    $orderID = $pdo->lastInsertId();

    $stmt = $pdo->prepare("Insert INTO orderdetails (orderID, pid, quantity, price) VALUES (?,?,?,?)");
    foreach ($cartArray as $key => $value) {
        if (is_array($value)) {
            $stmt->execute([$orderID,$key,$value['quantity'],$value['price']]);
        }
    }

    $pdo->commit();

    echo "Order successful!";
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
        <script src="sscript.js"></script>
        
        <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>

       
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
                <a href="aboutus.html">About Us</a>
                <a href="basket.php">Basket</a>
                <a href="account.html">Accounts</a>
                <a href="contact.html">Contact us</a>

                <div id="search-container">
                    <input type="text" id="search-bar" placeholder="Search...">
                    <button id="search-button">Go</button>
                </div>
            </nav>
        </div>
    </body>
</html>