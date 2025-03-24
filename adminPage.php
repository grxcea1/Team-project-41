<?php
session_start();
require_once("ffdbConn.php");

if (!isset($_SESSION["aid"]) && !isset($_SESSION["Email"])) {
    $_SESSION["noadminaccount"] = "You must be an Admin to interact with the inventory management.";
    header("Location: home.php");
    exit;
}

if (isset($_POST['logout'])) {
    session_unset(); 
    session_destroy(); 
    header("Location: home.php");
    exit();
}

$categories = [
    1 => 'Action',
    2 => 'Animation',
    3 => 'Romance',
    4 => 'Comedy',
    5 => 'Thriller',
    6 => 'Horror'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    $pid = $_POST['pid'];
    $name = $_POST['p_Name'];
    $price = $_POST['p_Price'];
    $stock = $_POST['p_Stock'];
    $description = $_POST['p_Description'];

    try {
        $updateQuery = "UPDATE product SET p_Name = ?, p_Price = ?, p_Stock = ?, p_Description = ? WHERE pid = ?";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([$name, $price, $stock, $description, $pid]);

        $_SESSION["success3"] = "Successfully updated product in the inventory!";
        header("Location: adminPage.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION["failure5"] = "Failed to connect to database, try again later.";
        header("Location: adminPage.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $pid = $_POST['pid'];

    try {
        $deleteQuery = "DELETE FROM product WHERE pid = ?";
        $stmt = $pdo->prepare($deleteQuery);
        $stmt->execute([$pid]);

        $_SESSION["success4"] = "Successfully deleted product from the inventory!";
        header("Location: adminPage.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION["failure6"] = "Failed to delete product, from the database, please try again later.";
        header("Location: adminPage.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_image']) && isset($_POST['pid']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
    $pid = $_POST['pid'];
    $imageData = file_get_contents($_FILES['new_image']['tmp_name']);

    try {
        $updateQuery = "UPDATE product SET p_Image = ? WHERE pid = ?";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([$imageData, $pid]);

        $_SESSION["success5"] = "Successfully uploaded image to database!";
        header("Location: adminPage.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION["failure7"] = "Error uploading the image, please use a png, jpeg or jpg or try again later.";
        header("Location: adminPage.php");
        exit;
    }
}

$reportQuery = "SELECT 
    p.pid, 
    p.p_Name AS Product_Name, 
    SUM(od.quantity) AS Total_Orders, 
    r.description AS Review_Description, 
    r.rating AS Review_Rating 
FROM orderdetails od 
JOIN product p ON od.pid = p.pid 
LEFT JOIN reviews r ON p.pid = r.product_id 
GROUP BY p.pid, p.p_Name, r.description, r.rating 
ORDER BY Total_Orders DESC";
$reportStmt = $pdo->prepare($reportQuery);
$reportStmt->execute();
$popularProducts = $reportStmt->fetchAll(PDO::FETCH_ASSOC);

$categoryFilter = $_GET['category'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';
$searchName = $_GET['search_name'] ?? '';

$query = "SELECT * FROM product WHERE 1=1";
$params = [];

if (!empty($categoryFilter)) {
    $query .= " AND categoryID = ?";
    $params[] = $categoryFilter;
}
if (!empty($minPrice)) {
    $query .= " AND p_Price >= ?";
    $params[] = $minPrice;
}
if (!empty($maxPrice)) {
    $query .= " AND p_Price <= ?";
    $params[] = $maxPrice;
}
if (!empty($searchName)) {
    $query .= " AND p_Name LIKE ?";
    $params[] = "%$searchName%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stockAlerts = [];
foreach ($products as $product) {
    $stock = $product['p_Stock'];
    $p_Name = $product['p_Name'];
    if ($stock == 0) {
        $stockAlerts[] = ["p_Stock" => $stock, "p_Name" => $p_Name, "message" => "is out of stock!"];
    } elseif ($stock < 5) {
        $stockAlerts[] = ["p_Stock" => $stock, "p_Name" => $p_Name, "message" => "is low on stock (less than 5 left)!"];
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
         <meta charset="UTF-8">
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Inventory Management</title>
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
         <link rel="stylesheet" href="home.css">
         <link rel="stylesheet" href="styles.css">
         <link rel="shortcut icon" href="fav">
         <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
         <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
         <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    </head>
    <body>

    <?php

if (isset($_SESSION['adminlogin'])) {
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
                " . $_SESSION['adminlogin'] . "
            </div>
          </div>";
    unset($_SESSION['adminlogin']);
}

    if (isset($_SESSION['success3'])) {
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
                    " . $_SESSION['success3'] . "
                </div>
            </div>";
        unset($_SESSION['success3']);
    }

    if (isset($_SESSION['success4'])) {
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
                    " . $_SESSION['success4'] . "
                </div>
            </div>";
        unset($_SESSION['success4']);
    }

    if (isset($_SESSION['success5'])) {
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
                    " . $_SESSION['success5'] . "
                </div>
            </div>";
        unset($_SESSION['success5']);
    }

    if (isset($_SESSION['failure5'])) {
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
                    " . $_SESSION['failure5'] . "
                </div>
            </div>";
        unset($_SESSION['failure5']);
    }

    if (isset($_SESSION['failure6'])) {
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
                    " . $_SESSION['failure6'] . "
                </div>
            </div>";
        unset($_SESSION['failure6']);
    }

    if (isset($_SESSION['failure7'])) {
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
                    " . $_SESSION['failure7'] . "
                </div>
            </div>";
        unset($_SESSION['failure7']);
    }
    ?>

     <!--link to js-->
    <script src="sscript.js"></script>
    <script>
    window.onload = function () {
        const stockAlerts = <?php echo json_encode($stockAlerts); ?>;
        stockAlerts.forEach(product => {
            alert(product.p_Name + " " + product.message);
        });
    };
    </script>

    
    <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>
    <?php if (isset($_SESSION["uid"]) || isset($_SESSION["Email"])): ?>
    <form method="POST" style="float: right;">
        <button id="log-out" name="logout">Log Out</button>
    </form>
    <?php endif; ?>

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
                    <div class="logo-name">Film Fuse - Inventory Management</div>
                </a>
            </div>
        </header>

        <div class="sidebox">
            <nav class="nav-bar">
                <a href="home.php">Home</a> 
                <a href="orders.php">Orders</a>
                <a href="customerDetails.php">Customer Management</a>
                <a href="add_Product.php">Add Products</a>
                <a href="password.php">Password</a>
            </nav>
        </div>

    <div class="container mt-5 section">
        <h2>Product Report</h2>
        <table class="table table-dark table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Total Orders</th>
                    <th>Reviews</th>
                    <th>Review Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($popularProducts as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['Product_Name']) ?></td>
                        <td><?= htmlspecialchars($product['Total_Orders']) ?></td>
                        <td><?= htmlspecialchars($product['Review_Description'] ?? 'No reviews') ?></td>
                        <td><?= htmlspecialchars($product['Review_Rating'] ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

        <div class="container mt-5 section">
            <h2>Manage Products</h2>
            <form method="GET">
                <div class="form-group">
                    <input type="text" class="form-control" name="search_name" placeholder="Search by Name" value="<?= htmlspecialchars($searchName) ?>">
                </div>
                <div class="form-group">
                    <select class="form-control" name="category">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $id => $name): ?>
                            <option value="<?= $id ?>" <?= ($categoryFilter == $id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="number" class="form-control" name="min_price" placeholder="Min Price" value="<?= htmlspecialchars($minPrice) ?>">
                </div>
                <div class="form-group">
                    <input type="number" class="form-control" name="max_price" placeholder="Max Price" value="<?= htmlspecialchars($maxPrice) ?>">
                </div>
                <button type="submit" class="btn btn-primary" style="margin-bottom: 10px;">Filter</button>
            </form>

            <table id="productTable" class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <td><?= htmlspecialchars($product['pid']) ?></td>
                            <td><input type="text" class="form-control" name="p_Name" value="<?= htmlspecialchars($product['p_Name']) ?>" required></td>
                            <td>
                                <?php 
                                if (!empty($product['p_Image'])) {
                                    $imageData = base64_encode($product['p_Image']);
                                    echo '<img src="data:image/jpeg;base64,' . $imageData . '" width="100" height="100"/>';
                                } else {
                                    echo 'No Image';
                                }
                                ?>
                                <input type="file" name="new_image" class="form-control mt-2">
                            </td>
                            <td><input type="number" class="form-control" name="p_Price" step="0.01" value="<?= htmlspecialchars($product['p_Price']) ?>" required></td>
                            <td><input type="number" class="form-control" name="p_Stock" value="<?= htmlspecialchars($product['p_Stock']) ?>" required></td>
                            <td><?= htmlspecialchars($categories[$product['categoryID']] ?? 'Unknown') ?></td>
                            <td><textarea class="form-control" name="p_Description" required><?= htmlspecialchars($product['p_Description'] ?? '') ?></textarea></td>
                            <td>
                                <input type="hidden" name="pid" value="<?= $product['pid'] ?>">
                                <button type="submit" name="update_product" class="btn btn-success">Update</button>
                                <button type="submit" name="delete_product" class="btn btn-danger">Delete</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </body>
</html>