<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Movie info</title>
        <link rel="stylesheet" href="home.css">
        <link rel="stylesheet" href="movieinfo.css">
    </head>
    <body>
    <style>
            #reviews {
    margin-top: 20px;
    padding: 10px;
    border-top: 2px solid #ddd;
}


h3{
    text-align: left;
} 


p{
    text-align:left;
}
#review-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}


#review-text {
    width: 100%;
    padding: 8px;
}


#rating {
    width: 150px;
}


.review-item {
    margin-top: 10px;
    padding: 8px;
    background:rgb(12, 12, 12);
    border-radius: 5px;
}

        </style>

        <!--link to js-->
        <script src="sscript.js"></script>

        <button id="mode-toggle" onclick="toggleMode()">Switch Mode</button>

       
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
                <a href="basket.php">Basket</a>
                <a href="account.html">Accounts</a>
                <a href="contact.html">Contact us</a>

                <div id="search-container">
                    <input type="text" id="search-bar" placeholder="Search...">
                    <button id="search-button">Go</button>
                </div>
            </nav>
        </div>

        <main>
            <?php
                require_once("ffdbConn.php");

                if (isset($_GET['movie'])) {
                    $movieID = $_GET['movie'];
                }

                $stmt = $pdo->prepare("SELECT * FROM product WHERE pid = ?");
                $stmt->execute([$movieID]);
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
                    <h3><?php echo $synopsis ?></h3>
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
                </form>
                <br><br>
                <section id="trailer">
                    <iframe src="<?=$result['p_Trailer']?>"></iframe>
                <form id="review-form" method="POST" action="submitReview.php">
                <label for="review-text">Leave a review:</label>
                <textarea id="review-text" name="review-text" rows="4" placeholder="Write your review here..."></textarea>
                
                <label for="rating">Rating:</label>
                <select id="rating" name="rating">
                    <option value="5">★★★★★ (5 stars)</option>
                    <option value="4">★★★★☆ (4 stars)</option>
                    <option value="3">★★★☆☆ (3 stars)</option>
                    <option value="2">★★☆☆☆ (2 stars)</option>
                    <option value="1">★☆☆☆☆ (1 star)</option>
                </select>

                <input type="hidden" name="movie_id" value="<?php echo $movieID; ?>">
                <button type="submit">Submit Review</button>
            </form>
            <?php
            $stmt = $pdo->prepare("SELECT * FROM reviews WHERE product_id = ?");
            $stmt->execute([$movieID]);
            $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div id="review-list">
                <?php if (count($reviews) > 0): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="review-item">
                            <p><strong>Rating:</strong> <?php echo str_repeat("★", $review['rating']) . str_repeat("☆", 5 - $review['rating']); ?></p>
                            <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                            <small>Posted on: <?php echo $review['created_at']; ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No reviews yet. Be the first to review!</p>
                <?php endif; ?>
            </div>

                </section>
            </div>
        </main>
        <footer></footer>
    </body>
</html>