<?php
    require_once 'form_kursanmeldung_functions.php';
    
    $validationErrors = [];
    $kurse = [];
    $kurs = '';
    $anrede = '';
    $vorname = '';
    $nachname = '';
    $email = '';
    $geburtsdatum = '';
    $anmeldungErfolgreich = false;
    
    // ist der Request ein POST Request?
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        /*if (isset($_POST['anrede'])) {
            $anrede = $_POST['anrede'];
        } else {
            $anrede = '';
        }*/
        
        // trim($variable) entfernt Leerzeichen vorne und hinten vom übergebenen Wert und gibt den bereinigten Wert dann zurück
        //$variable = (Bedingung ? Then-Wert : Else-Wert);
        $kurs = (isset($_POST['kurs']) ? trim($_POST['kurs']) : '');
        $anrede = (isset($_POST['anrede']) ? trim($_POST['anrede']) : '');
        $vorname = (isset($_POST['vorname']) ? trim($_POST['vorname']) : '');
        $nachname = (isset($_POST['nachname']) ? trim($_POST['nachname']) : '');
        $email = (isset($_POST['email']) ? trim($_POST['email']) : '');
        $geburtsdatum = (isset($_POST['geburtsdatum']) ? trim($_POST['geburtsdatum']) : '');
        
        // Validierung der Eingaben
        $validationErrors = validiereEingaben($kurs, $anrede, $vorname, $nachname, $email, $geburtsdatum);
        
        // Wenn alle Validierungen erfolgreich waren -> dann ist das $validationErrors Array leer -> hat 0 Elemente
        if (count($validationErrors) == 0) {
            // Speichern der Datein in einer Datei
            speichereAnmeldungInDatei('kursanmeldungen.txt', $kurs, $anrede, $vorname, $nachname, $email, $geburtsdatum);
            
            $anmeldungErfolgreich = true;
        }
    }
    
    // Kurse aus der Datei kurse.csv auslesen, wenn die Anmeldung nicht erfolgreich war oder das Formular zum ersten Mal geladen wird
    if (!$anmeldungErfolgreich) {
        $kurse = getKurseVonDatei('kurse.csv');
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
        <title>Kursanmeldung</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Kursanmeldung</h1>
        
        <?php if ($anmeldungErfolgreich): ?>
            <p>Vielen Dank für Ihre Anmeldung!</p>
        <?php else: ?>
            <form action="form_kursanmeldung.php" method="POST" enctype="multipart/form-data">
                <div class="form-field">
                    <label for="kurs">Kurs</label>
                    <select id="kurs" name="kurs" required>
                        <option selected disabled></option>
                        <?php foreach ($kurse as $tmpKurs): ?>
                            <option 
                                value="<?= $tmpKurs['kurs_nummer'] ?>" 
                                <?= ($kurs == $tmpKurs['kurs_nummer'] ? 'selected' : '') ?>
                            >
                                <?= $tmpKurs['kurs_titel'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($validationErrors['kurs'])): ?>
                        <div class="error-message"><?= $validationErrors['kurs'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="anrede">Anrede</label>
                    <select id="anrede" name="anrede" required>
                        <option selected disabled></option>
                        <option <?= ($anrede == 'Frau' ? 'selected' : '') ?>>Frau</option>
                        <option <?= ($anrede == 'Herr' ? 'selected' : '') ?>>Herr</option>
                        <option <?= ($anrede == 'Divers' ? 'selected' : '') ?>>Divers</option>
                    </select>
                    <?php
                        /*if (isset($validationErrors['anrede'])) {
                            echo '<div class="error-message">' . $validationErrors['anrede'] . '</div>';
                        }*/
                    ?>
                    <?php if (isset($validationErrors['anrede'])): ?>
                        <div class="error-message"><?= $validationErrors['anrede'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="vorname">Vorname</label>
                    <input type="text" id="vorname" name="vorname" value="<?= $vorname ?>" required>
                    <?php if (isset($validationErrors['vorname'])): ?>
                        <div class="error-message"><?= $validationErrors['vorname'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="nachname">Nachname</label>
                    <input type="text" id="nachname" name="nachname" value="<?= $nachname ?>" required>
                    <?php if (isset($validationErrors['nachname'])): ?>
                        <div class="error-message"><?= $validationErrors['nachname'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= $email ?>" required>
                    <?php if (isset($validationErrors['email'])): ?>
                        <div class="error-message"><?= $validationErrors['email'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-field">
                    <label for="geburtsdatum">Geburtsdatum</label>
                    <input type="date" id="geburtsdatum" name="geburtsdatum" value="<?= $geburtsdatum ?>" required>
                    <?php if (isset($validationErrors['geburtsdatum'])): ?>
                        <div class="error-message"><?= $validationErrors['geburtsdatum'] ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit">Anmelden</button>
            </form>
        <?php endif; ?>
    </body>
</html>

