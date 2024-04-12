Natuurlijk, hier is de bijgewerkte `README.md` met icons en emojis voor een meer visuele en aantrekkelijke weergave:

---

# 🎮 Wordle Game

## 📝 Projectomschrijving

Dit project is een eenvoudige implementatie van de populaire Wordle-game. Het stelt spelers in staat om een 5-letter woord te raden en hun scores te zien op een real-time scoreboard.

## 📋 Inhoud

- [🎮 Wordle Game](#-wordle-game)
  - [📝 Projectomschrijving](#-projectomschrijving)
  - [📋 Inhoud](#-inhoud)
  - [🚀 Hoe te gebruiken](#-hoe-te-gebruiken)
    - [🔧 Installatie](#-installatie)
    - [🎮 Gebruik](#-gebruik)
  - [📊 Database](#-database)
  - [📜 Licentie](#-licentie)

## 🚀 Hoe te gebruiken

### 🔧 Installatie

1. Clone de repository naar je lokale machine:
    ```bash
    git clone https://github.com/jouwgebruikersnaam/wordle-game.git
    ```
2. Navigeer naar de projectdirectory:
    ```bash
    cd wordle-game
    ```
3. Maak de database en tabellen aan. Gebruik hiervoor het SQL-script dat in de `database.sql` file staat.

### 🎮 Gebruik

1. Open het `index.html` bestand in je favoriete webbrowser.
2. Voer een 5-letter woord in in het tekstveld en klik op "Guess".
3. Je ziet direct of je woord correct is en het scoreboard wordt automatisch bijgewerkt.

## 📊 Database

De database bevat de volgende tabellen:

- **Players**: Bevat informatie over de spelers.
- **Words**: Bevat de woorden die geraden moeten worden.
- **Guesses**: Bevat de pogingen van de spelers om het woord te raden.

Zorg ervoor dat je de juiste databasegegevens vervangt in de PHP-bestanden (`check_guess.php` en `get_scoreboard.php`) door de daadwerkelijke gegevens voor je database.
