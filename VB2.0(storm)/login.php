<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create a database connection
    $servername = 'localhost';
    $username = 'root';
    $db_password = '';
    $database = 'voedselbank';
    $conn = new mysqli($servername, $username, $db_password, $database);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Prepare the SELECT query
    $stmt = $conn->prepare("SELECT id_gebruiker, gebruikersnaam, email, wachtwoord FROM gebruiker WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['wachtwoord'];
        if (password_verify($password, $hashedPassword)) {
            // Login successful
            $_SESSION['id_gebruiker'] = $row['id_gebruiker'];
            $_SESSION['gebruikersnaam'] = $row['gebruikersnaam'];
            echo 'Login successful!';
        } else {
            // Invalid password
            echo 'Invalid email or password.';
        }
    } else {
        // Invalid email
        echo 'Invalid email or password.';
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
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
</head>

<body>
  <div class="container">
    <div class="links">
      <h1 class="applicatieNaam">Applicatie Naam</h1>

      <form class="form" action="login.php" method="POST">
        <h2 class="formTitel">Inloggen</h2>
        <div class="formInput">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="formInput">
          <label for="password">Wachtwoord</label>
          <input type="password" id="password" name="password" required />
        </div>
        <button type="submit" class="knop">Inloggen</button>
        <p class="wachtwoordVergeten">Wachtwoord vergeten?</p>
      </form>

      <p class="geenAccount">
        Nog geen account?
        <span><a href="./registratie.php">Registreer</a></span>
      </p>
    </div>
    <div class="rechts"></div>
  </div>
</body>

</html>