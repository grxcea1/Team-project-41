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

$addressQuery = "SELECT addressLine1, city, postCode, country 
                 FROM address 
                 WHERE uid = ? 
                 LIMIT 1";
$stmt = $pdo->prepare($addressQuery);
$stmt->bindParam(1, $uid, PDO::PARAM_INT);
$stmt->execute();
$address = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt->closeCursor();

if (isset($_POST['return']) && isset($_POST['orderID']) && isset($_POST['pid'])) {
    $stmt = $pdo->prepare("UPDATE orderdetails SET returnStatus = ? WHERE orderID = ? AND pid = ?");
    $stmt->execute(['Returning',$_POST['orderID'],$_POST['pid']]);
    $_SESSION['returnStart'] = "You have successfully started a return! Please deliver to the address: Aston University, Birmingham, B4 7ET.";
}



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

if (isset($_SESSION['returnStart'])) {
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
                " . $_SESSION['returnStart'] . "
            </div>
          </div>";
    unset($_SESSION['returnStart']);
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

    <div class="section" style="display: flex; justify-content: space-between; align-items: flex-start;">
  <div style="width: 60%;">
    <h2>Order History</h2>
    <?php if (empty($orders)) {
      echo "No previous orders";
    } else { ?>
    <table style="width: 100%; border-spacing: 0 10px; text-align: center;">
      <tr>
        <th>Order ID</th>
        <th>Order Amount</th>
        <th>Order Date</th>
        <th>Order Status</th>
        <th>Actions</th>
      </tr>
      <?php foreach ($orders as $order): ?>
      <tr>
        <td><?= htmlspecialchars($order['orderID']) ?></td>
        <td><?= htmlspecialchars($order['orderAmount']) ?></td>
        <td><?= htmlspecialchars($order['orderDate']) ?></td>
        <td><?= htmlspecialchars($order['orderStatus']) ?></td>
        <td><button onclick="toggleDetails(<?= $order['orderID'] ?>)">Details</button></td>
      </tr>
      <tr>
        <td colspan="5">
          <table id="movies-<?= $order['orderID'] ?>" class="movie-details" style="display: none; margin: 10px auto; width: 90%; border: 1px solid #ccc;">
            <tr>
                <th>Movie Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            
            <?php
              $stmt = $pdo->prepare("SELECT * FROM orderdetails WHERE orderID = ?");
              $stmt->execute([$order['orderID']]);
              $movies = $stmt->fetchAll();

              foreach ($movies as $movie) {
                $stmt2 = $pdo->prepare("SELECT p_Name FROM product WHERE pid = ?");
                $stmt2->execute([$movie['pid']]);
                $name = $stmt2->fetch();
                $wholeprice = $movie['quantity'] * $movie['price'];
            ?>
            <tr id="movies-<?= $order['orderID'] ?>-<?= $movie['pid'] ?>">
              <td><?= htmlspecialchars($name['p_Name']) ?></td>
              <td><?= $movie['quantity'] ?></td>
              <td>£<?= number_format($wholeprice, 2) ?></td>
              <td>
                <?php
                    if ($order['orderStatus'] == 'Delivered' && is_null($movie['returnStatus'])) {
                ?>
                    <form method="POST">
                        <input type="hidden" name="orderID" value="<?= $order['orderID'] ?>">
                        <input type="hidden" name="pid" value="<?= $movie['pid'] ?>">
                        <button type="submit" name="return" value="return" class="movie-return">Return</button>
                    </form>
                <?php
                    } elseif ($order['orderStatus'] == 'Delivered' && $movie['returnStatus'] == 'Returning') {
                        echo "<button style='background-color: #555555;'>Returning</button>";
                    } elseif ($order['orderStatus'] == 'Delivered' && $movie['returnStatus'] == 'Recieved') {
                        echo "<button style='background-color: #555555;'>Returned</button>";        
                    } else {
                ?>
                        <button class="movie-return" onclick="alert('Cannot return, order has not been delivered yet!')">Return</button>
                <?php
                    }
                ?>
              </td>
            </tr>
            <?php } ?>
          </table>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php } ?>
  </div>

  <div style="width: 35%; padding-left: 20px;">
    <h2>Delivery Address</h2>
    <?php if ($address): ?>
    <ul>
      <li><strong>Address:</strong> <?= htmlspecialchars($address['addressLine1']) ?></li>
      <li><strong>City:</strong> <?= htmlspecialchars($address['city']) ?></li>
      <li><strong>Postcode:</strong> <?= htmlspecialchars($address['postCode']) ?></li>
      <li><strong>Country:</strong> <?= htmlspecialchars($address['country']) ?></li>
      <li><strong>Please call: 020 2345 67890;
    <li><strong> if you would like your delivery address changed 
    </ul>
    <?php else: ?>
    <p>No address found for this user.</p>
    <?php endif; ?>
  </div>
</div>

<script>
function toggleDetails(orderID) {
  const moviesDiv = document.getElementById("movies-" + orderID);
  if (moviesDiv.style.display === "none") {
    moviesDiv.style.display = "table";
  } else {
    moviesDiv.style.display = "none";
  }
}
</script>
