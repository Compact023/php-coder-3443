<?php

require_once 'form_config.php';

/**
 * Funktion zum Upload eines Files
 * 
 * @param string $feldName
 * @param string $zielVerzeichnis
 * @param array $validationErrors
 * @param int $maxFileSize
 * @param array $allowedFileTypes
 * @return boolean|string
 */
function uploadFile(string $feldName, string $zielVerzeichnis, array &$validationErrors, int $maxFileSize = MAX_FILESIZE, array $allowedFileTypes = ALLOWED_FILE_TYPES) {
    // 1. Check ob file-upload enthalten ist und ob eine Datei hochgeladen wurde
    if (isset($_FILES[$feldName]) && $_FILES[$feldName]['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadErrors = [];
        
        // 2. Verzeichnis und Dateinamen festlegen
        $dateiname = mb_strtolower(basename($_FILES[$feldName]['name']));
        $dateipfad = $zielVerzeichnis . '/' . $dateiname;
        
        // 3. Gibt es bereits eine Datei mit den gleichen Namen/Pfad
        if (file_exists($dateipfad)) {
            $uploadErrors[] = 'Es existiert bereits eine Datei mit diesem Dateinamen.';
        }
        
        // 4. Überprüfung der Dateigröße
        if ($_FILES[$feldName]['size'] == 0 || $_FILES[$feldName]['size'] > $maxFileSize) {
            $maxFileSizeMB = $maxFileSize / (1024 * 1024);
            $uploadErrors[] = 'Die Datei ist zu groß (max. ' . number_format($maxFileSizeMB, 1, ',', '.') . ' MB)';
        }
        
        // 5. Überprüfung des Dateiformats (Ist das Dateiformat nicht in den erlaubten Formaten enthalten)
        // dateiname = uv.xy.png
        $dateinamensTeile = explode('.', $dateiname);
        // dateinamensTeile = ['uv', 'xy', 'png']
        // count($dateinamensTeile) - 1  => Index vom letzten Element (Anzahl der Elemente im Array - 1)
        // 2 - 1 => Index vom letzten Element wäre 1
        $letzterIndex = count($dateinamensTeile) - 1;
        if (!in_array($dateinamensTeile[$letzterIndex], $allowedFileTypes)) {
            $uploadErrors[] = 'Das Dateiformat ist nicht erlaubt (erlaubt sind: ' . strtoupper(implode(', ', $allowedFileTypes)) . ')';
        }
        
        // 6. Check ob Upload wirklich funktioniert hat
        if ($_FILES[$feldName]['error'] != UPLOAD_ERR_OK) {
            $uploadErrors[] = 'Beim Upload ist ein Fehler aufgetreten.';
        }
        
        // 7. Datei an den gewünschten Zielort übertragen
        if (count($uploadErrors) == 0) {
            // Verschieben der Datei von aktuellen Ort (temp Verzeichnis) zum Zielort/Pfad
            move_uploaded_file($_FILES[$feldName]['tmp_name'], $dateipfad);
            return $dateipfad;
        }
        
        $validationErrors[$feldName] = $uploadErrors;
    }
    return false;
}

/**
 * Prüft, ob eine Datei ein Bild ist
 * 
 * @param string $dateipfad Pfad zur Datei
 * @return bool
 */
function fileIsImage(string $dateipfad) : bool {
    // Überprüfen ob der $dateipfad überhaupt existiert und ob das eine Datei (und kein Verzeichnis) ist
    if (file_exists($dateipfad) && is_file($dateipfad)) {
        
        // Variante 1: explode
        /*$dateiPfadTeile = explode('.', $dateipfad);
        $letzterIndex = count($dateiPfadTeile) - 1;
        if (in_array($dateiPfadTeile[$letzterIndex], IMAGE_FILE_TYPES)) {
            return true;
        }*/
        
        // Variante 2: strrpos
        $punktPosition = strrpos($dateipfad, '.');
        $dateiEndung = substr($dateipfad, $punktPosition + 1);
        if (in_array($dateiEndung, IMAGE_FILE_TYPES)) {
            return true;
        }
    }
    return false;
}