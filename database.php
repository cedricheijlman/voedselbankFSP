<?php
$host = '127.0.0.1';
$dbname = 'voedselbank';
$username = 'root';
$password = '12345678';
$port = '3306';

try {
  // Create a new PDO instance
  $conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);

  // Set PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  echo 'Connected successfully';
} catch (PDOException $e) {
  die('Connection failed: ' . $e->getMessage());
}

// Close the connection
$conn = null;
