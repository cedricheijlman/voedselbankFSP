<?php
session_start();
$_SESSION['loginError'] = "";

// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Formuliergegevens ophalen
  $email = $_POST['email'];
  $wachtwoord = $_POST['wachtwoord'];

  // Een PDO databaseverbinding maken
  include_once 'credentials.php';

  try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Gebruikersgegevens ophalen op basis van de gebruikersnaam
    $stmt = $conn->prepare('SELECT * FROM gebruiker WHERE email = ?');
    $stmt->execute([$email]);
    $gebruiker = $stmt->fetch();

    if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
      if ($gebruiker['toegang'] == 1) {
        // Inloggen is gelukt
        $_SESSION['voornaam'] = $gebruiker['naam'];
        $_SESSION['achternaam'] = $gebruiker['achternaam'];
        $_SESSION['gebruikersnaam'] = $gebruiker['gebruikersnaam'];
        $_SESSION['wachtwoord'] = $gebruiker['wachtwoord'];
        $_SESSION['email'] = $gebruiker['email'];
        $_SESSION['soortgebruiker'] = $gebruiker['id_soortgebruiker'];
        header('Location: homepage.php');
        exit;
      } else if ($gebruiker['toegang'] == 0) {
        $_SESSION['loginError'] = 'Geen toegang tot account!';
      }
    } else {
      $_SESSION['loginError'] = 'Ongeldige gebruikersnaam of wachtwoord';
    }
  } catch (PDOException $e) {
    $_SESSION['loginError'] = 'Inloggen mislukt, probeer opnieuw!';
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
          <label for="wachtwoord">Wachtwoord</label>
          <input type="password" id="wachtwoord" name="wachtwoord" required />
        </div>
        <button type="submit" class="knop">Inloggen</button>
        <p class="wachtwoordVergeten">Wachtwoord vergeten?</p>
        <p class="error"><?php echo $_SESSION['loginError'] ?></p>
      </form>
      <p class="geenAccount">
        Nog geen account?
        <span><a href="./registratie.php">Registreer</a></span>
      </p>
    </div>
    <div class="rechts"></div>
  </div>
</body>

</html>