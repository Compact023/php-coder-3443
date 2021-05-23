<?php

/**
 * Prüft die Eingabewerte und gibt ein Array mit Fehlermeldung zurück
 * Wenn keine Fehler gefunden wurden ist das Array leer.
 * 
 * @param string $kurs
 * @param string $anrede
 * @param string $vorname
 * @param string $nachname
 * @param string $email
 * @param string $geburtsdatum
 * @return array
 */
function validiereEingaben(string $kurs, string $anrede, string $vorname, string $nachname, string $email, string $geburtsdatum) : array {
    $validationErrors = [];
    
    // Wenn die Variable $kurs leer (kein Inhalt oder ein leerer Text) ist
    if (empty($kurs)) {
        $validationErrors['kurs'] = 'Bitte wählen Sie einen Kurs aus.';
    }
    // Wenn die Variable $anrede leer (kein Inhalt oder ein leerer Text) ist
    if (empty($anrede)) {
        $validationErrors['anrede'] = 'Bitte wählen Sie eine Anrede aus.';
    }
    // Wenn die Variable $vorname leer (kein Inhalt oder ein leerer Text) ist
    if (empty($vorname)) {
        $validationErrors['vorname'] = 'Bitte geben Sie Ihren Vornamen ein.';
    }
    // Wenn die Variable $nachname leer (kein Inhalt oder ein leerer Text) ist
    if (empty($nachname)) {
        $validationErrors['nachname'] = 'Bitte geben Sie Ihren Nachnamen ein.';
    }
    // Wenn die Variable $email leer (kein Inhalt oder ein leerer Text) ist
    if (empty($email)) {
        $validationErrors['email'] = 'Bitte geben Sie Ihre Email Adresse ein.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Wenn die Variable $email keine gültige Email Adresse enthält
        $validationErrors['email'] = 'Bitte geben Sie eine gültige Email Adresse ein.';
    }
    // Wenn die Variable $geburtsdatum leer (kein Inhalt oder ein leerer Text) ist
    if (empty($geburtsdatum)) {
        $validationErrors['geburtsdatum'] = 'Bitte geben Sie Ihr Geburtsdatum ein.';
    }
    //ToDo: Plausibilitätsüberprüfung des Geburtsdatums
    
    return $validationErrors;
}

/**
 * Liest die Kurse aus einer Datei aus und gibt sie als Array zurück
 * 
 * @param string $dateiname Dateiname bzw. Pfad aus dem die Kurse ausgelesen werden
 * @param string $trennzeichen Trennzeichen mit dem Kursnummer und Kurstritel getrennt sind
 * @return array Kurse-Array
 */
function getKurseVonDatei(string $dateiname, string $trennzeichen = ';') : array {
    $kurse = [];
    
    // Überprüfen ob die Datei existiert
    if (file_exists($dateiname)) {
        // Datei zum Lesen öffnen
        $handle = fopen($dateiname, 'r');

        $zeilenNr = 0;

        // Solange der Dateizeiger nicht am Ende der Datei steht
        while ( !feof($handle) ) {
            // Lesen einer Zeile aus der Datei
            $zeile = fgets($handle);
            $zeilenNr++;

            // Wenn es die erste Zeile ist, wollen wir diese überspringen
            if ($zeilenNr == 1) {
                continue;
            }

            // Wenn die Zeile nicht leer ist (meist ist die letzte Zeile in einem CSV nämlich leer)
            if (!empty($zeile)) {
                // Aufsplitten der Zeile anhand des Trennzeichens
                $spalten = explode($trennzeichen, $zeile);
                // Hinzufügen des Kurses zum Kurs-Array $kurse
                $kurse[] = [
                    'kurs_nummer' => $spalten[0],
                    'kurs_titel' => $spalten[1]
                ];
            }
        }

        // Datei schließen
        fclose($handle);
    }
    
    return $kurse;
}

/**
 * Speichert eine Kursanmeldung in der angegebenen Datei
 * 
 * @param string $dateiname
 * @param string $kurs
 * @param string $anrede
 * @param string $vorname
 * @param string $nachname
 * @param string $email
 * @param string $geburtsdatum
 */
function speichereAnmeldungInDatei(string $dateiname, string $kurs, string $anrede, string $vorname, string $nachname, string $email, string $geburtsdatum) {
    $kursanmeldung = [
        'kurs_id' => $kurs,
        'anrede' => $anrede,
        'vorname' => $vorname,
        'nachname' => $nachname,
        'email' => $email,
        'geburtsdatum' => $geburtsdatum
    ];

    // Daten in einer Zeile zusammenfassen
    //$zeile = $kurs . ';' . $anrede . ';' . $vorname . ';' . $nachname . ';' . $email . ';' . $geburtsdatum . "\n";
    $zeile = serialize($kursanmeldung) . "\n"; // Alternativ zu "\n" könnte auch PHP_EOL verwendet werden

    // Zeile in eine Datei speichern
    file_put_contents($dateiname, $zeile, FILE_APPEND);
}