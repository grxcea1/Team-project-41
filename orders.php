<?php
session_start();
require_once("ffdbConn.php"); 

if (!isset($_SESSION["aid"]) && !isset($_SESSION["Email"])) {
    $_SESSION["noadminaccount"] = "You must be an Admin to interact with the inventory management.";
    header("Location: home.php");
    exit;
}

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
            $_SESSION["failedOrder"] = "Order has been declined!";
            header("Location: orders.php");
            exit;
        }
        $stmt = $pdo->prepare("UPDATE orders SET orderStatus = ? WHERE orderID = ?");
        $stmt->execute([$newStatus, $orderID]);

        $_SESSION["ordered"] = "Movie has been dispatched!";
        header("Location: orders.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION["failedOrder"] = "Failed to authorise order, please try again later.";
        header("Location: orders.php");
        exit;
    }
}

$filterSQL = "";
$params = [];

$minAmount = $_GET['min_order_amount'] ?? '';
$maxAmount = $_GET['max_order_amount'] ?? '';
$orderDate = $_GET['order_date'] ?? '';
$firstName = $_GET['first_name'] ?? '';
$lastName = $_GET['last_name'] ?? '';

if ($minAmount !== '') {
    $filterSQL .= " AND orders.orderAmount >= ?";
    $params[] = $minAmount;
}
if ($maxAmount !== '') {
    $filterSQL .= " AND orders.orderAmount <= ?";
    $params[] = $maxAmount;
}
if (!empty($orderDate)) {
    $filterSQL .= " AND orders.orderDate = ?";
    $params[] = $orderDate;
}
if (!empty($firstName)) {
    $filterSQL .= " AND customer.first_name LIKE ?";
    $params[] = "%$firstName%";
}
if (!empty($lastName)) {
    $filterSQL .= " AND customer.last_name LIKE ?";
    $params[] = "%$lastName%";
}

$filterConditions = "orders.orderStatus NOT IN ('Dispatched', 'Declined')" . $filterSQL;

try {
    $query = "SELECT orders.orderID, orders.orderAmount, orders.orderDate, orders.orderStatus, 
                     customer.first_name, customer.last_name 
              FROM orders
              JOIN customer ON orders.uid = customer.uid
              WHERE " . $filterConditions;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION["failed_system"] = "Failed to connect to the database, please try again later.";
    header("Location: orders.php");
    exit;
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
<?php
require_once("ffdbConn.php");
if (isset($_SESSION['ordered'])) {
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
                " . $_SESSION['ordered'] . "
            </div>
          </div>";
    unset($_SESSION['ordered']);
}

if (isset($_SESSION['failedOrder'])) {
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
                " . $_SESSION["failedOrder"] . "
            </div>
          </div>";
    unset($_SESSION['failedOrder']);
}

if (isset($_SESSION['failed_system'])) {
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
                " . $_SESSION['failed_system'] . "
            </div>
          </div>";
    unset($_SESSION['failed_system']);
}
?>
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
        <a href="home.php">Home</a>
        <a href="adminPage.php">Inventory</a>
        <a href="customerDetails.php">Customer Management</a>
        <a href="add_Product.php">Add Products</a>
        <a href="password.php">Password</a>
     </nav>

    </div>

    <div class="container mt-5 section">
        <h2 class="mb-4">Order List</h2>
        <form method="GET" class="mb-4">
        <div class="form-row">
            <div class="col-md-3 mb-2">
                <input type="text" name="first_name" class="form-control" placeholder="Customer First Name" value="<?= htmlspecialchars($firstName) ?>">
            </div>
            <div class="col-md-3 mb-2">
                <input type="text" name="last_name" class="form-control" placeholder="Customer Last Name" value="<?= htmlspecialchars($lastName) ?>">
            </div>
            <div class="col-md-2 mb-2">
                <input type="number" step="0.01" name="min_order_amount" class="form-control" placeholder="Min Amount" value="<?= htmlspecialchars($minAmount) ?>">
            </div>
            <div class="col-md-2 mb-2">
                <input type="number" step="0.01" name="max_order_amount" class="form-control" placeholder="Max Amount" value="<?= htmlspecialchars($maxAmount) ?>">
            </div>
            <div class="col-md-2 mb-2">
                <input type="date" name="order_date" class="form-control" value="<?= htmlspecialchars($orderDate) ?>">
            </div>
            <div class="col-md-2 mb-2">
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
    </form>
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
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No orders found.</td>
                    </tr>
                <?php else: ?>
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
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        <hr>
        <p>Join the Film Fuse community today, and let us bring the world of cinema to you.</p>
    </footer>
</body>
</html>