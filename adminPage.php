<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FilmFuse - Inventory Management</title>
    <link rel="stylesheet" href="adminPage.css">
    <link rel="stylesheet" href="adminPage2.css">
    <script src="./index.js"></script>
</head>
<body>
    <header>
        <div class="logo-container">
            <div class="circle">
                <div class="text">
                    <span class="initials">FF</span>
                </div>
            </div>
            <div class="logo-name">FilmFuse</div>
        </div>
    </header>

    <div class="sidebox">
        <nav class="nav-bar">
            <a href="dashboard.php">Dashboard</a>
            <a href="inventory.php">Inventory</a>
            <a href="orders.php">Orders</a>
            <a href="customers.php">Customers</a>
            <a href="reports.php">Reports</a>
            <a href="profile.php">Profile</a>
            <a href="settings.php">Settings</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>

    <div class="main-container">
        <h1>Inventory Management</h1>
        <div class="buttons">
            <a href="add_product.php" class="btn">Add Product</a>
        </div>

        <?php
require_once 'ffdbConn.php'; 

if (!$pdo) {
    die("Error: Database connection failed.");
}

$products = [];
try {
    $stmt = $pdo->prepare("SELECT pid, p_Name, p_Price, p_Stock, categoryID FROM product");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching products: " . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pid = $_POST['pid'] ?? null;
    $p_Name = $_POST['p_Name'] ?? '';
    $p_Price = $_POST['p_Price'] ?? 0;
    $p_Stock = $_POST['p_Stock'] ?? 0;
    
    if (!empty($pid) && !empty($p_Name)) {
        try {
            $stmt = $pdo->prepare("UPDATE product SET p_Name = ?, p_Price = ?, p_Stock = ? WHERE pid = ?");
            $stmt->execute([$p_Name, $p_Price, $p_Stock, $pid]);
            echo "<p style='color: green;'>Product updated successfully!</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Error updating product: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color: red;'>Invalid input! Make sure all fields are filled.</p>";
    }
}
?>

        <div class="inventory-table">
            <table>
                <thead>
                    <tr>
                        <th>Product ID</th>
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
                        <td><?= htmlspecialchars($product['pid']) ?></td>
                        <td><?= htmlspecialchars($product['p_Name']) ?></td>
                        <td><?= htmlspecialchars($product['p_Price']) ?></td>
                        <td><?= htmlspecialchars($product['p_Stock']) ?></td>
                        <td><?= htmlspecialchars($product['categoryID']) ?></td>
                        <td>
                            <form action="inventory.php" method="POST">
                                <input type="hidden" name="pid" value="<?= $product['pid'] ?>">
                                <input type="text" name="p_Name" value="<?= htmlspecialchars($product['p_Name']) ?>" required>
                                <input type="number" name="p_Price" step="0.01" value="<?= $product['p_Price'] ?>" required>
                                <input type="number" name="p_Stock" value="<?= $product['p_Stock'] ?>" required>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
