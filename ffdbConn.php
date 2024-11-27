<?php
 $host = "localhost";
 $username ="root";
 $password = "";
 $dbname = "filmfuse_db";

 
try{

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
}catch(PDOException $ex){
    echo("Failed to connect to the database. <br>");
    echo($ex->getMessage());
    exit;
}

?>