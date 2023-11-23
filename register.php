<?php
session_start();

include './inc/config.php';

// Hier sollte deine Datenbankverbindung hergestellt werden
$db = new mysqli($mysqlHost, $mysqlUsername, $mysqlPassword, $mysqlDatabase);

// Überprüfe die Verbindung auf Fehler
if ($db->connect_error) {
    die("Verbindungsfehler: " . $db->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hier sollte die Benutzereingaben überprüft und vorbereitete Anweisungen für die Datenbankzugriffe verwendet werden
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Passwort sicher hashen

    // Überprüfe, ob der Benutzer bereits existiert
    $checkQuery = "SELECT id FROM users WHERE username = ?";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Benutzer existiert bereits
        echo "<p>Benutzername bereits vergeben. Bitte wähle einen anderen.</p>";
    } else {
        // Benutzer existiert noch nicht, füge ihn zur Datenbank hinzu
        $accountCreated = 1;
        $accountDayOfBirth = 1;
        $ipRegister = $_SERVER['REMOTE_ADDR']; // IP-Adresse des Benutzers
        $ipCurrent = $_SERVER['REMOTE_ADDR']; // Aktuelle IP-Adresse des Benutzers
        $onlineStatus = 0; // Online-Status

        // Generiere ein zufälliges auth_ticket
        $authTicket = uniqid("ShayCMS-");

        $insertQuery = "INSERT INTO users (username, password, account_created, account_day_of_birth, ip_register, ip_current, online, auth_ticket) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $db->prepare($insertQuery);
        $insertStmt->bind_param("ssisssis", $username, $password, $accountCreated, $accountDayOfBirth, $ipRegister, $ipCurrent, $onlineStatus, $authTicket);
        $insertStmt->execute();

        // Starte eine Session für den neuen Benutzer
        $_SESSION["username"] = $username;

        // Leite den Benutzer zur "me.php" weiter
        header("Location: me.php");
        exit();
    }

    // Schließe die Anweisungen
    $checkStmt->close();
    $insertStmt->close();
}

// Schließe die Datenbankverbindung am Ende deines Skripts
$db->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrierung</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
        }

        .card {
            background-color: #36393f;
            color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .label {
            display: block;
            margin-bottom: 5px;
            color: #7289da;
        }

        .input {
            width: 100%;
            padding: 8px;
            border: 1px solid #7289da;
            border-radius: 4px;
            color: #36393f;
        }

        .button {
            background-color: #7289da;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #677bc4;
        }
    </style>
</head>
<body class="bg-gray-800 text-white">

<div class="container mt-16">
    <div class="card">
        <h1 class="text-2xl font-semibold mb-4">Registrierung</h1>

        <!-- HTML-Formular für die Registrierung -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="input-group">
                <label for="username" class="label">Benutzername:</label>
                <input type="text" name="username" class="input" required>
            </div>

            <div class="input-group">
                <label for="password" class="label">Passwort:</label>
                <input type="password" name="password" class="input" required>
            </div>

            <input type="submit" value="Registrieren" class="button">
        </form>
    </div>
</div>

</body>
</html>
