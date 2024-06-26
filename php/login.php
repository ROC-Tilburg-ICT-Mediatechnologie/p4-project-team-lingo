<?php
// Start de sessie
session_start();
// Database configuratie
$servername = "localhost";
$username = "root";
$password = "";
$database = "wordle_game";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
   
    $stmt = $conn->prepare("SELECT id, password, admin FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashed_password, $admin);
        $stmt->fetch();
        // Verifieer het wachtwoord
        if (password_verify($password, $hashed_password)) {
            // Wachtwoord is correct, start een nieuwe sessie en sla de gebruikers-ID op
            $_SESSION["userid"] = $id;
            if ($admin >= 1) {
                // Als de gebruiker adminniveau 1 of hoger heeft, doorsturen naar de adminpagina
                header("Location: admin.php");
            } else {
                // Gebruiker heeft geen toegang tot de adminpagina
               header("Location: choice.php");
            }
        } else {
            // Ongeldig wachtwoord
            echo "Ongeldig wachtwoord.";
        }
    } else {
        // Gebruikersnaam bestaat niet
        echo "Geen account gevonden met die gebruikersnaam.";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">


    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        h1 {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            margin: 0;
            text-align: center;
            width: 100%;
        }
        form {
            margin: 20px auto;
            width: 300px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        input[type="text"], input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        input[type="submit"]:hover {
            background-color: #444;
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Login">
        <a href="register.php">Register here</a>
    </form>
</body>
</html>
