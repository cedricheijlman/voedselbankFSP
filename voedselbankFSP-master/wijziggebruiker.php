<?php
session_start();
require_once 'credentials.php';

$gebruikerId = $_GET['gebruikerid'];


$conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check voor info product
$stmtCheck = $conn->prepare('SELECT * FROM gebruiker WHERE id_gebruiker = ?');
$stmtCheck->execute([$gebruikerId]);
$gebruiker = $stmtCheck->fetch(PDO::FETCH_ASSOC);

// Toegang tot de gegevens
if ($gebruiker) {
  $gebruikerId = $gebruiker['id_gebruiker'];
  $soortgebruiker = $gebruiker['id_soortgebruiker'];
  $naam = $gebruiker['naam'];
  $achternaam = $gebruiker['achternaam'];
  $gebruikersnaam = $gebruiker['gebruikersnaam'];
  $email = $gebruiker['email'];
  $wachtwoord = $gebruiker['wachtwoord'];
  $toegang = $gebruiker['toegang'];
} else {
  // Behandel het geval wanneer er geen product wordt gevonden met de gegeven streepjescode
}


// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Formuliergegevens ophalen
  $soortgebruiker = $_POST['soortgebruiker'];
  $naam = $_POST['naam'];
  $achternaam = $_POST['achternaam'];
  $gebruikersnaam = $_POST['gebruikersnaam'];
  $email = $_POST['email'];
  $toegang = $_POST['toegang'];

  if ($_POST['wachtwoord'] != "" && strlen($_POST['wachtwoord']) >= 8) {
    $wachtwoord = password_hash($_POST['wachtwoord'], PASSWORD_DEFAULT);
  }




  try {

    // Gegevens van gebruiker wijzigen
    $stmt = $conn->prepare('UPDATE gebruiker SET id_soortgebruiker = ?, naam = ?, achternaam = ?, gebruikersnaam = ?, email = ?, wachtwoord = ?, toegang = ? WHERE id_gebruiker = ?');
    $stmt->execute([$soortgebruiker, $naam, $achternaam, $gebruikersnaam, $email, $wachtwoord, $toegang, $gebruikerId]);
    header("Location: gebruikers.php");
  } catch (PDOException $e) {
    $_SESSION['registratieErrror'] = 'Registratie mislukt';
    echo  $e;
    exit;
  }

  // Databaseverbinding sluiten
  $pdo = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="./styles/voegproduct.css" />
  <link rel="stylesheet" href="./styles/navbar.css" />
  <link rel="stylesheet" href="./styles/layout.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div class="navbar">
      <h2>Maaskantje Paneel</h2>
      <div class="navbarListContainer">
      <ul>
          <li onclick="location.href = 'homepage.php'">
            <i class="fa-solid fa-house"></i>
            <p>Home</p>
          </li>
          <?php if ($_SESSION['soortgebruiker'] == 1 || $_SESSION['soortgebruiker'] == 2 || $_SESSION['soortgebruiker'] == 3): ?>
            <li onclick="location.href = 'productvoorraad.php'">
              <i class="fa-solid fa-shop"></i>
              <p>Productvoorraad</p>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION['soortgebruiker'] == 1 || $_SESSION['soortgebruiker'] == 3): ?>
            <li>
              <i class="fa-solid fa-bag-shopping"></i>
              <p>Voedselpakketten</p>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION['soortgebruiker'] == 1 || $_SESSION['soortgebruiker'] == 2): ?>
            <li onclick="location.href = 'leveranciers.php'">
              <i class="fa-solid fa-truck-field"></i>
              <p>Leveranciers</p>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION['soortgebruiker'] == 1): ?>
            <li>
              <i class="fa-solid fa-users"></i>
              <p>Klanten</p>
            </li>
            <li>
              <i class="fa-solid fa-chart-simple"></i>
              <p>Maand Overzicht</p>
            </li>
            <li onclick="location.href = 'gebruikers.php'" class="selected">
              <i class="fa-solid fa-user-friends"></i>
              <p>Gebruikers</p>
            </li>
          <?php endif; ?>
          <li onclick="location.href = 'account.php'">
            <i class="fa-regular fa-circle-user"></i>
            <p>Account</p>
          </li>
        </ul>
      </div>
      <div class="uitlogContainer">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        <p>Uitloggen</p>
      </div>
    </div>
    <div class="rechts">
      <h2>Wijzig Gebruiker</h2>
      <form action="<?php echo 'wijziggebruiker.php?gebruikerid=' . $gebruikerId; ?>" method="POST">
        <p>Naam</p>
        <input readonly value="<?php echo $naam ?>" placeholder="Naam" type="text" name="naam" />
        <p>Achternaam</p>
        <input placeholder="Achternaam" required value="<?php echo $achternaam ?>" name="achternaam" />
        <p>Gebruikersnaam</p>
        <input placeholder="Productnaam" required value="<?php echo $gebruikersnaam ?>" name="gebruikersnaam" />
        <p>Email</p>
        <input placeholder="Productnaam" required value="<?php echo $email ?>" name="email" />
        <p>Wachtwoord</p>
        <input placeholder="Wijzig wachtwoord" type="password" name="wachtwoord" />
        <p>Soort Gebruiker</p>
        <select name="soortgebruiker" required value="<?php echo $soortgebruiker ?>">
          <option value="3">Vrijwilliger</option>
          <option value="2">Magazijnmedewerker</option>
          <option value="1">Directeur</option>
        </select>
        <p>Toegang?</p>
        <select name="toegang" required value="<?php echo $toegang ?>">
          <option value="1">Ja</option>
          <option value="0">Nee</option>
        </select>
        <div class="formButtons">
          <button id="cancelKnop">
            <a href="productvoorraad.php">Cancel</a>
          </button>
          <button id="voegKnop" type="submit">Wijzig Gebruiker</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>