<?php
require_once("ffdbConn.php");

// Fetch all movies from the database
$stmt = $pdo->query("SELECT pid, p_Name, p_Image, p_Price FROM product");
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<style>
    .center-container {
    display: flex;
    justify-content: center; 
    align-items: center;      
    height: 100%;            
    text-align: center;      
    }

    </style>

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
              <a href="aboutus.php">About Us</a>
              <a href="basket.php">Basket<span id="insideCart">0</span></a>
              <a href="account.php">Accounts</a>
           
  
         
              <div id="search-container">
                  <input type="text" id="search-bar" placeholder="Search...">
                  <button id="search-button">Go</button>
              </div>
  
          
      </div>
  
    
        <!-- Movie grid -->
        <div class="movie-grid container">
            <h2>Comedy movies</h2>
            <div class="center-container">
                <h6>Ever wonder what makes a comedy great? We put together a collection of the best comedies we could find so you can watch and learn from the best in the biz</h6>
            </div>
             <!-- Header section -->
            <div id="categories">
                <div class="container">
                <div class="header">
                    <li><a href="romance.php">Romance</a></li>
                    <li><a href="horror.php">Horror</a></li>
                    <li><a href="thriller.php">Thriller</a></li>
                    <li><a href="action.php">Action</a></li>
                    <li><a href="animation.php">Animation</a></li>
                
                </div>
                </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="poster-box">
                    <a href="movieinfo.php?movie=16">
                        <img src="images/Fly Me to the Moon (2024).jpg" alt="movie 1" class="img-fluid">
                    </a>
                    </div>
                    <?php
                        $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 16");
                        $stmt->execute();
                        $result = $stmt->fetch();
                        $buyprice = $result['p_Price'];
                        echo "<p>£".$buyprice."</p>";
                    ?>
                    <button class="A2Cbutton" 
                            onclick="addToCart(
                                <?php echo $result['pid']; ?>, 
                                '<?php echo addslashes($result['p_Name']); ?>', 
                                <?php echo $result['p_Price']; ?>, 
                                'images/<?php echo addslashes($result['p_Image']); ?>'
                            )">
                            Add to Cart
                    </button>
                </div>
                <div class="col-md-3">
                    <div class="poster-box">
                    <a href="movieinfo.php?movie=17">
                        <img src="images/The Fall Guy (2024).jpg" alt="movie 2" class="img-fluid">
                    </a>
                    </div>
                    <?php
                        $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 17");
                        $stmt->execute();
                        $result = $stmt->fetch();
                        $buyprice = $result['p_Price'];
                        echo "<p>£".$buyprice."</p>";
                    ?>
                    <button class="A2Cbutton" 
                            onclick="addToCart(
                                <?php echo $result['pid']; ?>, 
                                '<?php echo addslashes($result['p_Name']); ?>', 
                                <?php echo $result['p_Price']; ?>, 
                                'images/<?php echo addslashes($result['p_Image']); ?>'
                            )">
                            Add to Cart
                    </button>
                </div>
                <div class="col-md-3">
                    <div class="poster-box">
                    <a href="movieinfo.php?movie=19">
                        <img src="images/Good Boys (2019).jpg" alt="movie 3" class="img-fluid">
                    </a>
                    </div>
                    <?php
                        $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 19");
                        $stmt->execute();
                        $result = $stmt->fetch();
                        $buyprice = $result['p_Price'];
                        echo "<p>£".$buyprice."</p>";
                    ?>
                    <button class="A2Cbutton" 
                            onclick="addToCart(
                                <?php echo $result['pid']; ?>, 
                                '<?php echo addslashes($result['p_Name']); ?>', 
                                <?php echo $result['p_Price']; ?>, 
                                'images/<?php echo addslashes($result['p_Image']); ?>'
                            )">
                            Add to Cart
                    </button>
                </div>
                <div class="col-md-3">
                    <div class="poster-box">
                    <a href="movieinfo.php?movie=18">
                        <img src="images/family switch.jpg" alt="movie 4" class="img-fluid">
                    </a>
                    </div>
                    <?php
                        $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 18");
                        $stmt->execute();
                        $result = $stmt->fetch();
                        $buyprice = $result['p_Price'];
                        echo "<p>£".$buyprice."</p>";
                    ?>
                    <button class="A2Cbutton" 
                            onclick="addToCart(
                                <?php echo $result['pid']; ?>, 
                                '<?php echo addslashes($result['p_Name']); ?>', 
                                <?php echo $result['p_Price']; ?>, 
                                'images/<?php echo addslashes($result['p_Image']); ?>'
                            )">
                            Add to Cart
                    </button>
                </div>
                <div class="col-md-3">
                    <div class="poster-box">
                    <a href="movieinfo.php?movie=20">
                        <img src="images/me time.jpg" alt="movie 4" class="img-fluid">
                    </a>
                    </div>
                    <?php
                        $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 20");
                        $stmt->execute();
                        $result = $stmt->fetch();
                        $buyprice = $result['p_Price'];
                        echo "<p>£".$buyprice."</p>";
                    ?>
                    <button class="A2Cbutton" 
                            onclick="addToCart(
                                <?php echo $result['pid']; ?>, 
                                '<?php echo addslashes($result['p_Name']); ?>', 
                                <?php echo $result['p_Price']; ?>, 
                                'images/<?php echo addslashes($result['p_Image']); ?>'
                            )">
                            Add to Cart
                    </button>
                </div>
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
    <script>
    function updateCartCount() {
        let cart = JSON.parse(localStorage.getItem("cart")) || {};
        let totalCount = Object.values(cart).reduce((acc, item) => acc + (item.quantity || 0), 0);
        document.getElementById("insideCart").innerText = totalCount;
    }

    function addToCart(productId, movieName, price, imageUrl) {
    let cart = JSON.parse(localStorage.getItem("cart")) || {};

    if (cart[productId]) {
        cart[productId].quantity += 1;
    } else {
        cart[productId] = { 
            name: movieName, 
            price: parseFloat(price),  
            imageUrl: imageUrl.replace("images/", ""),// To Pass the name to the basket page to show when adding
            quantity: 1 
        };
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCount();
    alert(movieName + " added to cart!");
}

    updateCartCount();

    </script>
</html>