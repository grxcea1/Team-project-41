<?php
session_start();
require_once("ffdbConn.php"); 

if (!isset($_SESSION["aid"]) && !isset($_SESSION["Email"])) {
    $_SESSION["noadminaccount"] = "You must be an Admin to interact with the inventory management.";
    header("Location: home.php");
    exit;
}

if (isset($_POST['action']) && isset($_POST['orderID']) && isset($_POST['pid']) && isset($_POST['quantity'])) {
    $orderID = $_POST['orderID'];
    $pid = $_POST['pid'];
    $quantity = $_POST['quantity'];

    try {
        $newStatus = "Recieved";
        $stmt = $pdo->prepare("UPDATE orderdetails SET returnStatus = ? WHERE orderID = ? AND pid = ?");
        $stmt->execute([$newStatus, $orderID, $pid]);

        $stmt = $pdo->prepare("UPDATE product SET p_Stock = p_Stock + ? WHERE pid = ?");
        $stmt->execute([$quantity, $pid]);
            
        $_SESSION["recieved"] = "Return has been marked as recieved!";
        header("Location: returns.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION["failedReturn"] = "Failed to mark the return as recieved, please try again later.";
        header("Location: returns.php");
        exit;
    }
}

try {
    $query = "SELECT orders.orderID, orderdetails.pid, product.p_Name, orderdetails.price, orderdetails.quantity, 
                     customer.first_name, customer.last_name, orderdetails.returnStatus 
              FROM orders
              JOIN customer ON orders.uid = customer.uid
              JOIN orderdetails ON orders.orderID = orderdetails.orderID
              JOIN product ON product.pid = orderdetails.pid
              WHERE orderdetails.returnStatus = 'Returning'"; 

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $returns = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $_SESSION["failed_system"] = "Failed to connect to the database, please try again later.";
    header("Location: returns.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return List</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="home.css">
    <script src="sscript.js"></script>
    <link rel="shortcut icon" href="fav">
</head>
<body>
<?php
require_once("ffdbConn.php");
if (isset($_SESSION['recieved'])) {
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
                " . $_SESSION['recieved'] . "
            </div>
          </div>";
    unset($_SESSION['recieved']);
}

if (isset($_SESSION['failedReturn'])) {
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
                " . $_SESSION["failedReturn"] . "
            </div>
          </div>";
    unset($_SESSION['failedReturn']);
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
            <div class="logo-name">Film Fuse - Returns</div>
        </div>
    </header>

    <div class="sidebox">
     <nav class="nav-bar">
        <a href="home.php">Home</a>
        <a href="adminPage.php">Inventory</a>
        <a href="orders.php">Orders</a>
        <a href="customerDetails.php">Customer Management</a>
        <a href="add_Product.php">Add Products</a>
        <a href="password.php">Password</a>
     </nav>

    </div>

    <div class="container mt-5 section">
        <h2 class="mb-4">Return List</h2>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Movie Name</th>
                    <th>Movie Price</th>
                    <th>Quantity</th>
                    <th>Customer First Name</th>
                    <th>Customer Last Name</th>
                    <th>Return Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($returns as $return): ?>
                <tr>
                    <td><?= htmlspecialchars($return['orderID']) ?></td>
                    <td><?= htmlspecialchars($return['p_Name']) ?></td>
                    <td><?= htmlspecialchars($return['price']) ?></td>
                    <td><?= htmlspecialchars($return['quantity']) ?></td>
                    <td><?= htmlspecialchars($return['first_name']) ?></td>
                    <td><?= htmlspecialchars($return['last_name']) ?></td>
                    <td><?= htmlspecialchars($return['returnStatus']) ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="orderID" value="<?= $return['orderID'] ?>">
                            <input type="hidden" name="pid" value="<?= $return['pid'] ?>">
                            <input type="hidden" name="quantity" value="<?= $return['quantity'] ?>">
                            <button type="submit" name="action" value="recieved" class="btn btn-success">Recieved</button>
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
