<?php
session_start();
$_SESSION['gebruikerAanmaken'] =  "";

require_once 'credentials.php';


// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Formuliergegevens ophalen
  $voornaam = $_POST['voornaam'];
  $achternaam = $_POST['achternaam'];
  $gebruikersnaam = $_POST['gebruikersnaam'];
  $email = $_POST['email'];
  $wachtwoord = $_POST['wachtwoord'];
  $soortgebruiker = $_POST['soortgebruiker'];
  $toegang = $_POST['toegang'];

  // Wachtwoord hashen
  $hashedPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);

  $_SESSION['registratieError'] =  "";


  try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Controleren of de gebruikersnaam of e-mail al in gebruik zijn
    $stmt = $conn->prepare('SELECT COUNT(*) FROM gebruiker WHERE gebruikersnaam = ? OR email = ?');
    $stmt->execute([$gebruikersnaam, $email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
      $_SESSION['registratieError'] = 'Gebruikersnaam of e-mail is al in gebruik';
    }

    // Gegevens invoegen in de gebruikerstabel
    if ($_SESSION['registratieError'] == "") {
      $stmt = $conn->prepare('INSERT INTO gebruiker (naam, achternaam, gebruikersnaam, email, wachtwoord, id_soortgebruiker, toegang) VALUES (?, ?, ?, ?, ?, ?, ?)');
      $stmt->execute([$voornaam, $achternaam, $gebruikersnaam, $email, $hashedPassword, $soortgebruiker, $toegang]);
      header('Location: gebruikers.php');
    };
  } catch (PDOException $e) {
    $_SESSION['registratieErrror'] = 'Registratie mislukt';
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
          <li class="selected" onclick="location.href = 'gebruikers.php'">
            <i class="fa-solid fa-user-group"></i>
            <p>Gebruikers</p>
          </li>
          <li onclick="location.href = 'productvoorraad.php'">
            <i class="fa-solid fa-shop"></i>
            <p>Productvoorraad</p>
          </li>
          <li>
            <i class="fa-solid fa-bag-shopping"></i>
            <p>Voedselpakketten</p>
          </li>
          <li>
            <i class="fa-solid fa-users"></i>
            <p>Klanten</p>
          </li>
          <li onclick="location.href = 'leveranciers.php'">
            <i class="fa-solid fa-truck-field"></i>
            <p>Leveranciers</p>
          </li>
          <li>
            <i class="fa-solid fa-chart-simple"></i>
            <p>Maand Overzicht</p>
          </li>
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
      <h2>Voeg Gebruiker Toe</h2>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <p>Gebruiker Informatie</p>
        <input placeholder=" Naam" name="voornaam" type="text" required />
        <input placeholder="Achternaam" name="achternaam" type="text" required />
        <input placeholder="Gebruikersnaam" name="gebruikersnaam" type="text" required />
        <input placeholder="Email" name="email" type="email" required />
        <input placeholder="Wachtwoord" type="password" pattern=".{8,}" minlength="8" name="wachtwoord" required />
        <label>Soort gebruiker</label>

        <select name="soortgebruiker">
          <option value="3">Vrijwilliger</option>
          <option value="2">Magazijnmedewerker</option>
          <option value="1">Directeur</option>
        </select>
        <label>Toegang?</label>

        <select name="toegang">
          <option value="1">Ja</option>
          <option value="0">Nee</option>
        </select>
        <div class="formButtons">
          <button id="cancelKnop">
            <a href="gebruikers.php">Cancel</a>
          </button>
          <button id="voegKnop" type="submit">Voeg Gebruiker</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>