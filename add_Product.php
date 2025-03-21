<?php
session_start();
require_once("ffdbConn.php");

$categories = [
    1 => 'Action',
    2 => 'Animation',
    3 => 'Romance',
    4 => 'Comedy',
    5 => 'Thriller',
    6 => 'Horror'
];

if (!isset($_SESSION['Email'])) {
    exit('Login to add a product.');
}

if (isset($_POST['add_product']) && isset($_FILES['p_Image'])) {
    if ($_FILES['p_Image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['p_Image']['tmp_name']);

        try {
            $stmt = $pdo->prepare("INSERT INTO product (p_Name, p_Price, p_RentPrice, p_Description, p_ReleaseDate, categoryID, p_Stock, p_ageRating, p_Duration, p_Starring, p_Director, p_Image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['p_Name'], $_POST['p_Price'], $_POST['p_RentPrice'], $_POST['p_Description'],
                $_POST['p_ReleaseDate'], $_POST['categoryID'], $_POST['p_Stock'],
                $_POST['p_ageRating'], $_POST['p_Duration'], $_POST['p_Starring'], $_POST['p_Director'], $imageData
            ]);

            echo '<p style="color:green; text-align:center;">Movie successfully added to the database.</p>';
        } catch (PDOException $ex) {
            echo '<p style="color:red; text-align:center;">Failed to add movie: ' . $ex->getMessage() . '</p>';
        }
    } else {
        echo '<p style="color:red; text-align:center;">Error uploading image file.</p>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="home.css">
    <link rel="shortcut icon" href="fav">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
<script src="sscript.js"></script>

<button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>
<div id="image-container">
    <img id="light-image" src="images/light.jpg" alt="Light Mode Image" class="mode-image" style="display: none;">
    <img id="dark-image" src="images/dark.jpg" alt="Dark Mode Image" class="mode-image">
</div>

<header>
    <div class="logo-container">
        <div class="circle">
            <div class="text">
                <span class="initials">FF</span>
            </div>
        </div>
        <div class="logo-name">Film Fuse - Add Product</div>
    </div>
</header>

<div class="sidebox">
    <nav class="nav-bar">
        <a href="home.php">Home</a>
        <a href="adminPage.php">Inventory</a>
        <a href="orders.php">Orders</a>
        <a href="password.php">Password</a>
    </nav>
</div>

<div class="container mt-5">
    <h2>Add a Movie</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="p_Name">Title:</label>
            <input type="text" class="form-control" name="p_Name" required>
        </div>
        <div class="form-group">
            <label for="p_Description">Description:</label>
            <textarea class="form-control" name="p_Description" required></textarea>
        </div>
        <div class="form-group">
            <label for="p_Price">Price:</label>
            <input type="number" step="0.01" class="form-control" name="p_Price" required>
        </div>
        <div class="form-group">
            <label for="p_RentPrice">Rent Price:</label>
            <input type="number" step="0.01" class="form-control" name="p_RentPrice" required>
        </div>
        <div class="form-group">
            <label for="p_ReleaseDate">Release Date:</label>
            <input type="date" class="form-control" name="p_ReleaseDate" required>
        </div>
        <div class="form-group">
            <label for="categoryID">Category:</label>
            <select class="form-control" name="categoryID" required>
                <?php foreach ($categories as $id => $name): ?>
                    <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="p_Stock">Stock:</label>
            <input type="number" class="form-control" name="p_Stock" required>
        </div>
        <div class="form-group">
            <label for="p_ageRating">Age Rating:</label>
            <input type="text" class="form-control" name="p_ageRating" required>
        </div>
        <div class="form-group">
            <label for="p_Duration">Duration:</label>
            <input type="text" class="form-control" name="p_Duration" required>
        </div>
        <div class="form-group">
            <label for="p_Starring">Starring:</label>
            <input type="text" class="form-control" name="p_Starring" required>
        </div>
        <div class="form-group">
            <label for="p_Director">Director:</label>
            <input type="text" class="form-control" name="p_Director" required>
        </div>
        <div class="form-group">
            <label for="p_Image">Movie Image:</label>
            <input type="file" class="form-control-file" name="p_Image" accept="image/*" required>
        </div>
        <button type="submit" name="add_product" class="btn btn-primary">Add</button>
    </form>
</div>

<footer class="footer">
    <hr>
    <p>Join the Film Fuse community today, and let us bring the world of cinema to you.</p>
</footer>
</body>
</html>