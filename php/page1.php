<?php
session_start(); // Start the session

// Select a random word
$words = array("woord", "ander", "spel", "code", "taak");
$correct_word = str_split($words[array_rand($words)]);

// Store correct word and guesses in the session
if (!isset($_SESSION['correct_word'])) {
    $_SESSION['correct_word'] = $correct_word;
    $_SESSION['guesses'] = 0;
    $_SESSION['game_status'] = "ongoing";
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "wordle_game";
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$naam = "";
$woord = "";
$score = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SESSION['guesses'] < 5 && $_SESSION['game_status'] == "ongoing") {
    $naam = isset($_POST['naam']) ? $_POST['naam'] : "";
    $woord = isset($_POST['woord']) ? $_POST['woord'] : "";

    if (!empty($naam) && !empty($woord)) {
        for ($i = 0; $i < strlen($woord); $i++) {
            if (isset($_SESSION['correct_word'][$i]) && $woord[$i] == $_SESSION['correct_word'][$i]) {
                $score++;
            }
        }

        if ($woord === implode($_SESSION['correct_word'])) {
            $_SESSION['game_status'] = "won";
        } else {
            $_SESSION['guesses']++;
            if ($_SESSION['guesses'] == 5) {
                $_SESSION['game_status'] = "lost";
            }
        }

        $stmt = $conn->prepare("INSERT INTO scores (naam, score, datum) VALUES (?, ?, NOW())");
        $stmt->bind_param("si", $naam, $score);
        $stmt->execute();
        $stmt->close();
    }
}

$sql = "SELECT naam, score FROM scores ORDER BY score DESC, datum ASC LIMIT 10";
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wordle Spel</title>
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
        .container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start; 
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
        }
        .instructions, .leaderboard {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 20%;
            text-align: center;
        }
        .header {
            background-color: #333;
            color: white;
            padding: 10px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .instructions button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .instructions button:hover {
            background-color: #218838;
        }
        form {
            margin: 20px auto;
            width: 300px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 10vh;
            left: 40vw;
        }
        input[type="text"], input[type="submit"] {
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
        .correct {
            color: green;
            font-weight: bold;
        }
        .misplaced {
            color: orange;
            font-weight: bold;
        }
        #scoreList {
            list-style-type: none;
            padding: 0;
            width: 100%;
        }
        #scoreList li {
            background-color: white;
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 600px;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .deleteButton {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .deleteButton:hover {
            background-color: darkred;
        }
        #aanpassen {
            position: absolute;
            top: 5vh;
            right: -20vw;
            background-color: none;
            height: 0px;
            width: 200px;
        }
        #appelkaas {
            position: absolute;
            top: 55vh;
            right: 20vw;
            background-color: none;
            height: 50px;
            width: 200px;
        }
        .selected {
            background-color: #ccc;
        }
        #wordleForm {
        }
        #result {
            position: absolute;
            top: 55vh;
            right: 50vw;
        }
    </style>
</head>
<body>
    <h1>Wordle Spel</h1>

    <div class="container">
        <div class="instructions">
            <div class="header">Uitleg</div>
            <p>Welcome to WordPlay!<br>
            It's for people that love Wordle, but hate limits.<br>
            Enjoy unlimited games, challenge others and learn about words.<br>
            <p id="dailyPuzzleText">Daily Puzzle #103 - Fri, May 24</p>
            <button id="tutorialButton">How to play</button>
        </div>
        <div class="leaderboard">
            <div class="header">Leaderboard</div>
            <form method="POST" id="aanpassen">
                <button type="submit" name="sort" value="today">Vandaag</button>
                <button type="submit" name="sort" value="yesterday">Gisteren</button>
                <button type="submit" name="sort" value="all">Alle Tijd</button>
            </form>
           <ul id="scoreList">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($row["naam"]) . ": " . htmlspecialchars($row["score"]) . "</li>";
                    }
                } else {
                    echo "Geen scores gevonden.";
                }
                ?>
            </ul>
        </div>
    </div>
    <form id="wordleForm" method="POST" action="">
        <label for="naam">Naam:</label>
        <input type="text" id="naam" name="naam" value="<?php echo htmlspecialchars($naam); ?>" required>
        <label for="woord">Woord:</label>
        <input type="text" id="woord" name="woord" value="<?php echo htmlspecialchars($woord); ?>" required>
        <input type="submit" value="Verzenden">
    </form>
    <div id="result">
        <?php
        if ($_SESSION['game_status'] == "won") {
            echo "<p>Congratulations! You guessed the word!</p>";
        } elseif ($_SESSION['game_status'] == "lost") {
            echo "<p>Game over! The correct word was " . implode($_SESSION['correct_word']) . ".</p>";
        }
        ?>
        </div>
    <div id="tutorialModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Hoe te spelen</h2>
            <p>Dit is een eenvoudige uitleg over hoe je het spel moet spelen...</p>
            <ul>
                <li>Je hebt 5 pogingen om het woord te raden.</li>
                <li>Elke keer dat je een woord invoert, wordt aangegeven welke letters correct zijn.</li>
                <li>Groen betekent dat de letter op de juiste plaats staat.</li>
                <li>Oranje betekent dat de letter in het woord zit, maar op de verkeerde plaats.</li>
            </ul>
        </div>
    </div>
    <script>
        var modal = document.getElementById("tutorialModal");
        var btn = document.getElementById("tutorialButton");
        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        var correctWord = <?php echo json_encode($_SESSION['correct_word']); ?>;
        document.getElementById('wordleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var inputWord = document.getElementById('woord').value.split('');
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
            document.getElementById('result').innerHTML += '<p>' + result + '</p>';
            document.getElementById('woord').value = '';
        });

        var sortButtons = document.querySelectorAll('#aanpassen button');
        sortButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                sortButtons.forEach(function(btn) {
                    btn.classList.remove('selected');
                });
                button.classList.add('selected');
            });
        });

        var today = new Date();
        var options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
        var formattedDate = today.toLocaleDateString('en-US', options);
        var puzzleNumber = 103;
        var displayText = `Daily Puzzle #${puzzleNumber} - ${formattedDate}`;

        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('dailyPuzzleText').innerText = displayText;
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
