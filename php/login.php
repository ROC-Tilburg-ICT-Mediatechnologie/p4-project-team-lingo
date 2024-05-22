<?php
// Definieer gebruikersnaam en wachtwoord
$correct_username = 'admin';
$correct_password = 'password';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $correct_username && $password === $correct_password) {
        echo 'Inloggen succesvol!';
        // Hier kunt u de gebruiker doorverwijzen naar een andere pagina
    } else {
        echo 'Verkeerde gebruikersnaam of wachtwoord!';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inloggen</title>
    <link rel = "stylesheet" type = "text/css" href = "../css/login.css">
</head>
<body>
    <form method="post" action="">
        Gebruikersnaam: <input type="text" name="username" required><br>
        Wachtwoord: <input type="password" name="password" required><br>
        <input type="submit" value="Inloggen">
    </form>
</body>
</html>