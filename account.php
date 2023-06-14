<?php
require_once 'credentials.php';
session_start();
$_SESSION['errorAccount'] =  "";

// account verwijderen
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modalButton'])) {
  $conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Controleren of de gebruikersnaam of e-mail al in gebruik zijn
  $stmt = $conn->prepare('DELETE FROM gebruiker WHERE gebruikersnaam = ?');
  $stmt->execute([$_SESSION['gebruikersnaam']]);
  $count = $stmt->fetchColumn();
  header('Location: login.php');
  exit;
}

// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Formuliergegevens ophalen
  $naam = $_POST['naam'];
  $achternaam = $_POST['achternaam'];
  $gebruikersnaam = $_POST['gebruikersnaam'];
  $email = $_POST['email'];
  $wachtwoord = $_POST['wachtwoord'];

  // Wachtwoord hashen
  $hashedPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);

  $_SESSION['errorAccount'] =  "";


  try {
    $voornaamChanged = false;
    $achternaamChanged = false;
    $usernameChanged = false;
    $emailChanged = false;
    $passwordChanged = false;

    $conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Controleren of de gebruikersnaam of e-mail al in gebruik zijn
    $stmt = $conn->prepare('SELECT COUNT(*) FROM gebruiker WHERE gebruikersnaam = ? OR email = ?');
    $stmt->execute([$gebruikersnaam, $email]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
      $_SESSION['errorAccount'] = 'Gebruikersnaam of e-mail is al in gebruik';
    }

    if ($naam !== "") {
      $query = 'UPDATE gebruiker SET naam = ? WHERE gebruikersnaam = ?';
      $stmt = $conn->prepare($query);
      $stmt->execute([
        $naam,
        $_SESSION['gebruikersnaam']
      ]);
      $_SESSION['voornaam'] = $naam;
      $voornaamChanged = true;
    }

    if ($achternaam !== "") {
      $query = 'UPDATE gebruiker SET achternaam = ? WHERE gebruikersnaam = ?';
      $stmt = $conn->prepare($query);
      $stmt->execute([
        $achternaam,
        $_SESSION['gebruikersnaam']
      ]);

      $_SESSION['achternaam'] = $achternaam;
      $achternaamChanged = true;
    }

    if ($gebruikersnaam !== "" && $_SESSION['errorAccount'] == "") {
      $query = 'UPDATE gebruiker SET gebruikersnaam = ? WHERE gebruikersnaam = ?';
      $stmt = $conn->prepare($query);
      $stmt->execute([
        $gebruikersnaam,
        $_SESSION['gebruikersnaam']
      ]);

      $_SESSION['gebruikersnaam'] = $gebruikersnaam;
      $usernameChanged = true;
    }


    if ($email !== "" && $_SESSION['errorAccount'] == "") {
      $query = 'UPDATE gebruiker SET email = ? WHERE gebruikersnaam = ?';
      $stmt = $conn->prepare($query);
      $stmt->execute([
        $email,
        $_SESSION['gebruikersnaam']
      ]);
      $_SESSION['email'] = $email;
      $emailChanged = true;
    }
    if ($wachtwoord !== "") {
      $query = 'UPDATE gebruiker SET wachtwoord = ? WHERE gebruikersnaam = ?';
      $stmt = $conn->prepare($query);

      $stmt->execute([
        password_hash($wachtwoord, PASSWORD_DEFAULT),
        $_SESSION['gebruikersnaam']
      ]);
      $_SESSION['$wachtwoord'] = password_hash($wachtwoord, PASSWORD_DEFAULT);
      $passwordChanged = true;
    }
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
  <link rel="stylesheet" href="./styles/homepage.css" />
  <link rel="stylesheet" href="./styles/layout.css" />
  <link rel="stylesheet" href="./styles/navbar.css" />
  <link rel="stylesheet" href="./styles/account.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div id="overlay" class="overlay">
      <form class="overlayForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <h2>Delete Account</h2>
        <p>Are you sure you want to delete your account?</p>
        <div class="overlayButtonsContainer">
          <a onclick="hideModal()">No</a>
          <button type="submit" name="modalButton">Yes</button>
        </div>
      </form>
    </div>
    <div class="navbar">
      <h2>Maaskantje Paneel</h2>
      <div class="navbarListContainer">
        <ul>
          <li onclick="location.href = 'homepage.php'">
            <i class="fa-solid fa-house"></i>
            <p>Home</p>
          </li>
          <li onclick="location.href = 'gebruikers.php'">
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
          <li class="selected" onclick="location.href = 'account.php'">
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
      <h2>Accountgegevens</h2>
      <form class="accountForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label>Verander Naam</label>
        <input name="naam" />
        <?php if ($voornaamChanged == true) echo "<p>Veranderd</p>"; ?>
        <label>Verander Achternaam</label>
        <input name="achternaam" />
        <?php if ($achternaamChanged == true) echo "<p>Veranderd</p>"; ?>
        <label>Verander Gebruikersnaam</label>
        <input name="gebruikersnaam" />
        <?php if ($usernameChanged == true) echo "<p>Verander</p>"; ?>
        <label>Verander Email</label>
        <input name="email" />
        <?php if ($emailChanged == true) echo "<p>Veranderd</p>"; ?>
        <label>Verander Wachtwoord</label>
        <input name="wachtwoord" />
        <?php if ($passwordChanged == true) echo "<p>Veranderd</p>"; ?>
        <div class="buttons">
          <button id="verwijderAccountKnop" type="button" onclick="showModal()">Verwijder Account</button>
          <button id="veranderGegevensKnop" type="submit">Verander Gegevens</button>
        </div>
      </form>
      <p class="error"><?php echo $_SESSION['errorAccount'] ?></p>
    </div>
  </div>
  <script>
    function showModal() {
      document.getElementById("overlay").style.display = "flex";
    }

    function hideModal() {
      document.getElementById("overlay").style.display = "none";
    }
    window.onclick = function(event) {
      let modal = document.getElementById("overlay");
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
  </script>
</body>

</html>