<?php
// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Formuliergegevens ophalen
  $gebruikersnaam = $_POST['gebruikersnaam'];
  $email = $_POST['email'];
  $wachtwoord = $_POST['wachtwoord'];

  // Wachtwoord hashen
  $hashedPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);

  // Een PDO databaseverbinding maken
  $host = 'localhost';
  $dbname = 'voedselbank';
  $username = 'root';
  $password = '123456';



  try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Controleren of de gebruikersnaam of e-mail al in gebruik zijn
    $stmt = $conn->prepare('SELECT COUNT(*) FROM gebruiker WHERE gebruikersnaam = ? OR email = ?');
    $stmt->execute([$gebruikersnaam, $email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
      echo 'Gebruikersnaam of e-mail is al in gebruik';
      echo '<br>';
      echo '<a href="registratie.html">Klik hier om het opnieuw te proberen</a>';
      exit;
    }

    // Gegevens invoegen in de gebruikerstabel
    $stmt = $pdo->prepare("INSERT INTO gebruiker (gebruikersnaam, email, wachtwoord) VALUES (?, ?, ?)");
    $stmt->execute([$gebruikersnaam, $email, $hashedPassword]);

    echo 'Registratie succesvol';
    header('Location: homepage.php'); // Gebruiker doorsturen naar homepage.php
    exit;
  } catch (PDOException $e) {
    echo 'Registratie mislukt<br>';
    echo '<a href="registratie.html">Klik hier om het opnieuw te proberen</a>';
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

      <form class="form" action="registratie.php" method="POST">
        <h2 class="formTitel">Maak een account</h2>
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
      </form>

      <p class="geenAccount">
        Al een account? <span><a href="./login.html">Inloggen</a></span>
      </p>
    </div>
    <div class="rechts"></div>
  </div>
</body>

</html>