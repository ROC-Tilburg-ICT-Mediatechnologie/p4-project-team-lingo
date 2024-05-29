<?php
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
    $data = json_decode(file_get_contents("php://input"), true);
    $scoreId = $data['scoreId'];

    // Delete the specific score from the database
    $sql = "DELETE FROM scores WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $scoreId);
    $stmt->execute();
    $stmt->close();
}
    
$conn->close();
?>