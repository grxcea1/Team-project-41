<?php
session_start();
require_once 'ffdbConn.php';

$categories = [
    1 => 'Action',
    2 => 'Animation',
    3 => 'Romance',
    4 => 'Comedy',
    5 => 'Thriller',
    6 => 'Horror'
];

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
        <video class="video-background" autoplay muted loop>
            <source src="images/image 4.mp4" type="video/mp4">
        </video>
        <script src="sscript.js"></script>
        <header>
            <div class="logo-container">
                <a href="home.html" class="logo-link">
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
                <a href="home.html">Home</a> 
                <a href="orders.php">Orders</a>
                <a href="password.php">Password</a>
                <a href="add_Product.php">Add Products</a>
            </nav>
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

            <table id="productTable" class="table table-light table-striped">
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
                            <form action="" method="POST">
                                <td><?= htmlspecialchars($product['pid']) ?></td>
                                <td><input type="text" class="form-control" name="p_Name" value="<?= htmlspecialchars($product['p_Name']) ?>" required></td>
                                <td><input type="text" class="form-control" name="p_Image" value="<?= htmlspecialchars($product['p_Image'] ?? '') ?>" required></td>
                                <td class="movie-price"><input type="number" class="form-control" name="p_Price" step="0.01" value="<?= htmlspecialchars($product['p_Price']) ?>" required></td>
                                <td><input type="number" class="form-control" name="p_Stock" value="<?= htmlspecialchars($product['p_Stock']) ?>" required></td>
                                <td><?= htmlspecialchars($categories[$product['categoryID']] ?? 'Unknown') ?></td>
                                <td><textarea class="form-control" name="p_Description" required><?= htmlspecialchars($product['p_Description'] ?? '') ?></textarea></td>
                                <td>
                                    <input type="hidden" name="pid" value="<?= $product['pid'] ?>">
                                    <button type="submit" name="update_product" class="btn btn-success">Update</button>
                                    <button type="submit" name="delete_product" class="btn btn-danger">Delete</button>
                                    <a href="edit_Product.php?pid=<?= $product['pid'] ?>" class="btn btn-info">Edit</a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <script>
            $(document).ready(function() {
                $('#productTable').DataTable();
            });
        </script>
    </body>
</html>