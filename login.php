<?php
// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Formuliergegevens ophalen
  $gebruikersnaam = $_POST['gebruikersnaam'];
  $wachtwoord = $_POST['wachtwoord'];

  // Een PDO databaseverbinding maken
  $host = 'localhost';
  $dbname = 'voedselbank';
  $username = 'root';
  $password = '123456';

  try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Gebruikersgegevens ophalen op basis van de gebruikersnaam
    $stmt = $conn->prepare('SELECT id_gebruiker, wachtwoord FROM gebruiker WHERE gebruikersnaam = ?');
    $stmt->execute([$gebruikersnaam]);
    $gebruiker = $stmt->fetch();

    if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
      // Inloggen is gelukt, sla gebruikersnaam en id op in de sessie
      $_SESSION['gebruikersnaam'] = $gebruikersnaam;
      $_SESSION['gebruiker_id'] = $gebruiker['id_gebruiker'];

      header('Location: homepage.php'); // Gebruiker doorsturen naar homepage.php
      exit;
    } else {
      echo 'Ongeldige gebruikersnaam of wachtwoord';
      echo '<br>';
      echo '<a href="login.html">Klik hier om opnieuw in te loggen</a>';
      exit;
    }
  } catch (PDOException $e) {
    echo 'Inloggen mislukt<br>';
    echo '<a href="login.html">Klik hier om opnieuw te proberen</a>';
  }

  // Databaseverbinding sluiten
  $conn = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="./styles/login.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div class="links">
      <h1 class="applicatieNaam">Applicatie Naam</h1>

      <form class="form" action="login.php" method="POST">
        <h2 class="formTitel">Inloggen</h2>
        <div class="formInput">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="formInput">
          <label for="password">Wachtwoord</label>
          <input type="password" id="password" name="password" required />
        </div>
        <button type="submit" class="knop">Inloggen</button>
        <p class="wachtwoordVergeten">Wachtwoord vergeten?</p>
      </form>

      <p class="geenAccount">
        Nog geen account?
        <span><a href="./registratie.html">Registreer</a></span>
      </p>
    </div>
    <div class="rechts"></div>
  </div>
</body>

</html>