<?php
    $filePath = 'kursanmeldungen.txt';
    $kursanmeldungen = [];
    
    // Überprüfung ob die Datei existiert
    if (file_exists($filePath)) {
        
        // Datei öffnen
        $handle = fopen($filePath, 'r');
        
        // Daten aus Datei auslesen soland der Zeiger nicht am Ende der Datei angelangt ist
        while ( !feof($handle) ) {
            // Zeile aus Datei auslesen und in Variable $zeile speichern
            $zeile = fgets($handle);
            
            // $zeile anhander des Zeichens ';' auf mehrere Teile aufteilen
            //$werte = explode(';', $zeile);
            
            if (!empty($zeile)) {
                // $zeile deserialisieren
                $werte = unserialize($zeile);

                // Eine neue Kursanmeldung zum Array $kursanmeldungen hinzufügen
                $kursanmeldungen[] = $werte;
            }
        }
        
        // Datei schließen
        fclose($handle);
    } else {
        echo "Die Datei $filePath konnte nicht gefunden werden.";
    }
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Kursanmeldungen</title>
   </head>
    <body>
        <h1>Kursanmeldungen</h1>
        <table>
            <thead>
                <tr>
                    <th>Kurs ID</th>
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th>Anrede</th>
                    <th>E-Mail</th>
                    <th>Geburtsdatum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kursanmeldungen as $kursanmeldung): ?>
                <tr>
                    <td><?= $kursanmeldung['kurs_id'] ?></td>
                    <td><?= $kursanmeldung['vorname'] ?></td>
                    <td><?= $kursanmeldung['nachname'] ?></td>
                    <td><?= $kursanmeldung['anrede'] ?></td>
                    <td><?= $kursanmeldung['email'] ?></td>
                    <td><?= $kursanmeldung['geburtsdatum'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
</html>