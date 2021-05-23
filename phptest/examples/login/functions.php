<?php

require_once 'config.php';

/**
 * Erstellt eine Datenbankverbindung und gibt die Verbindung zurück
 * 
 * @return type
 */
function connectToDB() {
    // Verbindung zur Datenbank aufbauen
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Überprüfen, ob die Verbindung zur Datenbank nicht aufgebaut werden konnte
    if (!$conn) {
        // Ausführung beenden und Fehlermeldung ausgeben
        die('Es konnte keine DB-Verbindung hergestellt werden ' . mysqli_connect_error());
    }

    // Überprüfen ob der Zeichensatz für die Verbindung zur DB nicht gesetzt werden konnte
    if (!mysqli_set_charset($conn, 'utf8mb4')) {
        die('Der Zeichensatz für die Verbindung zur DB konnte nicht gesetzt werden.');
    }

    return $conn;
}

/**
 * Schließt die Datenbankverbindung
 * 
 * @param type $conn Datenbankverbindung
 */
function closeDB(&$conn) {
    if ($conn) {
        // Datenbankverbindung schließen
        mysqli_close($conn);
    }
}

/**
 * Gibt zurück ob ein Benutzer angemeldet ist oder nicht
 * 
 * @return bool
 */
function isSignedIn() : bool {
    return isset($_SESSION['user']);
}

/**
 * Liest Benutzer aus einer Datei und und gibt sie als Array zurück
 * 
 * @param string $fileName Pfad zur Datei
 * @return array
 */
function readUsersFromFile(string $fileName) : array {
    $users = [];
    
    // Überprüfung ob Pfad existiert und ob Pfad eine Datei ist
    if (file_exists($fileName) && is_file($fileName)) {
        // Öffnen der Datei zum Lesen
        $handle = fopen($fileName, 'r');
        
        // Überprüfen ob das Öffnen der Datei funktioniert hat
        if ($handle) {
            // Variable zum Zählen der eingelesenen Zeilen
            $cntRows = 0;
            
            while (!feof($handle)) {
                // Zeile einlesen und anhand von einem Trennzeichen aufsplitten
                $daten = fgetcsv($handle, 0, ';');
                
                // Zeilenzählen erhöhen
                $cntRows++;
                
                // ist das die erste Zeile?
                if ($cntRows === 1) continue;
                
                // Wenn keine leere Zeile eingelesen wurde
                if ($daten) {
                    // Daten zu den Usern hinzufügen mit der Email-Adresse als Schlüssel
                    $users[$daten[3]] = [
                        'id' => $daten[0],
                        'vorname' => $daten[1],
                        'nachname' => $daten[2],
                        'email' => $daten[3],
                        'passwort' => $daten[4]
                    ];
                    
                    /*$users['max.mustermann@test.at'] = [
                        'id' => 1,
                        'vorname' => 'Max',
                        'nachname' => 'Mustermann',
                        'email' => 'max.mustermann@test.at',
                        'passwort' => 'pwd4max'
                    ]*/
                }
            }
            
            // Datei schließen
            fclose($handle);
        }
    }
    
    return $users;
}

function getUserIP() {
    if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
            $addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($addr[0]);
        } else {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
    else {
        return $_SERVER['REMOTE_ADDR'];
    }
}