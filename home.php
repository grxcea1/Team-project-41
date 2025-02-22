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
         <link rel="stylesheet" href="cssPages/home.css">
         <!--fav Icon -->
         <link rel="shortcut icon" href="fav">
         <!--box icons-->
         <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        
        <div class="search">
        <form method="post" action="searchbar.php">
         <p>Search Movies or Categories</p>
         <input class="input" type="text" name="searchKeyword" id="searchKeyword" required>
         <button class="search-button" type="submit" name="search">Search</button>
        </form>
        </div>
    </head>
    
<body>
    <!--link to js-->
    <script src="sscript.js"></script>
 
    <!-- Video background -->
    <video class="video-background" autoplay loop muted>
        <source src="images/image 4.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>


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
            <a href="#">Home</a>
            <a href="#">About Us</a>
            <a href="#">Basket</a>
        </nav>
    </div>


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
            <img src="images/The Nightingale (2018).jpg" style="width:100%; height:20%;">
        </div>


        <div class="mySlides">
            <div class="numbertext">2 / 5</div>
            <img src="images/Trigger Warning (2024).jpg" style="width:100%; height:20%;">
        </div>


        <div class="mySlides">
            <div class="numbertext">3 / 5</div>
            <img src="images/Toy Story 4 (2019).jpg" style="width:100%; height:20%;">
        </div>
       
        <div class="mySlides">
            <div class="numbertext">4 / 5</div>
            <img src="images/The Fall Guy (2024).jpg" style="width:100%; height:20%;">
        </div>
   
       
        <div class="mySlides">
            <div class="numbertext">5 / 5</div>
            <img src="images/The Idea of You (2024).jpg" style="width:100%; height:20%;">
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
            <img src="images/A family affair.jpg" alt="movie 1" class="img-fluid">
        </div>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
            <img src="images/Alone.jpg" alt="movie 2" class="img-fluid">
        </div>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
            <img src="images/apartment 7a.jpg" alt="movie 3" class="img-fluid">
        </div>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
            <img src="images/blink twice.jpg" alt="movie 4" class="img-fluid">
        </div>
    </div>
</div>
</div>


<div class="container mt-4">
<h2>New Movies</h2>
<div class="row">
    <div class="col-md-3">
        <div class="poster-box">
            <img src="images/deadpool&wolverine.jpg" alt="movie 5" class="img-fluid">
        </div>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
            <img src="images/family switch.jpg" alt="movie 6" class="img-fluid">
        </div>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
            <img src="images/Fly Me to the Moon (2024).jpg" alt="movie 7" class="img-fluid">
        </div>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
            <img src="images/Nowhere (2023).jpg" alt="movie 8" class="img-fluid">
        </div>
    </div>
</div>
</div>


<div class="container mt-4">
<h2>Recommended Movies</h2>
<div class="row">
    <div class="col-md-3">
        <div class="poster-box">
            <img src="images/Look Away (2018).jpg" alt="movie 9" class="img-fluid">
        </div>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
            <img src="images/The Fall Guy (2024).jpg" alt="movie 10" class="img-fluid">
        </div>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
            <img src="images/Rebel Ridge (2024).jpg" alt="movie 11" class="img-fluid">
        </div>
    </div>
    <div class="col-md-3">
        <div class="poster-box">
            <img src="images/Rio (2011).jpg" alt="movie 12" class="img-fluid">
        </div>
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


   
</body>
</html>

    
    