<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wordle game</title>
</head>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wordle_game";

// Maak verbinding met de database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $woord = $_POST["woord"];
    $naam = $_POST["naam"];

    // Controleer het woord en bepaal de score (dit is een eenvoudig voorbeeld, je kunt hier een echte Wordle-logic implementeren)
    $score = 5;

    // Voeg de score toe aan de database
    $sql = "INSERT INTO scores (naam, score) VALUES ('$naam', $score)";
    if ($conn->query($sql) === TRUE) {
        echo "Score succesvol toegevoegd!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Haal de top 10 scores op uit de database
$sql = "SELECT naam, score FROM scores ORDER BY score ASC LIMIT 10";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wordle Spel</title>
</head>
<body>
    <h1>Wordle Spel</h1>

    <form method="post" action="">
        Naam: <input type="text" name="naam" required><br>
        Woord: <input type="text" name="woord" maxlength="5" required><br>
        <input type="submit" value="Check">
    </form>

    <h2>Top 10 Scores</h2>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<li>" . $row["naam"] . ": " . $row["score"] . "</li>";
            }
        } else {
            echo "Geen scores gevonden.";
        }
        ?>
    </ul>

</body>
</html>

<?php
$conn->close();
?>

</body>
</html>