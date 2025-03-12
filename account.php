<?php
session_start();

if (!isset($_SESSION['uid'])) {
    header("Location: ffLoginPage.php");
    exit;
}

$uid = $_SESSION['uid'];
require_once("ffdbConn.php");

$stmt = $pdo->prepare("SELECT * FROM orders WHERE uid = ? ORDER BY orderDate DESC");
$stmt->execute([$uid]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
    <head>
         <!-- Metadata for character set, compatibility, and viewport settings -->
         <meta charset="UTF-8">
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Home page</title>
         <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!--css link for this page -->
         <link rel="stylesheet" href="home.css">
         <!--fav Icon -->
         <link rel="shortcut icon" href="fav">
         <!--box icons-->
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
            <a href="home.html">Home</a>
            <a href="ffLoginPage.php">Login</a>
            <a href="basket.php">Basket<span id="insideCart">0</span></a>
            <a href="contact.html">Contact us</a>
            <a href="adminPage.php">admin Page</a>


       
            <div id="search-container">
                <input type="text" id="search-bar" placeholder="Search...">
                <button id="search-button">Go</button>
            </div>

        
    </div>
     
        <div class="section">
            <h2>Account Information</h2>
            <ul>
                <li><strong>Username:</strong>Batman123</li>
                <li><strong>Email:</strong> NOTbrucewyne@email.com</li>
                <li><strong>Membership:</strong> Premium </li>
                <li><strong>Account Created:</strong> January 1, 2020</li>
                <li><strong>Last Login:</strong> October 30, 2024</li>
                <li><strong>Active Devices:</strong> Laptop, Mobile Phone</li>
            </ul>
        </div>

        <div class="section">
            <h2>Previous Movie Orders</h2>
            <ul>
                <?php
                    if (empty($orders)) {
                        echo "No previous orders";
                    }
                    else {
                        foreach ($orders as $order) {
                            echo "<li>";
                            echo "<strong>Order ID:</strong> " . $order['orderID'] . "<br>";
                            echo "<strong>Order Date:</strong> " . $order['orderDate'] . "<br>";
                            echo "<strong>Total Price:</strong> " . $order['orderAmount'] . "<br>";

                            $stmt = $pdo->prepare("SELECT * FROM orderdetails WHERE orderID = ?");
                            $stmt->execute([$order['orderID']]);
                            $movies = $stmt->fetchAll();

                            foreach($movies as $movie) {
                                $stmt = $pdo->prepare("SELECT p_Name FROM product WHERE pid = ?");
                                $stmt->execute([$movie['pid']]);
                                $name = $stmt->fetch();
                                echo "<strong>Movie Name:</strong> " . $name['p_Name'] . "<br>";
                                echo "<strong>Quantity:</strong> " . $movie['quantity'] . "<br>";
                                $wholeprice = ($movie['quantity']*$movie['price']);
                                echo "<strong>Price:</strong> " . round($wholeprice,2) . "<br>";
                            }
                            
                            echo "<br></li>";
                        }
                    }
                ?>
            </ul>
        </div>

        <div class="section">
            <h2>Recently Watched Movies</h2>
            <ul>
                <li>
                    <strong>Movie Title:</strong> Blade Runner 2049<br>
                    <strong>Date Watched:</strong> October 28, 2024<br>
                    <strong>Rating:</strong> 4.5/5
                </li>
                <li>
                    <strong>Movie Title:</strong> Arrival<br>
                    <strong>Date Watched:</strong> October 22, 2024<br>
                    <strong>Rating:</strong> 4/5
                </li>
                <li>
                    <strong>Movie Title:</strong> Dune<br>
                    <strong>Date Watched:</strong> October 15, 2024<br>
                    <strong>Rating:</strong> 4.8/5
                </li>
            </ul>
        </div>

        <div class="section">
            <h2>Movies in Checkout Basket</h2>
            <ul>
                <li>
                    <strong>Movie Title:</strong> The Dark Knight<br>
                    <strong>Price:</strong> $11.99
                </li>
                <li>
                    <strong>Movie Title:</strong> Pulp Fiction<br>
                    <strong>Price:</strong> $9.99
                </li>
            </ul>
        </div>

        <div class="section">
            <h2>Other Account Details</h2>
            <ul>
                <li><strong>Favorite Genre:</strong> Science Fiction</li>
                <li><strong>Newsletter Subscription:</strong> Yes</li>
                <li><strong>Gift Card Balance:</strong> $50</li>
                <li><strong>Saved Payment Methods:</strong> Visa **** 1234, PayPal</li>
            </ul>
        </div>

        <div class="section">
            <h2>Profile Settings</h2>
            <button onclick="alert('Feature Coming Soon!')">Edit Profile</button>
            <button onclick="alert('Feature Coming Soon!')">Change Password</button>
        </div>
    </div>

    <footer class="footer">
        <hr>
        <P> 
        Join the Film Fuse community today, and let us bring the world of cinema to you.
        Whether you're planning a cozy night in or looking for your next movie obsession,
        we’re here to make it memorable.
        </P>
       </footer>
</body>
</html>