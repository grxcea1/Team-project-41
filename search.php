<?php
$host = 'localhost'; 
$dbname = 'filmfuse_db';
$username = 'root';
$password = '';


$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$search_term = isset($_GET['search_query']) ? $_GET['search_query'] : '';


$search_term = $conn->real_escape_string($search_term);


$query = "SELECT p_Name FROM product WHERE p_Name LIKE '%$search_term%' LIMIT 10";


$result = $conn->query($query);
$suggestions = [];

if ($result->num_rows > 0) {
    
    while($row = $result->fetch_assoc()) {
        $suggestions[] = $row['p_Name'];
    }
    
    echo json_encode($suggestions);
} else {
   
    echo json_encode([]);
}

// Close the connection
$conn->close();
?>


