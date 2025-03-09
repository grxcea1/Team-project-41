<?php
header("Content-Type: application/json");
require "db_connect.php";

if (!isset($_GET["id"])) {
    echo json_encode(["error" => "Movie ID is required"]);
    exit;
}

$movie_id = intval($_GET["id"]); 

$sql = "SELECT name, price FROM product WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "Movie not found"]);
}

$stmt->close();
$conn->close();
?>
