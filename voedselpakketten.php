<?php
// MySQL database configuration
require_once 'credentials.php';

try {
  // Create a PDO connection
  $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
  $pdo = new PDO($dsn, $username, $password);

  // Query to retrieve all rows from the "gebruiker" table
  $sql = "SELECT voedselpakket.*, klant.id_klant, klant.achternaam, klant.postcode  FROM voedselpakket JOIN klant ON voedselpakket.klant_id = klant.id_klant";
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
  <link rel="stylesheet" href="./styles/productvoorraad.css" />
  <link rel="stylesheet" href="./styles/navbar.css" />
  <link rel="stylesheet" href="./styles/layout.css" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div id="overlay" class="overlay">
      <div class="overlayForm">
        <h2>Verwijder Product</h2>
        <p>Weet je zeker dat je dit product wilt verwijderen?</p>
        <div class="overlayButtonsContainer">
          <a onclick="hideModal()">Nee</a>
          <button onclick="deleteProduct()" name="modalButton">Ja</button>
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
      <div class="headerContainer">
        <h2>Voedselpakketten</h2>
        <a href="./voegvoedselpakket.php">Voeg voedselpakket toe</a>
      </div>
      <input type="text" class="zoekInput" id="searchInput" placeholder="Zoek op streepjescode" onkeyup="searchProduct()" />

      <table id="productTable">
        <thead>
          <tr>
            <th onclick="sortTable(0)" data-column-index="0">
              Pakketnummer<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(1)" data-column-index="1">
              Datum van samenstelling<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(2)" data-column-index="2">
              Datum van uitgifte<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(3)" data-column-index="3">
              Postcode levering<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(4)" data-column-index="4">
              Klant ID<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(5)" data-column-index="5">
              Klant Achternaam<span class="sort-indicator"></span>
            </th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($rows)) : ?>
            <?php foreach ($rows as $row) : ?>
              <tr onclick="location.href = 'voedselpakket.php?id=<?php echo $row['pakket_nr'] ?>'" data-productid="<?php echo $row['pakket_nr']; ?>">
                <td><?php echo $row['pakket_nr']; ?></td>
                <td><?php echo $row['samenstelling']; ?></td>
                <td><?php echo $row['uitgifte']; ?></td>
                <td><?php echo $row['postcode']; ?></td>
                <td><?php echo $row['klant_id']; ?></td>
                <td><?php echo $row['achternaam']; ?></td>


                <td class="actions">
                  <a href="wijzigproduct.php?streepjescode=<?php echo $row['streepjescode'] ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                  <a data-productid="<?php echo $row['streepjescode']; ?>" onclick="showModal(this)"><i class="fa-solid fa-trash"></i></a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <p>No rows found.</p>
          <?php endif; ?>
        </tbody>
      </table>

      <script src="script.js"></script>
    </div>
  </div>
  <script>
    let id; // Variable to store the selected product ID


    function deleteProduct() {
      // Redirect to deleteproduct.php with the selected product ID
      window.location.href =
        "deleteproduct.php?streepjescode=" + streepjescode;
    }

    function showModal(button) {
      document.getElementById("overlay").style.display = "flex";
      streepjescode = button.getAttribute("data-productid");
    }

    function hideModal() {
      document.getElementById("overlay").style.display = "none";
    }
    window.onclick = function(event) {
      let modal = document.getElementById("overlay");
      if (event.target == modal) {
        modal.style.display = "none";
      }
    };
  </script>
</body>

</html>