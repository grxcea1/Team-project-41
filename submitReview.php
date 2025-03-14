<?php
require_once("ffdbConn.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["review-text"]) && !empty($_POST["rating"]) && !empty($_POST["movie_id"])) {
        $review_text = trim($_POST["review-text"]);
        $rating = (int) $_POST["rating"];
        $movie_id = (int) $_POST["movie_id"];

        $stmt = $pdo->prepare("INSERT INTO reviews (product_id, review_text, rating) VALUES (?, ?, ?)");
        $stmt->execute([$movie_id, $review_text, $rating]);

        header("Location: movieinfo.php?movie=$movie_id");
        exit();
    } else {
        echo "<script>alert('Please fill out all fields correctly.'); window.history.back();</script>";
    }
}
?>
