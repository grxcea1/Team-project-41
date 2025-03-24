<?php
session_start();
require_once("ffdbConn.php");

if (!isset($_SESSION["aid"]) && !isset($_SESSION["Email"])) {
    $_SESSION["noadminaccount"] = "You must be an Admin to interact with the inventory management.";
    header("Location: home.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_customer'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $addressLine1 = $_POST['addressLine1'];
    $city = $_POST['city'];
    $postcode = $_POST['postcode'];
    $country = $_POST['country'];
    $m_Number = $_POST['m_Number'];

    try {
        $insertCustomer = $pdo->prepare("INSERT INTO customer (first_name, last_name, username, email) VALUES (?, ?, ?, ?)");
        $insertCustomer->execute([$first_name, $last_name, $username, $email]);

        $uid = $pdo->lastInsertId();
        $insertAddress = $pdo->prepare("INSERT INTO address (uid, addressLine1, city, postcode, country, m_Number) VALUES (?, ?, ?, ?, ?, ?)");
        $insertAddress->execute([$uid, $addressLine1, $city, $postcode, $country, $m_Number]);

        $_SESSION["success1"] = "Successfully added new customer!";
        header("Location: customerDetails.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION["failure1"] = "Failed to add new customer, Please fill in all fields or try again later.";
        header("Location: customerDetails.php");
        exit;
}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_customer'])) {
    $uid = $_POST['uid'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $addressLine1 = $_POST['addressLine1'];
    $city = $_POST['city'];
    $postcode = $_POST['postcode'];
    $country = $_POST['country'];
    $m_Number = $_POST['m_Number'];

    try {
        $updateCustomer = $pdo->prepare("UPDATE customer SET first_name = ?, last_name = ?, username = ?, email = ? WHERE uid = ?");
        $updateCustomer->execute([$first_name, $last_name, $username, $email, $uid]);

        $updateAddress = $pdo->prepare("UPDATE address SET addressLine1 = ?, city = ?, postcode = ?, country = ?, m_Number = ? WHERE uid = ?");
        $updateAddress->execute([$addressLine1, $city, $postcode, $country, $m_Number, $uid]);

        $_SESSION["success6"] = "Successfully updated customer details to the system!";
        header("Location: customerDetails.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION["failure8"] = "Failed to update customer, please fill in all fields or try again later.";
        header("Location: customerDetails.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_customer'])) {
    $uid = $_POST['uid'];

    try {
        $deleteAddress = $pdo->prepare("DELETE FROM address WHERE uid = ?");
        $deleteAddress->execute([$uid]);

        $deleteCustomer = $pdo->prepare("DELETE FROM customer WHERE uid = ?");
        $deleteCustomer->execute([$uid]);

        $_SESSION["success7"] = "Successfully deleted customer and address from the system!";
        header("Location: customerDetails.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION["failure9"] = "Failed to delete customer, please fill in all fields or try again later.";
        header("Location: customerDetails.php");
        exit;
    }
}

$searchEmail = $_GET['search_email'] ?? '';
$filterQuery = '';
$params = [];

if (!empty($searchEmail)) {
    $filterQuery = "WHERE c.email = ?";
    $params[] = $searchEmail;
}

$query = "
    SELECT c.uid, c.first_name, c.last_name, c.username, c.email,
           a.addressLine1, a.city, a.postcode, a.country, a.m_Number
    FROM customer c
    LEFT JOIN address a ON c.uid = a.uid
    $filterQuery
";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Customer Management</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="home.css">
        <link rel="stylesheet" href="styles.css">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    </head>
    <body>
    <?php
    if (isset($_SESSION['success1'])) {
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
                    " . $_SESSION['success1'] . "
                </div>
            </div>";
        unset($_SESSION['success1']);
    }

    if (isset($_SESSION['success6'])) {
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
                    " . $_SESSION['success6'] . "
                </div>
            </div>";
        unset($_SESSION['success6']);
    }

    if (isset($_SESSION['success7'])) {
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
                    " . $_SESSION['success7'] . "
                </div>
            </div>";
        unset($_SESSION['success7']);
    }

    if (isset($_SESSION['failure1'])) {
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
                    " . $_SESSION['failure1'] . "
                </div>
            </div>";
        unset($_SESSION['failure1']);
    }

    if (isset($_SESSION['failure8'])) {
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
                    " . $_SESSION['failure8'] . "
                </div>
            </div>";
        unset($_SESSION['failure8']);
    }

    if (isset($_SESSION['failure9'])) {
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
                    " . $_SESSION['failure9'] . "
                </div>
            </div>";
        unset($_SESSION['failure9']);
    }
    ?>
            <!--link to js-->
            <script src="sscript.js"></script>

        
    <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>

    <div id="image-container">
        <img id="light-image" src="images/light.jpg" alt="Light Mode Image" class="mode-image" style="display: none;">
        <img id="dark-image" src="images/dark.jpg"  alt="Dark Mode Image" class="mode-image">
    </div>

        <header> 
            <div class="logo-container">
                <a href="home.php" class="logo-link">
                    <div class="circle">
                        <div class="text">
                            <span class="initials">FF</span>
                        </div>
                    </div>
                    <div class="logo-name">Film Fuse - Customer Management</div>
                </a>
            </div>
        </header>

        <div class="sidebox">
            <nav class="nav-bar">
                <a href="home.php">Home</a> 
                <a href="adminPage.php">Inventory</a>
                <a href="orders.php">Orders</a>
                <a href="returns.php">Returns</a>
                <a href="add_Product.php">Add Products</a>
                <a href="password.php">Password</a>
            </nav>
        </div>
    <div class="container mt-5 section">
        <h2>Add New Customer</h2>
        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <input type="text" name="first_name" class="form-control" placeholder="First Name" required>
                </div>
                <div class="form-group col-md-4">
                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                </div>
                <div class="form-group col-md-4">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="addressLine1" class="form-control" placeholder="Address" required>
                </div>
                <div class="form-group col-md-3">
                    <input type="text" name="city" class="form-control" placeholder="City" required>
                </div>
                <div class="form-group col-md-3">
                    <input type="text" name="postcode" class="form-control" placeholder="Postcode" required>
                </div>
                <div class="form-group col-md-3">
                    <input type="text" name="country" class="form-control" placeholder="Country" required>
                </div>
                <div class="form-group col-md-3">
                    <input type="text" name="m_Number" class="form-control" placeholder="Phone Number" required>
                </div>
            </div>
            <button type="submit" name="add_customer" class="btn btn-primary">Add</button>
        </form>
    </div>


    <div class="container mt-5 section">
        <h2 class="mt-5">Manage Customers</h2>
        <form method="GET" class="mb-4">
            <input type="text" class="form-control" name="search_email" placeholder="Search by Email" value="<?= htmlspecialchars($searchEmail) ?>">
            <button type="submit" class="btn btn-primary mt-2">Search</button>
        </form>

        <table class="table table-dark table-bordered">
            <thead>
                <tr>
                    <th>UID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Postcode</th>
                    <th>Country</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($customers as $cust): ?>
                <tr>
                    <form method="POST" action="">
                        <td><?= htmlspecialchars($cust['uid']) ?></td>
                        <td><input type="text" name="first_name" value="<?= htmlspecialchars($cust['first_name']) ?>" class="form-control"></td>
                        <td><input type="text" name="last_name" value="<?= htmlspecialchars($cust['last_name']) ?>" class="form-control"></td>
                        <td><input type="text" name="username" value="<?= htmlspecialchars($cust['username']) ?>" class="form-control"></td>
                        <td><input type="email" name="email" value="<?= htmlspecialchars($cust['email']) ?>" class="form-control"></td>
                        <td><input type="text" name="addressLine1" value="<?= htmlspecialchars($cust['addressLine1']) ?>" class="form-control"></td>
                        <td><input type="text" name="city" value="<?= htmlspecialchars($cust['city']) ?>" class="form-control"></td>
                        <td><input type="text" name="postcode" value="<?= htmlspecialchars($cust['postcode']) ?>" class="form-control"></td>
                        <td><input type="text" name="country" value="<?= htmlspecialchars($cust['country']) ?>" class="form-control"></td>
                        <td><input type="text" name="m_Number" value="<?= htmlspecialchars($cust['m_Number']) ?>" class="form-control"></td>
                        <td>
                            <input type="hidden" name="uid" value="<?= $cust['uid'] ?>">
                            <button type="submit" name="update_customer" class="btn btn-success btn-sm mb-1">Update</button>
                            <button type="submit" name="delete_customer" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </body>
</html>
