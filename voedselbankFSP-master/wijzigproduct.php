<?php
session_start();
require_once 'credentials.php';

$streepjescode = $_GET['streepjescode'];


$conn = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check voor info product
$stmtCheck = $conn->prepare('SELECT * FROM product WHERE streepjescode = ?');
$stmtCheck->execute([$streepjescode]);
$product = $stmtCheck->fetch(PDO::FETCH_ASSOC);

// Toegang tot de gegevens
if ($product) {
  $streepjescode = $product['streepjescode'];
  $productnaam = $product['naam'];
  $categorie = $product['categorie_id'];
  $aantal = $product['aantal'];
} else {
  // Behandel het geval wanneer er geen product wordt gevonden met de gegeven streepjescode
  header("Location: productvoorraad.php");
}


// Controleren of het formulier is ingediend
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Formuliergegevens ophalen
  $streepjescode = $_POST['streepjescode'];
  $productnaam = $_POST['productnaam'];
  $categorie = $_POST['categorie'];
  $aantal = $_POST['aantal'];


  try {

    // Gegevens invoegen in de gebruikerstabel

    $stmt = $conn->prepare('UPDATE product SET aantal = ?, categorie_id = ? WHERE streepjescode = ?');
    $stmt->execute([$aantal, $categorie, $streepjescode]);
    header("Location: productvoorraad.php");
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
            <li onclick="location.href = 'productvoorraad.php'" class="selected">
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
      <h2>Wijzig Product</h2>
      <form action="<?php echo 'wijzigproduct.php?streepjescode=' . $streepjescode; ?>" method="POST">
        <p>Streepjescode</p>
        <input readonly value="<?php echo $streepjescode ?>" placeholder="Streepjescode" type="number" name="streepjescode" />
        <p>Product Naam</p>
        <input readonly placeholder="Productnaam" required value="<?php echo $productnaam ?>" name="productnaam" />
        <p>Product Categorie</p>
        <select name="categorie" required value="<?php echo $categorie ?>">
          <option value="1">Aardappelen, groente, fruit</option>
          <option value="2">Kaas, vleeswaren</option>
          <option value="3">Zuivel, plantaardig en eieren</option>
          <option value="4">Bakkerij en banket</option>
          <option value="5">Frisdrank, sappen, koffie en thee</option>
          <option value="6">Pasta, rijst en wereldkeuken</option>
          <option value="7">Snoep, koek, chips en chocolade</option>
          <option value="8">Baby, verzorging en hygiene</option>
        </select>
        <p>Aantal in voorrraad</p>
        <input placeholder="Voorraad" required type="number" value="<?php echo $aantal ?>" name="aantal" />
        <div class="formButtons">
          <button id="cancelKnop">
            <a href="productvoorraad.php">Cancel</a>
          </button>
          <button id="voegKnop" type="submit">Wijzig Product</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>