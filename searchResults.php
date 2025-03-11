<?php
session_start();
require 'ffdbConn1.php';


// search form submission
if (isset($_GET['query'])) {
    $search = strtolower($_GET['query']);


   
    $query = "SELECT p.* FROM product p JOIN category c ON p.categoryID = c.categoryID
       WHERE p.p_Name LIKE :search_name OR c.Name LIKE :search_category";
   
   
    $statement = $filmfuse_db->prepare($query);
    $statement->execute(array(
        ':search_name' => '%' . $search . '%',
        ':search_category' => $search . '%'
    ));


 
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
   
    $products = [];
}
?>


<!DOCTYPE html>
<html>
    <head>
         <!-- Metadata for character set, compatibility, and viewport settings -->
         <meta charset="UTF-8">
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <title>Home page</title>
         <!-- Bootstrap CSS -->
         <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!--css link for this page -->
         <link rel="stylesheet" href="home.css">
         <!--fav Icon -->
         <link rel="shortcut icon" href="fav">
         <!--box icons-->
         <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    </head>
   
<body>
    <!--link to js-->
    <script src="sscript.js"></script>


    <!-- Toggle Button to Switch Backgrounds -->
    <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>


    <!-- Light Mode and Dark Mode Images -->
    <div id="image-container">
        <img id="light-image" src="images/light.jpg" alt="Light Mode Image" class="mode-image" style="display: none;">
        <img id="dark-image" src="images/dark.jpg"  alt="Dark Mode Image" class="mode-image">
    </div>


    <!-- LOGO BAR -->
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


    <!-- Navigation menu -->
    <div class="sidebox">
        <nav class="nav-bar">
            <a href="home.php">Home</a>
            <a href="ffLoginPage.php">Login</a>
            <a href="aboutus.html">About Us</a>
            <a href="basket.php">Basket<span id="insideCart">0</span></a>
            <a href="account.html">Accounts</a>
            <a href="contact.html">Contact us</a>


            <div id="search-container">
                <!-- Search Form -->
                <form action="searchResults.php" method="GET">
                    <input type="text" id="search-bar" placeholder="Search..." name="query" value="<?= isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '' ?>">
                    <button type="submit" id="search-button">Go</button>
                </form>
            </div>
        </nav>
    </div>


   <!-- Product Results Display -->
<div class="container mt-4">
    <h2>Search Results</h2>
    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $row): ?>
                <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                    <div class="poster-box text-center">
                            <img src="<?= htmlspecialchars($row['p_Image']) ?>" alt="<?= htmlspecialchars($row['p_Name']) ?>" class="img-fluid movie-img">
                        </a>
                        <h3 class="movie-title"><?= htmlspecialchars($row['p_Name']) ?></h3>
                        <p class="movie-price">£<?= htmlspecialchars($row['p_Price']) ?></p>
                    </div>
                    <button class="A2Cbutton btn-sm" onclick="addToCart()">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        <?php else:  ?>
            <p class="text-center">Sorry, no movies found.</p>
        <?php endif; ?>
    </div>
</div>


<style>
.movie-img {
    width: 100%;
    height: auto;
    max-height: 1000px;
    object-fit: cover;
    border-radius: 5px;
}


.movie-title {
    font-size: 14px;
    margin-top: 5px;
}


.movie-price {
    font-size: 12px;
    color: #666;
}


.A2Cbutton {
    font-size: 12px;
    padding: 5px 10px;
}


</style>


   
    <footer class="footer">
        <hr>
        <p>
            Join the Film Fuse community today, and let us bring the world of cinema to you.
            Whether you're planning a cozy night in or looking for your next movie obsession,
            we’re here to make it memorable.
        </p>
    </footer>
</body>
</html>


