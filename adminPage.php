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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmFuse - Admin</title>
    <link rel="stylesheet" href="adminPage.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>
<body>
<header>
    <h1>Inventory Management</h1>
    <a href="logout.php">Logout</a>
</header>

<div class="sidebar">
    <nav>
        <ul>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="password.php">Password</a></li>
            <li><a href="add_Product.php">Add Product</a></li>
        </ul>
    </nav>
</div>

<form method="GET">
    <input type="text" name="search_name" placeholder="Search by Name" value="<?= htmlspecialchars($searchName) ?>">
    <select name="category">
        <option value="">All Categories</option>
        <?php foreach ($categories as $id => $name): ?>
            <option value="<?= $id ?>" <?= ($categoryFilter == $id) ? 'selected' : '' ?>>
                <?= htmlspecialchars($name) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="number" name="min_price" placeholder="Min Price" value="<?= htmlspecialchars($minPrice) ?>">
    <input type="number" name="max_price" placeholder="Max Price" value="<?= htmlspecialchars($maxPrice) ?>">
    <button type="submit">Filter</button>
</form>

<table id="productTable">
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
                    <td><input type="text" name="p_Name" value="<?= htmlspecialchars($product['p_Name']) ?>" required></td>
                    <td><input type="text" name="p_Image" value="<?= htmlspecialchars($product['p_Image'] ?? '') ?>" required></td>
                    <td><input type="number" name="p_Price" step="0.01" value="<?= htmlspecialchars($product['p_Price']) ?>" required></td>
                    <td><input type="number" name="p_Stock" value="<?= htmlspecialchars($product['p_Stock']) ?>" required></td>
                    <td><?= htmlspecialchars($categories[$product['categoryID']] ?? 'Unknown') ?></td>
                    <td><textarea name="p_Description" required><?= htmlspecialchars($product['p_Description'] ?? '') ?></textarea></td>
                    <td>
                        <input type="hidden" name="pid" value="<?= $product['pid'] ?>">
                        <button type="submit" name="update_product">Update</button>
                        <button type="submit" name="delete_product">Delete</button>
                        <a href="edit_Product.php?pid=<?= $product['pid'] ?>">Edit</a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('#productTable').DataTable();
    });
</script>
</body>
</html>

