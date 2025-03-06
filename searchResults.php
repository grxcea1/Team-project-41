<?php
session_start();
require 'ffdbConn.php';

// Handle search form submission
if (isset($_GET['query'])) {
    $search = strtolower($_GET['query']); // get the user's search query

    // Construct the SQL query
    $query = "SELECT p.* FROM product p JOIN category c ON p.categoryID = c.categoryID
       WHERE p.p_Name LIKE :search_name OR c.Name LIKE :search_category";
    
    // Prepare and execute the SQL query
    $statement = $filmfuse_db->prepare($query);
    $statement->execute(array(
        ':search_name' => '%' . $search . '%',
        ':search_category' => $search . '%'
    ));
    // Fetch results - the matching products
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
} else{
    // No results since query is empty
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="home.css">
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
                <form action="searchResults.php" method="GET">
                <input type="text" id="search-bar" placeholder="Search..." name="query">
                <button type="submit" id="search-button">Go</buttonbutton>
                </form>
            </div>

        
    </div>

    <div class="product-container">
        <h1>Search Results</h1>
        <div class="product-grid">
            <?php if (!empty($products)): // display if there are products found ?> 
                <?php foreach ($products as $row): ?>
                    <div class="poster-box">
                        <a href="movieinfo.php?product_id=<?= htmlspecialchars($row['product_id']) ?>">
                            <img src="<?= htmlspecialchars($row['p_Image']) ?>" alt="<?= htmlspecialchars($row['p_Name']) ?>" >
                            <h2><?= htmlspecialchars($row['p_Name']) ?></h2>
                            <h3><?= htmlspecialchars($row['p_Price']) ?></h3>
                            <p><?= htmlspecialchars($row['p_Description']) ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: // display message for no products found ?>
                <p>Sorry, no movies found.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer">
    <hr>
    <P> 
    Join the Film Fuse community today, and let us bring the world of cinema to you.
    Whether you're planning a cozy night in or looking for your next movie obsession,
    we’re here to make it memorable.
    </P>
   </footer>
</body>
</html>