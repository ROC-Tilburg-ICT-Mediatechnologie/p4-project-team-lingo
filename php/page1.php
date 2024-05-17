<?php
$words = array("woord", "ander", "spel", "code", "taak"); // Array van mogelijke woorden
$correct_word = str_split($words[array_rand($words)]); // Selecteer een willekeurig woord
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wordle Spel</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
    <h1>Wordle Spel</h1>

    <form method="post" action="" id="wordleForm">
        Naam: <input type="text" name="naam" required><br>
        Woord: <input type="text" name="woord" maxlength="5" required id="inputWord"><br>
        <input type="submit" value="Check">
    </form>

    <h2>Top 10 Scores</h2>
    <ul id="scoreList">
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

    <script>
        var correctWord = <?php echo json_encode($correct_word); ?>;
        document.getElementById('wordleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var inputWord = document.getElementById('inputWord').value.split('');
            var result = '';
            for (var i = 0; i < inputWord.length; i++) {
                if (inputWord[i] === correctWord[i]) {
                    result += '<span class="correct">' + inputWord[i] + '</span>';
                } else if (correctWord.includes(inputWord[i])) {
                    result += '<span class="misplaced">' + inputWord[i] + '</span>';
                } else {
                    result += inputWord[i];
                }
            }
            document.getElementById('scoreList').innerHTML = '<li>' + result + '</li>' + document.getElementById('scoreList').innerHTML;
            document.getElementById('inputWord').value = '';
        });
    </script>

</body>
</html>

<?php
$conn->close();
?>