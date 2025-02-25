<?php
// Connection parameters
$host = 'localhost'; // or other host
$dbname = 'filmfuse_db';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term from the URL
$search_term = isset($_GET['search_query']) ? $_GET['search_query'] : '';

// Protect against SQL injection
$search_term = $conn->real_escape_string($search_term);

// SQL query to perform the search, focusing on quick, limited results
$query = "SELECT p_Name FROM product WHERE p_Name LIKE '%$search_term%' LIMIT 10";

// Execute the query
$result = $conn->query($query);
$suggestions = [];

if ($result->num_rows > 0) {
    // Collect all product names for suggestions
    while($row = $result->fetch_assoc()) {
        $suggestions[] = $row['p_Name'];
    }
    // Output the data in JSON format
    echo json_encode($suggestions);
} else {
    // Return an empty JSON array if no results
    echo json_encode([]);
}

// Close the connection
$conn->close();
?>
