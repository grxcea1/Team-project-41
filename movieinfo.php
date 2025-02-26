<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Movie info</title>
        <link rel="stylesheet" href="home.css">
        <link rel="stylesheet" href="movieinfo.css">
    </head>
    <body>
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
                <a href="home.html">Home</a>
                <a href="ffLoginPage.php">Login</a>
                <a href="aboutus.html">About Us</a>
                <a href="basket.html">Basket</a>
                <a href="account.html">Accounts</a>
                <a href="contact.html">Contact us</a>
            </nav>
        </div>

        <main>
            <?php
                require_once("ffdbConn.php");

                if (isset($_GET['movie'])) {
                    $movieID = $_GET['movie'];
                }

                $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = $movieID");
                $stmt->execute();
                $result = $stmt->fetch();
                $imgpath = $result['p_Image'];
                $name = $result['p_Name'];
                $synopsis = $result['p_Description'];
                $buyprice = $result['p_Price'];
                $rentprice = $result['p_RentPrice'];
                $stock = $result['p_Stock'];
                $release = $result['p_ReleaseDate'];
                $duration = $result['p_Duration'];
                $genre = $result['categoryID'];
                $age = $result['p_ageRating'];
                $director = $result['p_Director'];
                $starring = $result['p_Starring'];

                $genreList = [
                    1 => 'Action',
                    2 => 'Animation',
                    3 => 'Romance',
                    4 => 'Comedy',
                    5 => 'Thriller',
                    6 => 'Horror',
                ];

                if ($result['pid'] == 2) {
                    $stock = 0;
                }

                if ($stock == 0) {
                    echo '<script>alert("This movie is out of stock")</script>';
                }
            ?>

            <div class="movie-pic">
                <img src='<?php echo $imgpath; ?>' alt='movie 12' class='img-fluid'>
            </div>

            <div id="description">
                <h2><?php echo $name ?></h2>
                <br>
                <section id="synopsis">
                    <p><?php echo $synopsis ?></p>
                    <br>
                </section>
                <section id="buy-info">
                    <p>
                        <?php
                            echo "Full Price: £" . $buyprice . "<br>
                            Rent Price: £" . $rentprice . "<br>
                            Stock: " . $stock;
                        ?>
                    </p>
                    <br>
                </section>
                <section id="extra-info">
                    <p>
                        <?php
                            echo "Release date: " . $release . "<br>
                            Duration: " . $duration . "<br>
                            Genre: " . $genreList[$genre] . "<br>
                            Age rating: " . $age . " <br>
                            Director: " . $director . "<br>
                            Starring: " . $starring;
                        ?>
                    </p>
                </section>
                <br>
                <form>
                    <input type="checkbox" id="add-cart">
                    <label for="add-cart">Add to cart</label>
                    <input type="checkbox" id="add-wish">
                    <label for="add-wish">Add to wishlist</label>
                </form>
                <br><br>
                <section id="trailer">
                    <iframe src="https://www.youtube.com/embed/73_1biulkYk"></iframe>
                </section>
            </div>
        </main>
        <footer></footer>
    </body>
</html>