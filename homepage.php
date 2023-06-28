<?php
session_start();

if (!isset($_SESSION['soortgebruiker'])) {
  header("Location: login.php");
  exit();
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
          <li onclick="location.href = 'homepage.php'" class="selected">
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
      <h1>Welkom, <?php echo $_SESSION['voornaam'] ?>!</h1>
    </div>
  </div>
</body>

</html>