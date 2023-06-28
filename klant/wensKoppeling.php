<?php
include_once 'cred.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klantId = $_POST['klant_id'];
    $selectedWensen = $_POST['wensen'];
    $persoonlijkeWens = $_POST['persoonlijkeWens'];

    foreach ($selectedWensen as $wensId) {
        $sql = "INSERT INTO klant_has_wens (id_klant, id_wens) VALUES ($klantId, $wensId)";
        $conn->query($sql);
    }

    $sql = "UPDATE klant SET persoonlijkeWens = '$persoonlijkeWens' WHERE id_klant = $klantId";
    $conn->query($sql);

    header("Location: voltooid.php");
    exit();
}

if (isset($_GET["naam"]) && isset($_GET["achternaam"]) && isset($_GET["email"]) && isset($_GET["postcode"]) && isset($_GET["huisnummer"]) && isset($_GET["plaats"]) && isset($_GET["telefoon"])) {
    $naam = $_GET["naam"];
    $achternaam = $_GET["achternaam"];
    $email = $_GET["email"];
    $postcode = $_GET["postcode"];
    $huisnummer = $_GET["huisnummer"];
    $plaats = $_GET["plaats"];
    $telefoon = $_GET["telefoon"];

    $sql = "SELECT id_klant FROM klant WHERE naam = '$naam' AND achternaam = '$achternaam' AND email = '$email' AND postcode = '$postcode' AND huisnummer = '$huisnummer' AND plaats = '$plaats' AND telefoon = '$telefoon'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $klantId = $row["id_klant"];

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
            <title>Wensen</title>
        </head>
        <body>
            <div class="container">
                <h2 class="formTitel">Overige wensen</h2>
                <form class="formWens" method="POST" action="">

                <div class="inputsWens">

                    <input type="hidden" name="klant_id" value="<?php echo $klantId; ?>">

                    <label for="geen_varkensvlees">
                        <input type="checkbox" id="geen_varkensvlees" name="wensen[]" value="1">
                        Geen varkensvlees
                    </label><br>

                    <label for="veganistisch">
                        <input type="checkbox" id="veganistisch" name="wensen[]" value="2">
                        Veganistisch
                    </label><br>

                    <label for="vegetarisch">
                        <input type="checkbox" id="vegetarisch" name="wensen[]" value="3">
                        Vegetarisch
                    </label><br>

                
                    <label for="persoonlijke_wens">Persoonlijke wens:</label><br>
                    <textarea id="persoonlijke_wens" name="persoonlijkeWens" maxlength="999"></textarea>
                


                </div>

                    <input class="knop" type="submit" value="Opslaan">
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Geen klant gevonden";
    }
} else {
    echo "Geen parameters";
}

$conn->close();
?>
