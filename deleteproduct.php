<?php
require_once 'credentials.php';

try {
  // Haal het product-ID op uit de URL-parameter
  $streepjescode = $_GET['streepjescode'];

  // Maak verbinding met de database
  $conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Bereid de SQL DELETE-instructie voor en voer deze uit
  $stmt = $conn->prepare('DELETE FROM product WHERE streepjescode = ?');
  $stmt->execute([$streepjescode]);

  // Verwijs terug naar de productenlijstpagina
  header("Location: productvoorraad.php");
  exit;
} catch (PDOException $e) {
  // Behandel de uitzondering, toon een foutmelding of log de fout
  echo 'Fout bij het verwijderen van het product: ' . $e->getMessage();
  exit;
}
