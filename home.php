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
    <?php
        require_once("ffdbConn.php");
    ?>

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
                <button type="submit" id="search-button">Go</button>
                </form>
            </div>


        
    </div>
    <!-- <div class="searchContainer">
        <form class="search" action="search.php" method="GET">
            <input type="text" id="searchInput" placeholder="Enter here...">
            <button class="searchButton" type="submit"><img src="search.png" alt=""></button>
        </form>
    </div>
    <div id="suggestionBox" class="suggestions"></div> -->

    <!-- Header section -->
     <div id="categories">
        <div class="container">
           <div class="header">
            <li><a href="ComedyPage.html">Comedy</a></li>
            <li><a href="romance.html">Romance</a></li>
            <li><a href="horror.html">Horror</a></li>
            <li><a href="thriller.html">Thriller</a></li>
            <li><a href="action.html">Action</a></li>
            <li><a href="animation.html">Animation</a></li>
      
          </div>
        </div>
     </div>
    <!-- Slideshow container -->
    <div class="slideshow-container">
        <div class="mySlides">
            <div class="numbertext">1 / 5</div>
            <a href="movieinfo.php?movie=28">
                <img src="images/The Nightingale (2018).jpg" style="width:100%; height:20%;">
            </a>
        </div>


        <div class="mySlides">
            <div class="numbertext">2 / 5</div>
            <a href="movieinfo.php?movie=3">
                <img src="images/Trigger Warning (2024).jpg" style="width:100%; height:20%;">
            </a>
        </div>


        <div class="mySlides">
            <div class="numbertext">3 / 5</div>
            <!-- Add toy story link -->
                <img src="images/Toy Story 4 (2019).jpg" style="width:100%; height:20%;">

        </div>
       
        <div class="mySlides">
            <div class="numbertext">4 / 5</div>
            <a href="movieinfo.php?movie=17">
                <img src="images/The Fall Guy (2024).jpg" style="width:100%; height:20%;">
            </a>
        </div>
   
       
        <div class="mySlides">
            <div class="numbertext">5 / 5</div>
            <a href="movieinfo.php?movie=14">
                <img src="images/The Idea of You (2024).jpg" style="width:100%; height:20%;">
            </a>
        </div>



        <!-- Next and previous buttons -->
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>

    </div>
    <br>

<!-- Movie posters -->
<div class="container mt-4">
<h2>Popular Movies</h2>
<div class="row">
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=11">
            <img src="images/A family affair.jpg" alt="movie 1" class="img-fluid">
        </div>
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 11");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=25">
            <img src="images/Alone.jpg" alt="movie 2" class="img-fluid">
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 25");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        </div>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=26">
            <img src="images/apartment 7a.jpg" alt="movie 3" class="img-fluid">
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 26");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        </div>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=21">
            <img src="images/blink twice.jpg" alt="movie 4" class="img-fluid">
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 21");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        </div>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
</div>
</div>


<div class="container mt-4">
<h2>New Movies</h2>
<div class="row">
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=2">
            <img src="images/deadpool&wolverine.jpg" alt="movie 5" class="img-fluid">
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 2");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        </div>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=18">
            <img src="images/family switch.jpg" alt="movie 6" class="img-fluid">
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 18");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        </div>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=16">
            <img src="images/Fly Me to the Moon (2024).jpg" alt="movie 7" class="img-fluid">
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 16");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        </div>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=24">
            <img src="images/Nowhere (2023).jpg" alt="movie 8" class="img-fluid">
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 24");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        </div>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
</div>
</div>


<div class="container mt-4">
<h2>Recommended Movies</h2>
<div class="row">
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=29">
            <img src="images/Look Away (2018).jpg" alt="movie 9" class="img-fluid">
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 29");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        </div>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=8">
            <img src="images/Inside Out (2015).jpg" alt="movie 10" class="img-fluid">
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 8");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        </div>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=7">
            <img src="images/wish.webp" alt="movie 11" class="img-fluid">
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 7");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        </div>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
        <a href="movieinfo.php?movie=10">
            <img src="images/Rio (2011).jpg" alt="movie 12" class="img-fluid">
        </a>
        <?php
            $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = 10");
            $stmt->execute();
            $result = $stmt->fetch();
            $buyprice = $result['p_Price'];
            echo "<p>£".$buyprice."</p>";
        ?>
        </div>
        <button class="A2Cbutton" onclick="addToCart()">Add to Cart</button>
    </div>
</div>
</div>
     <div style="text-align:center">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
        <span class="dot" onclick="currentSlide(4)"></span>
        <span class="dot" onclick="currentSlide(5)"></span>
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
    <script src="sscript.js"></script>
    <script src="search.js"></script>
    <script>
        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem("cart")) || {};
            let totalCount = Object.values(cart).reduce((acc, count) => acc + count, 0);
            document.getElementById("insideCart").innerText = totalCount;
        }
        function addToCart(productId) {
            let cart = JSON.parse(localStorage.getItem("cart")) || {};
            cart[productId] = (cart[productId] || 0) + 1;
            localStorage.setItem("cart", JSON.stringify(cart));
            updateCartCount();
            alert("Item added to cart!");
        }
        updateCartCount();
    </script>
</html>

    
    