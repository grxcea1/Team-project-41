<?php
session_start();
require_once("ffdbConn.php"); 

if (!isset($_SESSION['uid'])) {
    die("User not logged in");
}

$uid = $_SESSION['uid'];

$customerQuery = "SELECT username, first_name, last_name, email FROM customer WHERE uid = ?";
$stmt = $pdo->prepare($customerQuery);
$stmt->bindParam(1, $uid, PDO::PARAM_INT);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$orderQuery = "SELECT o.orderID, o.orderAmount, o.orderDate, od.quantity, od.price 
               FROM orders o
               JOIN orderdetails od ON o.orderID = od.orderID
               WHERE o.uid = ?";
$stmt = $pdo->prepare($orderQuery);
$stmt->bindParam(1, $uid, PDO::PARAM_INT);
$stmt->execute();
$orders = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $orders[] = $row;
}
$stmt->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Fuse - Account Page</title>
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
            <a href="home.php">Home</a>
            <a href="ffLoginPage.php">Login</a>
            <a href="aboutus.html">About Us</a>
            <a href="basket.php">Basket</a>
        </nav>
    </div>
    
    <div class="section">
        <h2>Account Information</h2>
        <ul>
            <li><strong>Username:</strong> <?php echo htmlspecialchars($customer['username']); ?></li>
            <li><strong>First Name:</strong> <?php echo htmlspecialchars($customer['first_name']); ?></li>
            <li><strong>Last Name:</strong> <?php echo htmlspecialchars($customer['last_name']); ?></li>
            <li><strong>Email:</strong> <?php echo htmlspecialchars($customer['email']); ?></li>
            <li><button onclick="window.location.href='accountUpdate.php'">Edit</button></li>
        </ul>
    </div>

    <div class="section">
        <h2>Order History</h2>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Order Amount</th>
                <th>Order Date</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php foreach ($orders as $order) { ?>
            <tr>
                <td><?php echo htmlspecialchars($order['orderID']); ?></td>
                <td><?php echo htmlspecialchars($order['orderAmount']); ?></td>
                <td><?php echo htmlspecialchars($order['orderDate']); ?></td>
                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                <td><?php echo htmlspecialchars($order['price']); ?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>