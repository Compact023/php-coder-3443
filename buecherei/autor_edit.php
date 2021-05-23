<?php
require_once 'functions.php';

$errorMessages = [];
$name = '';

// Datenbank-Verbindung aufbauen
$conn = connectToDB();

// Laden des Autors aus der Datenbank
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // ID von URL Parameter auslesen
    $buchId = (isset($_GET['id']) ? $_GET['id'] : 0);

    // Select-Statement für das Auslesen eines Autors anhand einer gegebenen ID
    $query = "SELECT * FROM autor WHERE autor_id=$buchId";

    // Select-Statement an die Datenbank senden und Ergebnis in Variable $result speichern
    $result = mysqli_query($conn, $query);

    // Überprüfen vom Resultat (ob es erfolgreich war und ob die Anzahl der zurückgelieferten Zeilen == 1 ist)
    if ($result && mysqli_num_rows($result) == 1) {

        // Datensatz aus Ergebnis auslesen
        $autor = mysqli_fetch_assoc($result);

        $name = $autor['name'];
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Speichern, wenn das Formular abgesendet wurde
    
    // Parameter aus POST Request auslesen
    $buchId = (isset($_POST['id']) ? trim($_POST['id']) : '');
    $name = (isset($_POST['name']) ? trim($_POST['name']) : '');
    
    // Validierung des Parameters
    if (empty($name)) {
        $errorMessages['name'] = 'Bitte geben Sie den Namen des Autors ein.';
    }
    
    // Wenn die Validierung erfolgreich war -> keine Fehlermeldungen
    if (count($errorMessages) == 0) {
        $query = "UPDATE autor SET name = '$name' WHERE autor_id = $buchId";
        
        // Ausführen des SQL-Statements und Überprüfung ob es erfolgreich war.
        if (mysqli_query($conn, $query)) {
            // Datenbank-Verbindung schließen
            closeDB($conn);
            
            // Weiterleitung auf die Startseite
            header('Location: index.php');
        } else {
            $errorMessages['general'] = 'Beim Ändern des Autors ist ein Fehler aufgetreten.';            
        }
    }
}

// Datenbank-Verbindung schließen
closeDB($conn);

?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Autor bearbeiten</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Autor bearbeiten</h1>
        
        <?php if ($buchId && $name): ?>
        
            <?php if (isset($errorMessages['general'])): ?>
                <div class="error-message"><?= $errorMessages['general'] ?></div>
            <?php endif; ?>

            <form action="autor_edit.php" method="POST">
                <div class="form-field">
                    <label for="name">Autorname</label>
                    <input type="text" name="name" id="name" value="<?= $name ?>" required>

                    <?php if (isset($errorMessages['name'])): ?>
                        <div class="error-message"><?= $errorMessages['name'] ?></div>
                    <?php endif; ?>
                </div>

                <input type="hidden" name="id" value="<?= $buchId ?>">
                
                <button type="submit">Speichern</button>
                <button type="button" onclick="location.href='index.php';">Abbrechen</button>
            </form>
                
        <?php else: ?>
            <p>Der Autor existiert nicht.</p>
        <?php endif; ?>
    </body>
</html>