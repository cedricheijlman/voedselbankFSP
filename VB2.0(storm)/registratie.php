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
    $password = '123456';
    $database = 'voedselbank';
    $conn = new mysqli($servername, $username, $password, $database);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Insert the data into the gebruiker table
    $sql = "INSERT INTO gebruiker (gebruikersnaam, email, wachtwoord) VALUES ('$gebruikersnaam', '$email', '$hashedPassword')";
    if ($conn->query($sql) === TRUE) {
        echo 'Registratie gelukt';
        echo '<br> <a href="login.html">Klik hier om in te loggen</a>';
    } else {
        echo 'Registratie mislukt<br>';
        echo '<a href="registratie.html">Klik hier om het opnieuw te proberen</a>';
    }

    // Close the database connection
    $conn->close();
}
?>
