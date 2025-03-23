<?php
$host = "localhost";
$username = "root";
$password = ""; // For the empty password when deployed
$database = "filmfuse_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}
?>
