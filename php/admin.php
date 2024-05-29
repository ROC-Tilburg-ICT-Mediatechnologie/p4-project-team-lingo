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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'deleteScores') {
        // Delete all scores from the database
        $sql = "DELETE FROM scores";
        if ($conn->query($sql) === TRUE) {
            echo "All scores have been deleted.";
        } else {
            echo "Error deleting scores: " . $conn->error;
        }
    }
}
// Fetch top 10 scores
$sql = "SELECT id, naam, score FROM scores ORDER BY score DESC, datum ASC LIMIT 10";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
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
        .deleteScoreButton {
            background: yellow;
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
    </style>
</head>
<body>
    <h1>Wordle Spel</h1>
    <p>welkom admin<p>
    <form action="login.php" method="post">
        <button>Login</button>
    </form>
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
            <ul id="scoreList">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<li>" . htmlspecialchars($row["naam"]) . ": " . htmlspecialchars($row["score"]) . " <button class='deleteScoreButton' data-scoreid='" . $row['id'] . "'>Delete</button></li>";
                    }
                } else {
                    echo "No scores found.";
                }
                ?>
            </ul>
            <button id="deleteScoresButton">Delete All Scores</button>
        </div>
    </div>
    <form method="POST" action="">
        <label for="naam">Naam:</label>
        <input type="text" id="naam" name="naam" required>
        <label for="woord">Woord:</label>
        <input type="text" id="woord" name="woord" required>
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
            document.getElementById('scoreList').innerHTML = '<li>' + result + '</li>' + document.getElementById('scoreList').innerHTML;
            document.getElementById('woord').value = '';
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

        // Add this JavaScript code to handle the deletion of a single score
document.querySelectorAll('.deleteScoreButton').forEach(button => {
    button.addEventListener('click', function() {
        const scoreId = this.getAttribute('data-scoreid');
        if (confirm("Are you sure you want to delete this score?")) {
            // Make an AJAX call to delete the specific score
            fetch('delete_score.php', {
                method: 'POST',
                body: JSON.stringify({ scoreId: scoreId }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    alert("Score deleted successfully.");
                    // Refresh the leaderboard after deleting the score
                    location.reload();
                } else {
                    alert("Error deleting score.");
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
    </script>
</body>
</html>
<?php
$conn->close();
?>  