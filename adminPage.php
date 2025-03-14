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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_image']) && isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    
    if ($_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['new_image']['tmp_name']);
        
        $updateQuery = "UPDATE product SET p_Image = ? WHERE pid = ?";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([$imageData, $pid]);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error uploading file.";
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
            <!--link to js-->
        <script src="sscript.js"></script>

    
    <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>

    <div id="image-container">
        <img id="light-image" src="images/light.jpg" alt="Light Mode Image" class="mode-image" style="display: none;">
        <img id="dark-image" src="images/dark.jpg"  alt="Dark Mode Image" class="mode-image">
    </div>


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

        <div class="container mt-5">
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
        <button id="showMoreReport" class="btn btn-primary">More</button>
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
                                <a href="edit_Product.php?pid=<?= $product['pid'] ?>" class="btn btn-info">Edit</a>
                                <button type="submit" name="delete_product" class="btn btn-danger">Delete</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
        <script>
            // $(document).ready(function() {
            //     $('#productTable').DataTable();
            // });

            $("#showMoreManage").on("click", function() {
                $(".manage-products-row:hidden").slice(0, 5).slideDown();
                if ($(".manage-products-row:hidden").length === 0) {
                    $(this).hide();
                }
            });
        function selectImage(element) {
            $(element).siblings('.image-input').click();
        }
        </script>
    </body>
</html>