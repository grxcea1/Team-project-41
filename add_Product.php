<?php
session_start();
require_once("ffdbConn.php");

if (!isset($_SESSION["aid"]) && !isset($_SESSION["Email"])) {
    $_SESSION["noadminaccount"] = "You must be an Admin to interact with the inventory management.";
    header("Location: home.php");
    exit;
}

$categories = [
    1 => 'Action',
    2 => 'Animation',
    3 => 'Romance',
    4 => 'Comedy',
    5 => 'Thriller',
    6 => 'Horror'
];

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

            $_SESSION["success2"] = "Successfully added new product to inventory!";
            header("Location: add_Product.php");
            exit;
        } catch (PDOException $ex) {
            $_SESSION["failure2"] = "Failed to add product to system, Please ensure all fields are filled or try again later.";
            header("Location: add_Product.php");
            exit;
        }
    } else {
        $_SESSION["failure3"] = "Failed to upload image, please ensure image is png or jpg or try again later.";
        header("Location: add_Product.php");
        exit;
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

<?php
    if (isset($_SESSION['success2'])) {
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
                    " . $_SESSION['success2'] . "
                </div>
            </div>";
        unset($_SESSION['success2']);
    }

    if (isset($_SESSION['failure2'])) {
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
                    " . $_SESSION['failure2'] . "
                </div>
            </div>";
        unset($_SESSION['failure2']);
    }

    if (isset($_SESSION['failure3'])) {
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
                    " . $_SESSION['failure3'] . "
                </div>
            </div>";
        unset($_SESSION['failure3']);
    }

    ?>

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
        <a href="returns.php">Returns</a>
        <a href="customerDetails.php">Customer Management</a>
        <a href="password.php">Password</a>
    </nav>
</div>

<div class="container mt-5" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <h2>Add a Movie</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="p_Name" style="color: white;">Title:</label>
            <input type="text" class="form-control" name="p_Name" required>
        </div>
        <div class="form-group">
            <label for="p_Description" style="color: white;">Description:</label>
            <textarea class="form-control" name="p_Description" required></textarea>
        </div>
        <div class="form-group">
            <label for="p_Price" style="color: white;">Price:</label>
            <input type="number" step="0.01" class="form-control" name="p_Price" required>
        </div>
        <div class="form-group">
            <label for="p_RentPrice" style="color: white;">Rent Price:</label>
            <input type="number" step="0.01" class="form-control" name="p_RentPrice" required>
        </div>
        <div class="form-group">
            <label for="p_ReleaseDate" style="color: white;">Release Date:</label>
            <input type="date" class="form-control" name="p_ReleaseDate" required>
        </div>
        <div class="form-group">
            <label for="categoryID" style="color: white;">Category:</label>
            <select class="form-control" name="categoryID" required>
                <?php foreach ($categories as $id => $name): ?>
                    <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="p_Stock" style="color: white;">Stock:</label>
            <input type="number" class="form-control" name="p_Stock" required>
        </div>
        <div class="form-group">
            <label for="p_ageRating" style="color: white;">Age Rating:</label>
            <input type="text" class="form-control" name="p_ageRating" required>
        </div>
        <div class="form-group">
            <label for="p_Duration" style="color: white;">Duration:</label>
            <input type="text" class="form-control" name="p_Duration" required>
        </div>
        <div class="form-group">
            <label for="p_Starring" style="color: white;">Starring:</label>
            <input type="text" class="form-control" name="p_Starring" required>
        </div>
        <div class="form-group">
            <label for="p_Director" style="color: white;">Director:</label>
            <input type="text" class="form-control" name="p_Director" required>
        </div>
        <div class="form-group">
            <label for="p_Image" style="color: white;">Movie Image:</label>
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