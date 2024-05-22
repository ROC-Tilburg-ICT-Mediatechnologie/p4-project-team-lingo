<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Difficulty Selection</title>
    <link rel="stylesheet" type="text/css" href="../css/diff.css">
</head>
<body>
    
    <div class="container">
        <h2>Choose a difficulty level</h2>
        <header>
        
        </header>
        <button class="difficulty-btn easy" onclick="window.location.href='page1.php'">Easy</button>
        <button class="difficulty-btn normal" onclick="selectDifficulty('normal')">Normal</button><br>
        <button class="difficulty-btn hard" onclick="selectDifficulty('hard')">Hard</button>
        <button class="difficulty-btn expert" onclick="selectDifficulty('expert')">Expert</button>
        <button class="login-button" onclick="window.location.href='login.php'">Inloggen</button>
    </div>

    <script>
        function selectDifficulty(difficulty) {
            // You can add your logic here for what to do when a difficulty is selected
            console.log('Selected difficulty:', difficulty);
        }
    </script>
</body>
</html>
