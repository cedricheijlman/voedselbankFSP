<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start();
$_SESSION['registratieError'] =  "";
// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Formuliergegevens ophalen
  $voornaam = $_POST['voornaam'];
  $achternaam = $_POST['achternaam'];
  $gebruikersnaam = $_POST['gebruikersnaam'];
  $email = $_POST['email'];
  $wachtwoord = $_POST['wachtwoord'];

  // Wachtwoord hashen
  $hashedPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);

  // Een PDO databaseverbinding maken
  $host = '127.0.0.1';
  $dbname = 'voedselbank';
  $username = 'root';
  $password = '12345678';
  $port = '3306';

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
      $stmt = $conn->prepare('INSERT INTO gebruiker (naam, achternaam, gebruikersnaam, email, wachtwoord) VALUES (?, ?, ?, ?, ?)');
      $stmt->execute([$voornaam, $achternaam, $gebruikersnaam, $email, $hashedPassword]);
    };
    header('Location: homepage.html');
    exit;
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
  <link rel="stylesheet" href="./styles/login.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div class="links">
      <h1 class="applicatieNaam">Applicatie Naam</h1>

      <form class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <h2 class="formTitel">Maak een account</h2>
        <div class="formInput">
          <label for="voornaam">Voornaam</label>
          <input type="text" id="voornaam" name="voornaam" required />
        </div>
        <div class="formInput">
          <label for="achternaam">Achternaam</label>
          <input type="text" id="achternaam" name="achternaam" required />
        </div>
        <div class="formInput">
          <label for="gebruikersnaam">Gebruikersnaam</label>
          <input type="text" id="gebruikersnaam" name="gebruikersnaam" required />
        </div>
        <div class="formInput">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required />
        </div>
        <div class="formInput">
          <label for="wachtwoord">Wachtwoord (min. 8 tekens)</label>
          <input type="password" id="wachtwoord" name="wachtwoord" minlength="8" required />
        </div>
        <button type="submit" class="knop">Registreren</button>
        <?php echo '<p class="error">' . $_SESSION['registratieError'] . '</p>';  ?>
      </form>

      <p class="geenAccount">
        Al een account? <span><a href="./login.php">Inloggen</a></span>
      </p>
    </div>
    <div class="rechts"></div>
  </div>
</body>

</html>