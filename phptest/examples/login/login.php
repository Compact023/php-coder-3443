<?php
    session_start();
    
    require_once 'functions.php';
    
    // Überprüfen ob der Benutzer bereits angemeldet ist
    if (isSignedIn()) {
        // Weiterleitung auf index.php
        header('Location: index.php');
        die();
    }

    $validationErrors = [];
    $email = '';
    $passwort = '';
    
    // ist der Request ein POST Request?
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // trim($variable) entfernt Leerzeichen vorne und hinten vom übergebenen Wert und gibt den bereinigten Wert dann zurück
        //$variable = (Bedingung ? Then-Wert : Else-Wert);
        $email = (isset($_POST['email']) ? trim($_POST['email']) : '');
        $passwort = (isset($_POST['passwort']) ? trim($_POST['passwort']) : '');
        
        // Validierung der Eingaben
        // Wenn die Variable $email leer (kein Inhalt oder ein leerer Text) ist
        if (empty($email)) {
            $validationErrors['email'] = 'Bitte geben Sie Ihre Email Adresse ein.';
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Wenn die Variable $email keine gültige Email Adresse enthält
            $validationErrors['email'] = 'Bitte geben Sie eine gültige Email Adresse ein.';
        }
        // Wenn die Variable $passwort leer (kein Inhalt oder ein leerer Text) ist
        if (empty($passwort)) {
            $validationErrors['passwort'] = 'Bitte geben Sie Ihr Passwort ein.';
        }
        
        // Wenn alle Validierungen erfolgreich waren -> dann ist das $validationErrors Array leer -> hat 0 Elemente
        if (count($validationErrors) == 0) {
            $user = null;

            // Verbindung zur DB herstellen
            $conn = connectToDB();
            
            $query = "SELECT * FROM user WHERE email LIKE ?";
            
            // Prepared Statement erstellen
            $stmt = mysqli_prepare($conn, $query);
            
            // ? durch Werte ersetzen
            mysqli_stmt_bind_param($stmt, "s", $email);
            
            // Prepared Statement ausführen
            mysqli_stmt_execute($stmt);
            
            $result = mysqli_stmt_get_result($stmt);
            
            if ($result) {
                $user = mysqli_fetch_assoc($result);
            }
            
            // Überprüfen ob Benutzer exitiert, ob er aktiv ist und ob das Passwort übereinstimmt
            if ($user && $user['aktiv'] && password_verify($passwort, $user['passwort'])) {
                $remoteIP = getUserIP();
                $lastLogin = date('Y-m-d H:i:s');
                $loginVersuche = 0;
             
                $query = 'UPDATE user SET '
                        .'remote_ip = ?,'
                        .'last_login = ?,'
                        .'login_versuche = ? '
                        .'WHERE user_id = ?';
                
                // Prepared Statement erstellen
                $stmt = mysqli_prepare($conn, $query);
                
                // ? durch Werte ersetzen
                mysqli_stmt_bind_param($stmt, "ssii", $remoteIP, $lastLogin, $loginVersuche, $user['user_id']);
                
                // Prepared Statement ausführen
                mysqli_stmt_execute($stmt);
                
                // Datenbankverbindung schließen
                closeDB($conn);
                
                // Email im Session-Element User ablegen
                $_SESSION['user'] = $user;

                // Weiterleitung auf index.php
                header('Location: index.php');
                die();
            } else {
                $validationErrors['allgemein'] = 'Die Email-Adresse oder das Passwort sind nicht korrekt.';
                
                if ($user && $user['aktiv']) {
                    $remoteIP = getUserIP();
                    $loginVersuche = $user['login_versuche'] + 1;
                    
                    if ($loginVersuche > MAX_LOGIN_TRIES) {
                        $aktiv = 0;
                        $gesperrtAm = date('Y-m-d H:i:s');
                    } else {
                        $aktiv = $user['aktiv'];
                        $gesperrtAm = $user['gesperrt_am'];
                    }

                    $query = 'UPDATE user SET '
                            .'remote_ip = ?,'
                            .'aktiv = ?,'
                            .'login_versuche = ?,'
                            .'gesperrt_am = ? '
                            .'WHERE user_id = ?';

                    // Prepared Statement erstellen
                    $stmt = mysqli_prepare($conn, $query);

                    // ? durch Werte ersetzen
                    mysqli_stmt_bind_param($stmt, "siisi", $remoteIP, $aktiv, $loginVersuche, $gesperrtAm, $user['user_id']);

                    // Prepared Statement ausführen
                    mysqli_stmt_execute($stmt);
                }
            }
            
            // Datenbankverbindung schließen
            closeDB($conn);
        }
    }
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Login</h1>
        
        <form action="login.php" method="POST" enctype="multipart/form-data">
            <?php if (isset($validationErrors['allgemein'])): ?>
                <div class="error-message"><?= $validationErrors['allgemein'] ?></div>
            <?php endif; ?>
            
            <div class="form-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= $email ?>" required>
                <?php if (isset($validationErrors['email'])): ?>
                    <div class="error-message"><?= $validationErrors['email'] ?></div>
                <?php endif; ?>
            </div>

            <div class="form-field">
                <label for="passwort">Passwort</label>
                <input type="password" id="passwort" name="passwort" value="<?= $passwort ?>" required>
                <?php if (isset($validationErrors['passwort'])): ?>
                    <div class="error-message"><?= $validationErrors['passwort'] ?></div>
                <?php endif; ?>
            </div>

            <button type="submit">Anmelden</button>
        </form>

    </body>
</html>