<?php
session_start();

// Überprüfe, ob bereits eine aktive Session besteht
if (isset($_SESSION["username"])) {
    header("Location: me.php");
    exit();
}

include './inc/config.php';

// Hier solltest du deine Datenbankverbindung herstellen
$db = new mysqli($mysqlHost, $mysqlUsername, $mysqlPassword, $mysqlDatabase);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Hier solltest du die Benutzereingaben überprüfen und vorbereitete Anweisungen für die Datenbankzugriffe verwenden
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Beispiel: Datenbankabfrage
    // Verwende vorbereitete Anweisungen, um SQL-Injektionen zu verhindern
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Benutzer gefunden, überprüfe das Passwort
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Login erfolgreich
            $_SESSION["username"] = $username;
            header("Location: me.php");
            exit();
        } else {
            // Passwort stimmt nicht überein
            echo "<p>Ungültige Anmeldeinformationen. Bitte versuche es erneut.</p>";
        }
    } else {
        // Benutzer nicht gefunden
        echo "<p>Ungültige Anmeldeinformationen. Bitte versuche es erneut.</p>";
    }

    // Schließe die Anweisung
    $stmt->close();
}

// Schließe die Datenbankverbindung am Ende deines Skripts
$db->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
            margin-right: 10px;
        }

        .button:hover {
            background-color: #677bc4;
        }

        .register-button {
            background-color: #4caf50; /* Hellgrün */
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .register-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body class="bg-gray-800 text-white">

<div class="container mt-16">
    <div class="card">
        <h1 class="text-2xl font-semibold mb-4">Login</h1>

        <!-- HTML-Formular für den Login -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="input-group">
                <label for="username" class="label">Benutzername:</label>
                <input type="text" name="username" class="input" required>
            </div>

            <div class="input-group">
                <label for="password" class="label">Passwort:</label>
                <input type="password" name="password" class="input" required>
            </div>

            <input type="submit" value="Anmelden" class="button">
            <a href="register.php" class="register-button">Registrieren</a>
        </form>
    </div>
</div>

</body>
</html>
