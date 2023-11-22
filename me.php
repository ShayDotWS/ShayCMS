<?php
session_start();

if (!isset($_SESSION["username"])) {
    // Benutzer ist nicht angemeldet, leite ihn zur Login-Seite weiter
    header("Location: index.php");
    exit();
}

// Datenbankverbindung herstellen
$db = new mysqli('localhost', 'root', '', 'habbo');

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

// Wenn der "Ab ins Hotel"-Button geklickt wird
if (isset($_POST["ab_ins_hotel"])) {
    // Generiere ein neues auth_ticket
    $newAuthTicket = "ShayCMS-" . generateRandomString(16); // 16 zufällige Zeichen

    // Speichere das neue auth_ticket in der Datenbank
    $updateQuery = "UPDATE users SET auth_ticket = ? WHERE username = ?";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bind_param("ss", $newAuthTicket, $username);

    if ($updateStmt->execute()) {
        // Konstruiere die URL mit dem "auth_ticket"
        $redirectURL = "http://localhost/nitro-react/build/index.php?sso=" . urlencode($newAuthTicket);

        // Leite den Benutzer weiter
        header("Location: $redirectURL");
        exit();
    } else {
        // Gib eine Meldung aus, wenn das Update fehlschlägt
        echo "<p class='text-red-500'>Fehler beim Update des Auth-Tickets in der Datenbank.</p>";
    }

    $updateStmt->close();
}

// Schließe die Datenbankverbindung am Ende deines Skripts
$db->close();

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
    <div class="card flex bg-gray-700 text-gray-200 p-8 rounded">
        <div class="w-1/2">
            <h1 class="text-2xl font-semibold mb-4">Hallo, <?= htmlspecialchars($_SESSION["username"]) ?>!</h1>
        
        

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


</body>
</html>
