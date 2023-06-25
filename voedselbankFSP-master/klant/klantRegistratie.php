<?php

include_once 'cred.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $naam = $_POST["naam"];
    $tussenvoegsel = $_POST["tussenvoegsel"];
    $achternaam = $_POST["achternaam"];
    $postcode = $_POST["postcode"];
    $huisnummer = $_POST["huisnummer"];
    $plaats = $_POST["plaats"];
    $telefoon = $_POST["telefoon"];
    $email = $_POST["email"];
    $volwassenen = $_POST["volwassenen"];
    $kinderen = $_POST["kinderen"];
    $babys = $_POST["babys"];

    // Prepare and execute SQL statement
    $sql = "INSERT INTO klant (naam, tussenvoegsel, achternaam, postcode, huisnummer, plaats, telefoon, email, volwassenen, kinderen, `baby's`)
            VALUES ('$naam', '$tussenvoegsel', '$achternaam', '$postcode', '$huisnummer', '$plaats', '$telefoon', '$email', '$volwassenen', '$kinderen', '$babys')";

    if ($conn->query($sql) === TRUE) {
        $params = http_build_query(array(
            'naam' => $naam,
            'achternaam' => $achternaam,
            'email' => $email,
            'postcode' => $postcode,
            'huisnummer' => $huisnummer,
            'plaats' => $plaats,
            'telefoon' => $telefoon
        ));
    
        // Redirect to wensKoppeling.php with query parameters
        header("Location: wensKoppeling.php?" . $params);
        exit();
    } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

// Close database connection
$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="klant.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
        
  <h2 class="formTitel">Klant registratie</h2>
  <form class="form" method="POST" action="#">
    <div class="inputs">
        <div class="formInput">
            <label for="naam">Naam:</label>
            <input type="text" id="naam" name="naam" pattern="[A-Za-z\u00C0-\u017F]+" required>
        </div>

        <div class="formInput">
            <label for="tussenvoegsel">Tussenvoegsel: (optioneel)</label>
            <input type="text" id="tussenvoegsel" name="tussenvoegsel" pattern="[A-Za-z\u00C0-\u017F-]+">
        </div>

        <div class="formInput">
            <label for="achternaam">Achternaam:</label>
            <input type="text" id="achternaam" name="achternaam" pattern="[A-Za-z\u00C0-\u017F]+" required>
        </div>

        <div class="formInput">
            <label for="postcode">Postcode: (1234AB)</label>
            <input type="text" id="postcode" name="postcode" pattern="\d{4}[a-zA-Z]{2}" required>
        </div>

        <div class="formInput">
            <label for="huisnummer">Huisnummer:</label>
            <input type="text" id="huisnummer" name="huisnummer" pattern="[0-9][0-9A-Za-z]*" required>
        </div>

        <div class="formInput">
            <label for="plaats">Plaats:</label>
            <input type="text" id="plaats" name="plaats" pattern="[A-Za-z\u00C0-\u017F]+" required>
        </div>

        <div class="formInput">
            <label for="telefoon">Telefoon: (0612345678)</label>
            <input type="tel" id="telefoon" name="telefoon" pattern="\d{10}" required>
        </div>

        <div class="formInput">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" required>
        </div>

        <div class="formInput">
            <label for="volwassenen">Aantal volwassenen:</label>
            <input type="number" id="volwassenen" name="volwassenen" min="0" required>
        </div>

        <div class="formInput">
            <label for="kinderen">Aantal kinderen:</label>
            <input type="number" id="kinderen" name="kinderen" min="0" required>
        </div>

        <div class="formInput">
            <label for="babys">Aantal baby's:</label>
            <input type="number" id="babys" name="babys" min="0" required>
        </div>
    </div>

    <input class="knop" type="submit" value="Registreer">
</form>


    </div>
</body>
</html>