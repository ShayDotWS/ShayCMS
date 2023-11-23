<?php
session_start();

if (!isset($_SESSION["username"])) {
    // Benutzer ist nicht angemeldet, leite ihn zur Login-Seite weiter
    header("Location: index.php");
    exit();
}

// Lade die MySQL-Konfigurationsdatei
include './inc/config.php';

// Hier solltest du deine Datenbankverbindung herstellen
$db = new mysqli($mysqlHost, $mysqlUsername, $mysqlPassword, $mysqlDatabase);

// Restlicher Code bleibt unverändert
// ...



// Überprüfe die Verbindung auf Fehler
if ($db->connect_error) {
    die("Verbindungsfehler: " . $db->connect_error);
}

// Benutzername aus der Session abrufen
$username = $_SESSION["username"];

// Datenbankabfrage, um das auth_ticket und look abzurufen
$query = "SELECT auth_ticket, look FROM users WHERE username = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($authTicket, $look);
$stmt->fetch();
$stmt->close();

// Variablen für Taler, Duckets und Diamanten aus der Datenbank abrufen
$queryValues = "SELECT credits, pixels, points FROM users WHERE username = ?";
$stmtValues = $db->prepare($queryValues);
$stmtValues->bind_param("s", $username);
$stmtValues->execute();
$stmtValues->bind_result($talerValue, $ducketsValue, $diamantenValue);
$stmtValues->fetch();
$stmtValues->close();

// Funktion zur Generierung eines zufälligen Strings
function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meine Seite</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-800 text-white">

<div class="container mx-auto mt-16">
    <!-- Navigation -->
    <nav class="flex justify-between mb-8">
        <div>
            <a href="#" class="text-xl font-semibold">Home</a>
            <a href="#" class="ml-4 text-xl font-semibold">Team</a>
            <a href="#" class="ml-4 text-xl font-semibold">News</a>
        </div>
        <div>
            <span class="mr-4">Meine Taler: <?= $talerValue ?? 'Nicht verfügbar' ?></span>
            <span class="mr-4">Meine Duckets: <?= $ducketsValue ?? 'Nicht verfügbar' ?></span>
            <span>Meine Diamanten: <?= $diamantenValue ?? 'Nicht verfügbar' ?></span>
        </div>
    </nav>

    <div class="card flex bg-gray-700 text-gray-200 p-8 rounded">
        <div class="w-1/2">
            <h1 class="text-2xl font-semibold mb-4">Hallo, <?= htmlspecialchars($_SESSION["username"]) ?>!</h1>
        
            <!-- Weitere Inhalte deiner Seite -->

            <form action="logout.php" method="post" class="mb-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Logout</button>
            </form>

            <!-- Button für die Weiterleitung basierend auf "auth_ticket" -->
            <form method="post">
                <button type="submit" name="ab_ins_hotel" class="bg-green-500 text-white px-4 py-2 rounded">Ab ins Hotel</button>
            </form>
            
            <?php
            // Wenn der "Ab ins Hotel"-Button geklickt wird
            if (isset($_POST["ab_ins_hotel"])) {
                // Überprüfe, ob das auth_ticket vorhanden ist
                if ($authTicket) {
                    // Konstruiere die URL mit dem "auth_ticket"
                    $redirectURL = "http://localhost/nitro-react/build/index.php?sso=" . urlencode($authTicket);

                    // Leite den Benutzer weiter
                    header("Location: $redirectURL");
                    exit();
                } else {
                    // Gib eine Meldung aus, wenn das "auth_ticket" nicht vorhanden ist
                    echo "<p class='text-red-500'>Authentifizierungsticket nicht verfügbar.</p>";
                }
            }
            ?>
        </div>

        <!-- Hier wird das Bild basierend auf der "look"-Spalte angezeigt -->
        <div class="w-1/2">
            <?php
            // Hier generierst du die Bild-URL basierend auf der "look"-Spalte
            $imageUrl = "https://imager.shabbo.de/?figure=" . urlencode($look);
            ?>
                <img src="<?= $imageUrl ?>" alt="User Look" class="w-full h-auto max-h-64 object-none">
        </div>
    </div>
</div>

<!-- ... (dein weiterer HTML-Code) ... -->

</body>
</html>
