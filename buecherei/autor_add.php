<?php
require_once 'functions.php';

$errorMessages = [];
$name = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parameter aus POST Request auslesen
    $name = (isset($_POST['name']) ? trim($_POST['name']) : '');
    
    // Validierung des Parameters
    if (empty($name)) {
        $errorMessages['name'] = 'Bitte geben Sie den Namen des Autors ein.';
    }
    
    // Wenn die Validierung erfolgreich war -> keine Fehlermeldungen
    if (count($errorMessages) == 0) {
        $conn = connectToDB();
        
        $query = "INSERT INTO autor (name) VALUES ('$name')";
        
        // Ausführen des SQL-Statements und Überprüfung ob es erfolgreich war.
        if (mysqli_query($conn, $query)) {
            // Datenbank-Verbindung schließen
            closeDB($conn);
            
            // Weiterleitung auf die Startseite
            header('Location: index.php');
        } else {
            $errorMessages['general'] = 'Beim Einfügen des Autors ist ein Fehler aufgetreten.';
                    
            // Datenbank-Verbindung schließen
            closeDB($conn);
        }
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
        <title>Autor hinzufügen</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Autor hinzufügen</h1>
        
        <?php if (isset($errorMessages['general'])): ?>
            <div class="error-message"><?= $errorMessages['general'] ?></div>
        <?php endif; ?>
        
        <form action="autor_add.php" method="POST">
            <div class="form-field">
                <label for="name">Autorname</label>
                <input type="text" name="name" id="name" value="<?= $name ?>" required>
                
                <?php if (isset($errorMessages['name'])): ?>
                    <div class="error-message"><?= $errorMessages['name'] ?></div>
                <?php endif; ?>
            </div>
            
            <button type="submit">Hinzufügen</button>
            <button type="button" onclick="location.href='index.php';">Abbrechen</button>
        </form>
    </body>
</html>