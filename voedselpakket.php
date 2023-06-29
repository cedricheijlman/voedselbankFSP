<?php
// MySQL database configuration
require_once 'credentials.php';

$voedselbankID = $_GET['id'];

try {
  // Create a PDO connection
  $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
  $pdo = new PDO($dsn, $username, $password);
  // Query to retrieve all rows from the "gebruiker" table
  $sql = "SELECT voedselpakket.*, klant.* FROM voedselpakket JOIN klant ON voedselpakket.klant_id = klant.id_klant WHERE voedselpakket.pakket_nr = ?";
  // Execute the query and fetch all rows
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$voedselbankID]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $sqlTwee = "SELECT productenlijst.*, product.naam  FROM productenlijst JOIN product ON productenlijst.streepjescode = product.streepjescode WHERE pakket_nr = ?";

  $stmtTwee = $pdo->prepare($sqlTwee);
  $stmtTwee->execute([$voedselbankID]);
  $rows = $stmtTwee->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die('Database query failed: ' . $e->getMessage());
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="./styles/productinfo.css" />
  <link rel="stylesheet" href="./styles/layout.css" />
  <link rel="stylesheet" href="./styles/navbar.css" />

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
          <?php if ($_SESSION['soortgebruiker'] == 1 || $_SESSION['soortgebruiker'] == 2 || $_SESSION['soortgebruiker'] == 3) : ?>
            <li onclick="location.href = 'productvoorraad.php'">
              <i class="fa-solid fa-shop"></i>
              <p>Productvoorraad</p>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION['soortgebruiker'] == 1 || $_SESSION['soortgebruiker'] == 3) : ?>
            <li onclick="location.href = 'voedselpakketten.php'" class="selected">
              <i class="fa-solid fa-bag-shopping"></i>
              <p>Voedselpakketten</p>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION['soortgebruiker'] == 1 || $_SESSION['soortgebruiker'] == 2) : ?>
            <li onclick="location.href = 'leveranciers.php'">
              <i class="fa-solid fa-truck-field"></i>
              <p>Leveranciers</p>
            </li>
          <?php endif; ?>
          <?php if ($_SESSION['soortgebruiker'] == 1) : ?>
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
      <h1>Voedselpakket ID: <?php echo $row['pakket_nr'] ?></h1>
      <div class="voedselpakketDatums">
        <p>
          <span>Datum Samenstelling:</span> <?php echo $row['samenstelling'] ?>
          <span class="lijn">|</span> <span>Datum Uitgifte:</span> <?php echo $row['uitgifte'] ?>
        </p>
      </div>

      <div class="productenlijst">
        <?php if (!empty($rows)) : ?>
          <?php foreach ($rows as $product) : ?>
            <div class="product">
              <h2><?php echo $product['naam'] ?></h2>
              <p>Aantal: <?php echo $product['aantal'] ?></p>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <p>No rows found.</p>
        <?php endif; ?>
      </div>

      <div class="klantInfo">
        <h3>Klant Info</h3>
        <div class="klantInfoRow">
          <div class="klantDetail">
            <h6>Voornaam</h6>
            <p><?php echo $row['naam'] ?></p>
          </div>
          <div class="klantDetail">
            <h6>Achternaam</h6>
            <p><?php echo $row['achternaam'] ?></p>
          </div>
        </div>
        <div class="klantInfoRow">
          <div class="klantDetail">
            <h6>Postcode</h6>
            <p><?php echo $row['postcode'] ?></p>
          </div>
          <div class="klantDetail">
            <h6>Huisnummer</h6>
            <p><?php echo $row['huisnummer'] ?></p>
          </div>
        </div>
        <div class="klantInfoRow">
          <div class="klantDetail">
            <h6>Plaats</h6>
            <p><?php echo $row['plaats'] ?></p>
          </div>
          <div class="klantDetail">
            <h6>Telefoon</h6>
            <p><?php echo $row['telefoon'] ?></p>
          </div>
        </div>
      </div>

      <div class="voedselpakketActies">
        <a class="wijzig">Wijzig voedselpakket</a>
        <a class="verwijder">Verwijder voedselpakket</a>
      </div>
    </div>
  </div>
</body>

</html>