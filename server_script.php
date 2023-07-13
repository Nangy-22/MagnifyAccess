<?php
// Database configuration
$hostname = "localhost";
$username = "admin";
$password = "password";
$database = "magnifyaccess";

// Create a new MySQLi object and establish the connection
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check for connection errors
if ($mysqli->connect_errno) {
  die("Connection failed: " . $mysqli->connect_error);
}

// Query to retrieve data from the database
$query = "SELECT * FROM employee_data";

// Perform the query
$result = $mysqli->query($query);

// Check for query errors
if (!$result) {
  die("Query failed: " . $mysqli->error);
}

// Fetch the data from the result set
$data = $result->fetch_all(MYSQLI_ASSOC);

// Close the database connection
$mysqli->close();

// Set the response header to JSON
header('Content-Type: application/json');

// Return the data as JSON
echo json_encode($data);
