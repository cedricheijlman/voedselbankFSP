<?php
// MySQL database configuration
$host = '127.0.0.1';
$dbname = 'voedselBank';
$username = 'root';
$password = '12345678';
$port = '3306';

try {
  // Create a PDO connection
  $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
  $pdo = new PDO($dsn, $username, $password);

  // Query to retrieve all rows from the "gebruiker" table
  $sql = "SELECT gebruiker.*, soortgebruiker.soortgebruiker
  FROM gebruiker
  JOIN soortgebruiker ON gebruiker.id_soortgebruiker = soortgebruiker.id";
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
    <div class="navbar">
      <h2>Maaskantje Paneel</h2>
      <div class="navbarListContainer">
        <ul>
          <li onclick="location.href = 'homepage.html'">
            <i class="fa-solid fa-house"></i>
            <p>Home</p>
          </li>
          <li class="selected">
            <i class="fa-solid fa-user-group" onclick="location.href = 'gebruikers.php'"></i>
            <p>Gebruikers</p>
          </li>
          <li onclick="location.href = 'productvoorraad.html'">
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
        <h2>Gebruikers</h2>
        <a href="./voeggebruiker.php">Voeg gebruiker toe</a>
      </div>
      <input type="text" class="zoekInput" id="searchInput" placeholder="Zoek op gebruikersnaam" onkeyup="searchGebruiker()" />
      <table id="gebruikerTable">
        <thead>
          <tr>
            <th onclick="sortTable(0)" data-column-index="0">
              ID<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(1)" data-column-index="1">
              Naam<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(2)" data-column-index="2">
              Achternaam<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(3)" data-column-index="3">
              Gebruikersnaam<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(4)" data-column-index="4">
              Emailadres<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(5)" data-column-index="5">
              Soort Gebruiker<span class="sort-indicator"></span>
            </th>
            <th onclick="sortTable(6)" data-column-index="6">
              Site Toegang<span class="sort-indicator"></span>
            </th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($rows)) : ?>
            <?php foreach ($rows as $row) : ?>
              <tr>
                <td><?php echo $row['id_gebruiker']; ?></td>
                <td><?php echo $row['naam']; ?></td>
                <td><?php echo $row['achternaam']; ?></td>
                <td><?php echo $row['gebruikersnaam']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['soortgebruiker']; ?></td>
                <td><?php if ($row['toegang'] == 1) {
                      echo 'Ja';
                    } elseif ($row['toegang'] == 0) {
                      echo 'Nee';
                    }
                    ?></td>
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
    </div>
  </div>
  <script src="script.js"></script>

</body>

</html>