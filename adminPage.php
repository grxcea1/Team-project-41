<?php
session_start();
require_once 'ffdbConn.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_product'])) {
        $p_Name = htmlspecialchars($_POST['p_Name']);
        $p_Price = filter_var($_POST['p_Price'], FILTER_VALIDATE_FLOAT);
        $p_Stock = filter_var($_POST['p_Stock'], FILTER_VALIDATE_INT);
        $categoryID = filter_var($_POST['categoryID'], FILTER_VALIDATE_INT);
        
        if ($p_Name && $p_Price !== false && $p_Stock !== false && $categoryID !== false) {
            $stmt = $pdo->prepare("INSERT INTO product (p_Name, p_Price, p_Stock, categoryID) VALUES (?, ?, ?, ?)");
            $stmt->execute([$p_Name, $p_Price, $p_Stock, $categoryID]);
        }
    }
    
    if (isset($_POST['update_product'])) {
        $pid = filter_var($_POST['pid'], FILTER_VALIDATE_INT);
        $p_Name = htmlspecialchars($_POST['p_Name']);
        $p_Price = filter_var($_POST['p_Price'], FILTER_VALIDATE_FLOAT);
        $p_Stock = filter_var($_POST['p_Stock'], FILTER_VALIDATE_INT);
        
        if ($pid && $p_Name && $p_Price !== false && $p_Stock !== false) {
            $stmt = $pdo->prepare("UPDATE product SET p_Name = ?, p_Price = ?, p_Stock = ? WHERE pid = ?");
            $stmt->execute([$p_Name, $p_Price, $p_Stock, $pid]);
        }
    }
    
    if (isset($_POST['delete_product'])) {
        $pid = filter_var($_POST['pid'], FILTER_VALIDATE_INT);
        if ($pid) {
            $stmt = $pdo->prepare("DELETE FROM product WHERE pid = ?");
            $stmt->execute([$pid]);
        }
    }
}

$stmt = $pdo->query("SELECT * FROM product");
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
            <li><a href="add_product.php">Add Product</a></li>
        </ul>
    </nav>
</div>

<table id="productTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <form action="" method="POST">
                    <td><?= htmlspecialchars($product['pid']) ?></td>
                    <td><input type="text" name="p_Name" value="<?= htmlspecialchars($product['p_Name']) ?>" required></td>
                    <td><input type="number" name="p_Price" step="0.01" value="<?= htmlspecialchars($product['p_Price']) ?>" required></td>
                    <td><input type="number" name="p_Stock" value="<?= htmlspecialchars($product['p_Stock']) ?>" required></td>
                    <td><?= htmlspecialchars($product['categoryID']) ?></td>
                    <td>
                        <input type="hidden" name="pid" value="<?= $product['pid'] ?>">
                        <button type="submit" name="update_product">Update</button>
                        <button type="submit" name="delete_product">Delete</button>
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
