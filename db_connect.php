<?php
$host = "localhost";
$username = "root";
$password = ""; // For the empty password when deployed
$database = "filmfuse_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    $_SESSION["faileddatabaseconn"];
    header("Location: failedconnection.php");
    exit;
}
?>
