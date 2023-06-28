<?php
session_start();
if (!isset($_SESSION['soortgebruiker'])) {
  header("Location: login.php");
  exit();
}
// MySQL database configuration
require_once 'credentials.php';


try {
  // Create a PDO connection
  $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
  $pdo = new PDO($dsn, $username, $password);

  // Query to retrieve all rows from the "gebruiker" table
  $sql = "SELECT *
  FROM leverancier";
  // Execute the query and fetch all rows
  $result = $pdo->query($sql);
  $rows = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die('Database query failed: ' . $e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="./styles/layout.css" />
  <link rel="stylesheet" href="./styles/navbar.css" />
  <link rel="stylesheet" href="./styles/gebruikers.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div id="overlay" class="overlay">
      <div class="overlayForm">
        <h2>Verwijder Leverancier</h2>
        <p>Weet je zeker dat je deze leverancier wilt verwijderen?</p>
        <div class="overlayButtonsContainer">
          <a onclick="hideModal()">Nee</a>
          <button onclick="verwijderLeverancier()" name="modalButton">Ja</button>
        </div>
      </div>
    </div>
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
      <div class="headerContainer">
        <h2>Leveranciers</h2>
        <a href="./voegleverancier.php">Voeg leverancier toe</a>
      </div>
      <input type="text" class="zoekInput" id="searchInput" placeholder="Zoek op bedrijfsnaam" onkeyup="searchLeverancier()" />
      <table id="productTable">
        <thead>
          <tr>
            <th onclick="sortTable(0)" data-column-index="0">
              ID<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(1)" data-column-index="1">
              Bedrijfsnaam<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(2)" data-column-index="2">
              Postcode<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(3)" data-column-index="3">
              Huisnummer<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(4)" data-column-index="4">
              Plaats<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(5)" data-column-index="5">
              Email<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(6)" data-column-index="6">
              Telefoon<span class="sort-indicator"></span>
            </th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($rows)) : ?>
            <?php foreach ($rows as $row) : ?>
              <tr>
                <td><?php echo $row['id_leverancier']; ?></td>
                <td><?php echo $row['bedrijfsnaam']; ?></td>
                <td><?php echo $row['postcode']; ?></td>
                <td><?php echo $row['huisnummer']; ?></td>
                <td><?php echo $row['plaats']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['telefoon']; ?></td>
                <td class="actions">
                  <a href="wijzigleverancier.php?idleverancier=<?php echo $row['id_leverancier'] ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                  <a data-productid="<?php echo $row['id_leverancier']; ?>" onclick="showModal(this)"><i class="fa-solid fa-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <p>No rows found.</p>
          <?php endif; ?>




        </tbody>
      </table>
    </div>
  </div>
  <script>
    // Zoek Leverancier
    function searchLeverancier() {
      let input = document.getElementById("searchInput").value.toLowerCase();

      let rows = document.getElementsByTagName("tr");

      for (let i = 0; i < rows.length; i++) {
        let row = rows[i];
        let bedrijfsnaam = row.getElementsByTagName("td")[1];

        if (bedrijfsnaam) {
          let value = bedrijfsnaam.textContent.toLowerCase();

          if (value.indexOf(input) > -1) {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        }
      }
    }

    let idleverancier;

    function verwijderLeverancier() {
      window.location.href = 'verwijderleverancier.php?idleverancier=' + idleverancier;
    }

    function showModal(button) {
      document.getElementById("overlay").style.display = "flex";
      idleverancier = button.getAttribute('data-productid');

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
  <script src="script.js"></script>

</body>

</html>