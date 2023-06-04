<?php
$servername = "localhost"; // Replace with your database server name if necessary
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "voedselbank";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    // Attempt connection with empty password if the provided password is incorrect
    $conn = new mysqli($servername, $username, '123456', $dbname);
    
    // Check the connection again
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

echo "Connected successfully";

// Close the connection
$conn->close();
?>
