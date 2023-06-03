<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create a database connection
    $servername = 'localhost';
    $username = 'root';
    $dbpassword = '123456';
    $database = 'voedselbank';
    $conn = new mysqli($servername, $username, $dbpassword, $database);

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
            $_SESSION['user_id'] = $row['id_gebruiker'];
            $_SESSION['username'] = $row['gebruikersnaam'];
            echo 'Login successful!';
            // Redirect to the home page or any other authorized page
            // header('Location: nogNietgemaakt.html');
            exit();
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
