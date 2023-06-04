<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $email = $_POST['email'];
    $wachtwoord = $_POST['wachtwoord'];

    // Hash the password
    $hashedPassword = password_hash($wachtwoord, PASSWORD_DEFAULT);

    // Create a database connection
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'voedselbank';
    $conn = new mysqli($servername, $username, $password, $database);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Insert the data into the gebruiker table
    $sql = "INSERT INTO gebruiker (gebruikersnaam, email, wachtwoord) VALUES ('$gebruikersnaam', '$email', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        echo 'Registration successful';
        echo '<br> <a href="login.php">Click here to login</a>';
    } else {
        echo 'Error: ' . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="./styles/login.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div class="links">
      <h1 class="applicatieNaam">Applicatie Naam</h1>

      <form class="form" action="registratie.php" method="POST">
        <h2 class="formTitel">Maak een account</h2>
        <div class="formInput">
          <label for="gebruikersnaam">Gebruikersnaam</label>
          <input type="text" id="gebruikersnaam" name="gebruikersnaam" required />
        </div>
        <div class="formInput">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required />
        </div>
        <div class="formInput">
          <label for="wachtwoord">Wachtwoord (min. 8 tekens)</label>
          <input type="password" id="wachtwoord" name="wachtwoord" minlength="8" required />
        </div>
        <button type="submit" class="knop">Registreren</button>
      </form>

      <p class="geenAccount">
        Al een account? <span><a href="./login.html">Inloggen</a></span>
      </p>
    </div>
    <div class="rechts"></div>
  </div>
</body>

</html>
