<?php
require_once 'credentials.php';

try {
  // Haal het product-ID op uit de URL-parameter
  $idleverancier = $_GET['idleverancier'];

  // Maak verbinding met de database
  $conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Bereid de SQL DELETE-instructie voor en voer deze uit
  $stmt = $conn->prepare('DELETE FROM leverancier WHERE id_leverancier = ?');
  $stmt->execute([$idleverancier]);

  // Verwijs terug naar de productenlijstpagina
  header("Location: leveranciers.php");
  exit;
} catch (PDOException $e) {
  // Behandel de uitzondering, toon een foutmelding of log de fout
  echo 'Fout bij het verwijderen van gebruiker: ' . $e->getMessage();
  exit;
}
