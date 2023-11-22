<?php
session_start();

if (isset($_SESSION["username"])) {
    // Hier solltest du deine Datenbankverbindung herstellen
    // Ersetze 'dbname', 'username' und 'password' durch deine tatsächlichen Datenbankinformationen
    $db = new mysqli('localhost', 'root', '', 'habbo');

    // Überprüfe die Verbindung auf Fehler
    if ($db->connect_error) {
        die("Verbindungsfehler: " . $db->connect_error);
    }

    // Benutzername aus der Session abrufen
    $username = $_SESSION["username"];

    // Authentifizierungsticket löschen
    $deleteQuery = "UPDATE users SET auth_ticket = NULL WHERE username = ?";
    $deleteStmt = $db->prepare($deleteQuery);
    $deleteStmt->bind_param("s", $username);
    $deleteStmt->execute();
    $deleteStmt->close();

    // Schließe die Datenbankverbindung
    $db->close();
}

// Beende die PHP-Session
session_destroy();
?>
