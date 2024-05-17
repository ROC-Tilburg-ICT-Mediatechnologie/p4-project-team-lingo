<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Difficulty Selection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 15vh;
            background-color: black;

        }
        .container {
            text-align: center;
        }
        .difficulty-btn {
            border: none;
            padding: 20px 40px; /* Aangepaste padding voor grotere knoppen */
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 18px; /* Aangepaste lettergrootte */
            margin: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            background-color: rgb(17, 131, 17);
            border: 2px solid black;
            color: white;
        }
        .difficulty-btn:hover {
            background-color: #65c742; /* Default hover kleur */
        }
        .difficulty-btn.easy:hover {
            background-color: #04ff00; /* Donker oranje voor easy */
        }
        .difficulty-btn.normal:hover {
            background-color: #e68a00; /* Donker oranje voor normal */
        }
        .difficulty-btn.hard:hover {
            background-color: #ff3333; /* Rood voor hard */
        }
        .difficulty-btn.expert:hover {
            background-color: #570747; /* Donker rood voor expert */
        }

        .login-button {
        background-color: #008CBA; /* Blauw */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        transition-duration: 0.4s;
    }

    .login-button:hover {
        background-color: #4CAF50; /* Groen */
        color: white;
    }
    </style>
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
