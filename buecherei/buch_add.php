<?php

require_once 'functions.php';

$formData = [];
$errorMessages = [];

$conn = connectToDB();

if (isPostRequest()) {
    // Übergebene Daten aus POST-Request auslesen und in Array speichern
    $formData = [
        'isbn' => formFieldValue('isbn', ''),
        'buch_titel' => formFieldValue('buch_titel', ''),
        'autor_id' => formFieldValue('autor_id', 0, false),
        'kategorie_id' => formFieldValue('kategorie_id', 0, false),
        'verlag_id' => formFieldValue('verlag_id', 0, false),
        'erscheinungsdatum' => formFieldValue('erscheinungsdatum', null, false),
        'kurzbeschreibung' => formFieldValue('kurzbeschreibung', ''),
    ];

    $validations = [
        'isbn' => [
            'not_empty' => [
                'error_msg' => 'Bitte geben Sie eine ISBN ein.',
            ],
            'min_length' => [
                'min' => 3,
                'error_msg' => 'Bitte geben Sie mind. 3 Zeichen ein.'
            ]
        ],
        'buch_titel' => [
            'not_empty' => [
                'error_msg' => 'Bitte geben Sie einen Buchtitel ein.'
            ],
        ],
        'autor_id' => [
            'not_empty' => [
                'error_msg' => 'Bitte wählen Sie einen Autor aus.'
            ]
        ],
        'kategorie_id' => [
            'not_empty' => [
                'error_msg' => 'Bitte wählen Sie eine Kategorie aus.'
            ]
        ],
        'verlag_id' => [
            'not_empty' => [
                'error_msg' => 'Bitte wählen Sie einen Verlag aus.'
            ]
        ],
    ];
   
    
    // Validierung der Formular Daten
    if (validate($formData, $validations, $errorMessages)) {
        
        $query = "INSERT INTO buch (isbn, buch_titel, autor_id, kategorie_id, verlag_id, erscheinungsdatum, kurzbeschreibung)"
                ." VALUES ('" . $formData['isbn'] . "', '" . $formData['buch_titel'] . "', " . $formData['autor_id'] . ", " . $formData['kategorie_id'] . ", " . $formData['verlag_id'] . ", '" . $formData['erscheinungsdatum'] . "', '" . $formData['kurzbeschreibung'] . "')";
        
        // Wenn das Insert erfolgreich war
        if (mysqli_query($conn, $query)) {
            // DB Verbindung schließen
            closeDB($conn);
            
            // Weiterleitung auf die index.php
            header('Location: index.php');
        } else {
            $errorMessages['general'] = 'Beim Einfügen in die Datenbank ist ein Fehler aufgetreten';
        }
    }
}

$autoren = fetchAutorenFromDB($conn);

$kategorien = fetchKategorienFromDB($conn);

$verlaege = fetchVerlaegeFromDB($conn);

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
        <title>Buch hinzufügen</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Buch hinzufügen</h1>
        
        <?php if (isset($errorMessages['general'])): ?>
            <div class="error-message"><?= $errorMessages['general'] ?></div>
        <?php endif; ?>
        
        <form action="buch_add.php" method="POST">
            <div class="form-field">
                <label for="isbn">ISBN</label>
                <input type="text" name="isbn" id="isbn" value="<?= @$formData['isbn'] ?>" required>
                
                <?php if (isset($errorMessages['isbn'])): ?>
                    <div class="error-message"><?= $errorMessages['isbn'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-field">
                <label for="buch_titel">Buchtitel</label>
                <input type="text" name="buch_titel" id="buch_titel" value="<?= @$formData['buch_titel'] ?>" required>
                
                <?php if (isset($errorMessages['buch_titel'])): ?>
                    <div class="error-message"><?= $errorMessages['buch_titel'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-field">
                <label for="autor_id">Autor</label>
                <select name="autor_id" id="autor_id" required>
                    <option value="" disabled selected>Bitte wählen</option>
                    <?php foreach ($autoren as $autor): ?>
                        <option value="<?= $autor['autor_id'] ?>" <?= ($autor['autor_id'] == @$formData['autor_id'] ? 'selected' : '') ?>>
                            <?= $autor['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <?php if (isset($errorMessages['autor_id'])): ?>
                    <div class="error-message"><?= $errorMessages['autor_id'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-field">
                <label for="kategorie_id">Kategorie</label>
                <select name="kategorie_id" id="kategorie_id" required>
                    <option value="" disabled selected>Bitte wählen</option>
                    <?php foreach ($kategorien as $kategorie): ?>
                        <option value="<?= $kategorie['kategorie_id'] ?>" <?= ($kategorie['kategorie_id'] == @$formData['kategorie_id'] ? 'selected' : '') ?>>
                            <?= $kategorie['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <?php if (isset($errorMessages['kategorie_id'])): ?>
                    <div class="error-message"><?= $errorMessages['kategorie_id'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-field">
                <label for="verlag_id">Verlag</label>
                <select name="verlag_id" id="verlag_id" required>
                    <option value="" disabled selected>Bitte wählen</option>
                    <?php foreach ($verlaege as $verlag): ?>
                        <option value="<?= $verlag['verlag_id'] ?>" <?= ($verlag['verlag_id'] == @$formData['verlag_id'] ? 'selected' : '') ?>>
                            <?= $verlag['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <?php if (isset($errorMessages['verlag_id'])): ?>
                    <div class="error-message"><?= $errorMessages['verlag_id'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-field">
                <label for="erscheinungsdatum">Erscheinungsdatum</label>
                <input type="date" name="erscheinungsdatum" id="erscheinungsdatum" value="<?= @$formData['erscheinungsdatum'] ?>">
                
                <?php if (isset($errorMessages['erscheinungsdatum'])): ?>
                    <div class="error-message"><?= $errorMessages['erscheinungsdatum'] ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-field">
                <label for="kurzbeschreibung">Kurzbeschreibung</label><br>
                <textarea name="kurzbeschreibung" id="kurzbeschreibung" rows="10" cols="160"><?= @$formData['kurzbeschreibung'] ?></textarea>
                
                <?php if (isset($errorMessages['kurzbeschreibung'])): ?>
                    <div class="error-message"><?= $errorMessages['kurzbeschreibung'] ?></div>
                <?php endif; ?>
            </div>
            
            
            <button type="submit">Hinzufügen</button>
            <button type="button" onclick="location.href='index.php';">Abbrechen</button>
        </form>
    </body>
</html>