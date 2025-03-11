<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "filmfuse_db";


try {
    // Create a new PDO instance and assign it to $filmfuse_db
    $filmfuse_db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);


    // Set error mode to exception
    $filmfuse_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $ex) {
   
    die("Failed to connect to the database: " . $ex->getMessage());
}


?>


