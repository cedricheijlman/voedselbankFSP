<?php
session_start();
if (!isset($_SESSION['soortgebruiker'])) {
  header("Location: login.php");
  exit();
}
require_once 'credentials.php';

$idleverancier = $_GET['idleverancier'];


$conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check voor info product
$stmtCheck = $conn->prepare('SELECT * FROM leverancier WHERE id_leverancier = ?');
$stmtCheck->execute([$idleverancier]);
$leverancier = $stmtCheck->fetch(PDO::FETCH_ASSOC);

// Toegang tot de gegevens
if ($leverancier) {
  $idleverancier = $leverancier['id_leverancier'];
  $bedrijfsnaam = $leverancier['bedrijfsnaam'];
  $postcode = $leverancier['postcode'];
  $huisnummer = $leverancier['huisnummer'];
  $plaats = $leverancier['plaats'];
  $email = $leverancier['email'];
  $telefoon = $leverancier['telefoon'];
} else {
  // Behandel het geval wanneer er geen product wordt gevonden met de gegeven streepjescode
}


// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Formuliergegevens ophalen
  $bedrijfsnaam = $_POST['bedrijfsnaam'];
  $postcode = $_POST['postcode'];
  $huisnummer = $_POST['huisnummer'];
  $plaats = $_POST['plaats'];
  $email = $_POST['email'];
  $telefoon = $_POST['telefoon'];


  try {

    // Gegevens invoegen in de gebruikerstabel

    $stmt = $conn->prepare('UPDATE leverancier SET bedrijfsnaam = ?, postcode = ?, huisnummer = ?, plaats = ?, email = ?, telefoon = ? WHERE id_leverancier = ?');
    $stmt->execute([$bedrijfsnaam, $postcode, $huisnummer, $plaats, $email, $telefoon, $idleverancier]);
    header("Location: leveranciers.php");
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
            <li onclick="location.href = 'leveranciers.php'" class="selected">
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
            <li onclick="location.href = 'gebruikers.php'">
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
      <h2>Wijzig Leverancier</h2>
      <form action="<?php echo 'wijzigleverancier.php?idleverancier=' . $idleverancier; ?>" method="POST">
        <p>Bedrijfsnaam</p>
        <input value="<?php echo $bedrijfsnaam ?>" placeholder="Bedrijfsnaam" type="text" name="bedrijfsnaam" required />

        <p>Postcode</p>
        <input placeholder="Postcode" value="<?php echo $postcode ?>" type="text" name="postcode" minlength="6" maxlength="6" required />

        <p>Huisnummer</p>
        <input placeholder="Huisnummer" type="number" value="<?php echo $huisnummer ?>" name="huisnummer" required />

        <p>Plaats</p>
        <input placeholder="Plaats" value="<?php echo $plaats ?>" name="plaats" required />

        <p>Email</p>
        <input placeholder="Email" value="<?php echo $email ?>" type="email" name="email" required />

        <p>Telefoon</p>
        <input placeholder="Telefoon" value="<?php echo $telefoon ?>" name="telefoon" required />


        <div class="formButtons">
          <button id="cancelKnop">
            <a href="leveranciers.php">Cancel</a>
          </button>
          <button id="voegKnop" type="submit">Wijzig Leverancier</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>