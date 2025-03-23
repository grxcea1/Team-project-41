<?php
session_start();
require_once("ffdbConn.php");


if (!isset($_SESSION["uid"]) && !isset($_SESSION["Email"])) {
    $_SESSION["no_account"] = "You must be logged in or registered to view your account.";
    header("Location: ffLoginPage.php");
    exit;
}

$uid = $_SESSION['uid'];

if (isset($_POST['logout'])) {
    session_unset(); 
    session_destroy(); 
    header("Location: home.php");
    exit();
}

$customerQuery = "SELECT username, first_name, last_name, email FROM customer WHERE uid = ?";
$stmt = $pdo->prepare($customerQuery);
$stmt->bindParam(1, $uid, PDO::PARAM_INT);
$stmt->execute();
$customer = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

$orderQuery = "SELECT * 
               FROM orders
               WHERE uid = ?
               ORDER BY orderDate DESC";
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
    <link rel="stylesheet" href="account.css">
</head>
<body>

<?php
if (isset($_SESSION['aupdate5'])) {
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
                " . $_SESSION['aupdate5'] . "
            </div>
          </div>";
    unset($_SESSION['aupdate5']);
}
?>
<section>
         <!--link to js-->
    <script src="sscript.js"></script>

<!-- Toggle Button to Switch Backgrounds -->
  <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>
  <?php if (isset($_SESSION["uid"]) || isset($_SESSION["Email"])): ?>
    <form method="POST" style="float: right;">
        <button id="log-out" name="logout">Log Out</button>
    </form>
  <?php endif; ?>

  <!-- Light Mode and Dark Mode Images -->
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
            <div class="logo-name">Film Fuse</div>
        </div>
    </header>

    <div class="sidebox">
        <nav class="nav-bar">
            <a href="home.php">Home</a>
            <a href="ffLoginPage.php">Login</a>
            <a href="aboutus.php">About Us</a>
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
            <li><button onclick="window.location.href='password.php'">Change Password</button></li>
        </ul>
    </div>

    <div class="section">
        <h2>Order History</h2>
        <?php if (empty($orders)) {
            echo "No previous orders";
        }
        else {
        ?>
        <table style="margin: 0 auto; text-align: center; border-spacing: 20px 10px">
            <tr>
                <th style="padding: 5px 10px;">Order ID</th>
                <th style="padding: 5px 10px;">Order Amount</th>
                <th style="padding: 5px 10px;">Order Date</th>
                <th style="padding: 5px 10px;">Order Status</th>
            </tr>
            <?php foreach ($orders as $order) { ?>
            <table style="margin: 0 auto; text-align: center;">
            <tr>
                <td style="padding: 5px 10px;"><?php echo htmlspecialchars($order['orderID']); ?></td>
                <td style="padding: 5px 10px;"><?php echo htmlspecialchars($order['orderAmount']); ?></td>
                <td style="padding: 5px 10px;"><?php echo htmlspecialchars($order['orderDate']); ?></td>
                <td style="padding: 5px 10px;"><?php echo htmlspecialchars($order['orderStatus']); ?></td>
                <td style="padding: 5px 10px;"><button type="button" class="details" onclick="toggleDetails(<?= $order['orderID'] ?>)">Details</button></td>
            </tr>
            </table>
            <table id="movies-<?= $order['orderID'] ?>" class="movie-details" style="display: none">
                <?php
                $stmt = $pdo->prepare("SELECT * FROM orderdetails WHERE orderID = ?");
                $stmt->execute([$order['orderID']]);
                $movies = $stmt->fetchAll();

                foreach($movies as $movie) {
                    $stmt = $pdo->prepare("SELECT p_Name FROM product WHERE pid = ?");
                    $stmt->execute([$movie['pid']]);
                    $name = $stmt->fetch();
                    $wholeprice = ($movie['quantity']*$movie['price']);
                ?>
                        <tr id="movies-<?= $order['orderID'] ?>-<?= $movie['pid'] ?>">
                            <td><?=$name['p_Name']?></td>
                            <td>x<?=$movie['quantity']?></td>
                            <td>£<?=number_format($wholeprice,2)?></td>
                            <td>
                                <button class=movie-return onclick="returnMovie(<?= $order['orderID'] ?>,<?= $movie['pid']?>)">Return</button>
                            </td>
                        </tr>
                <?php
                }
                ?>
            </table>
            <?php }} ?>
        </table>
    </div>
    <script>
        function toggleDetails(orderID) {
            let moviesDiv = document.getElementById("movies-"+orderID);
            if (moviesDiv.style.display === "none") {
                moviesDiv.style.display = "block";
            }
            else {
                moviesDiv.style.display = "none";
            }
        }

        function returnMovie(orderID,pid) {
            let movieRow = document.getElementById("movies-"+orderID+"-"+pid);
            movieRow.remove();
        }
    </script>
</body>
</html>