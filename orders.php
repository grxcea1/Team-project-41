<?php
session_start();
require_once("ffdbConn.php"); 

if (isset($_POST['action']) && isset($_POST['orderID'])) {
    $orderID = $_POST['orderID'];
    
    try {
        if ($_POST['action'] === "authorise") {
            $newStatus = "Dispatched";
            $stmt = $pdo->prepare("SELECT pid, quantity FROM orderdetails WHERE orderID = ?");
            $stmt->execute([$orderID]);
            $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($orderDetails as $detail) {
                $stmt = $pdo->prepare("UPDATE product SET p_Stock = p_Stock - ? WHERE pid = ?");
                $stmt->execute([$detail['quantity'], $detail['pid']]);
            }
        } elseif ($_POST['action'] === "decline") {
            $newStatus = "Declined";
        }
        $stmt = $pdo->prepare("UPDATE orders SET orderStatus = ? WHERE orderID = ?");
        $stmt->execute([$newStatus, $orderID]);
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}

try {
    $query = "SELECT orders.orderID, orders.orderAmount, orders.orderDate, orders.orderStatus, 
                     customer.first_name, customer.last_name 
              FROM orders
              JOIN customer ON orders.uid = customer.uid
              WHERE orders.orderStatus NOT IN ('Dispatched', 'Declined')"; 

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="home.css">
    <script src="sscript.js"></script>
</head>
<body>
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
            <div class="logo-name">Film Fuse - Orders</div>
        </div>
    </header>

    <div class="sidebox">
        <nav class="nav-bar">
            <a href="home.html">Home</a>
            <a href="ffLoginPage.php">Login</a>
            <a href="aboutus.html">About Us</a>
            <a href="basket.html">Basket</a>
            <a href="account.html">Accounts</a>
            <a href="contact.html">Contact us</a>
        </nav>
    </div>

    <div class="container mt-5">
        <h2 class="mb-4">Order List</h2>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Order Amount</th>
                    <th>Order Date</th>
                    <th>Customer First Name</th>
                    <th>Customer Last Name</th>
                    <th>Order Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['orderID']) ?></td>
                    <td><?= htmlspecialchars($order['orderAmount']) ?></td>
                    <td><?= htmlspecialchars($order['orderDate']) ?></td>
                    <td><?= htmlspecialchars($order['first_name']) ?></td>
                    <td><?= htmlspecialchars($order['last_name']) ?></td>
                    <td><?= htmlspecialchars($order['orderStatus']) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="orderID" value="<?= $order['orderID'] ?>">
                            <button type="submit" name="action" value="authorise" class="btn btn-success">Authorise</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="orderID" value="<?= $order['orderID'] ?>">
                            <button type="submit" name="action" value="decline" class="btn btn-danger">Decline</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        <hr>
        <p>Join the Film Fuse community today, and let us bring the world of cinema to you.</p>
    </footer>
</body>
</html>
