<?php
    // Select a random word
$words = array("woord", "ander", "spel", "code", "taak");
$correct_word = str_split($words[array_rand($words)]);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "wordle_game";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $naam = $_POST['naam'];
    $woord = $_POST['woord'];

    $score = 0;
    for ($i = 0; $i < strlen($woord); $i++) {
        if (isset($correct_word[$i]) && $woord[$i] == $correct_word[$i]) {
            $score++;
        }
    }

    $stmt = $conn->prepare("INSERT INTO scores (naam, score, datum) VALUES (?, ?, NOW())");
    $stmt->bind_param("si", $naam, $score);
    $stmt->execute();
    $stmt->close();
}

// Fetch top 10 scores
$sql = "SELECT naam, score FROM scores ORDER BY score DESC, datum ASC LIMIT 10";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wordle Spel</title>
    <link rel="stylesheet" type="text/css" href="../css/page1.css">
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
                    <!-- voeg hier nog dat de dag automatisch vervangt met bijvoorbeeld #104 - Fri, May 24 -->
            <button id="tutorialButton">How to play</button>
        </div>
        <div class="leaderboard">
            <div class="header">Leaderboard</div>
            <button>Today</button>
            <button>Yesterday</button>
            <button>All Time</button>
            <ul id="scoreList">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($row["naam"]) . ": " . htmlspecialchars($row["score"]) . "</li>";
                    }
                } else {
                    echo "Geen scores gevonden.";
                }
                ?>
            </ul>
        </div>
    </div>
    <form method="POST" action="" id="woordForm">
        <label for="naam">Naam:</label>
        <input type="text" id="naam" name="naam" required>
        <label for="woord">Woord:</label>
        <input type="text" id="woord" name="woord" required maxlength="5">
        <div id="feedback"></div>
        <input type="submit" value="Verzenden">
    </form>
    <div id="tutorialModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Hoe te spelen</h2>
            <p>Dit is een eenvoudige uitleg over hoe je het spel moet spelen...</p>
            <ul>
                <li>Je hebt 5 pogingen om het woord te raden.</li>
                <li>Elke keer dat je een woord invoert, wordt aangegeven welke letters correct zijn.</li>
                <li>Groene letters zijn correct en op de juiste plaats.</li>
                <li>Gele letters zijn correct maar op de verkeerde plaats.</li>
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

        var correctWord = <?php echo json_encode($correct_word); ?>;
        document.getElementById('woordForm').addEventListener('submit', function(e) {
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
            document.getElementById('feedback').innerHTML = result;
        });


        // Get today's date
var today = new Date();

// Options for formatting the date
var options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };

// Format the date
var formattedDate = today.toLocaleDateString('en-US', options);

// Create the puzzle number (this example uses 103, increment or adjust as needed)
var puzzleNumber = 103;

// Construct the display text
var displayText = `Daily Puzzle #${puzzleNumber} - ${formattedDate}`;

// Insert the display text into the appropriate element
document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelector('p').innerText = displayText;
});

    </script>
</body>
</html>

<?php
$conn->close();
?>