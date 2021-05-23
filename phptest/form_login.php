<?php
    $validationErrors = [];
    $email = '';
    $passwort = '';
    $anmeldungErfolgreich = false;
    
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
            $anmeldungErfolgreich = true;
        }
        
        $zeile = date('Y-m-d H:i:s') . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . $email . ' ' . ($anmeldungErfolgreich ? 'success' : 'error') . "\n";
        file_put_contents('login_trace.txt', $zeile, FILE_APPEND);
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
        
        <?php if ($anmeldungErfolgreich): ?>
            <p>Sie sind angemeldet als <?= $email ?></p>
        <?php else: ?>
            <form action="form_login.php" method="POST" enctype="multipart/form-data">
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
        <?php endif; ?>
            
            <img src="image.php?id=1441" alt="">
    </body>
</html>

