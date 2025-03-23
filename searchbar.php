<?php
require_once('ffdbConn.php');
if (isset($_POST['search'])) {
    $searchKeyword = $_POST['searchKeyword'];
    $query = "SELECT * FROM product WHERE p_Name LIKE ?";
    $stat = $pdo->prepare($query);
    $stat->execute(["%$searchKeyword%"]);
    if ($stat->rowCount() > 0) {
        echo "<h2>Search Results</h2>";
        while ($row = $stat->fetch(PDO::FETCH_ASSOC)) {
            echo "<p>Movies: " . $row['p_Name'] . "</p>";
        }
    } else {
        echo "<p>No movies found matching the search criteria.</p>";
    }
}
?>
