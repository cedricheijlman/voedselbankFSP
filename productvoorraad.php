<?php
// MySQL database configuration
$host = '127.0.0.1';
$dbname = 'voedselbank';
$username = 'root';
$password = '12345678';
$port = '3306';

try {
  // Create a PDO connection
  $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
  $pdo = new PDO($dsn, $username, $password);

  // Query to retrieve all rows from the "gebruiker" table
  $sql = "SELECT * FROM product";
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
          <li onclick="location.href = 'productvoorraad.php'" class="selected">
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
          <li>
            <i class="fa-solid fa-truck-field"></i>
            <p>Leveranciers</p>
          </li>
          <li>
            <i class="fa-solid fa-chart-simple"></i>
            <p>Maand Overzicht</p>
          </li>
          <li>
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
        <h2>Productvoorraad</h2>
        <a href="./voegproduct.php">Voeg product toe</a>
      </div>
      <input type="text" class="zoekInput" id="searchInput" placeholder="Zoek op streepjescode" onkeyup="searchProduct()" />

      <table id="productTable">
        <thead>
          <tr>
            <th onclick="sortTable(0)" data-column-index="0">
              Streepjescode<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(1)" data-column-index="1">
              Productnaam<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(2)" data-column-index="2">
              Categorie<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(3)" data-column-index="3">
              Aantal in voorraad<span class="sort-indicator"></span>
            </th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($rows)) : ?>
            <?php foreach ($rows as $row) : ?>
              <tr>
                <td><?php echo $row['streepjescode']; ?></td>
                <td><?php echo $row['naam']; ?></td>
                <td><?php echo $row['categorie_id']; ?></td>
                <td><?php echo $row['aantal']; ?></td>

                <td class="actions">
                  <a href="#" onclick="editProduct(0)"><i class="fa-solid fa-pen-to-square"></i></a>
                  <a href="#" onclick="deleteProduct(0)"><i class="fa-solid fa-trash"></i></a>
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
</body>

</html>