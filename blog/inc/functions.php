<?php
    session_start();
    
    /**
     * Gibt zurück, ob ein Benutzer angemeldet ist oder nicht
     * 
     * @return bool
     */
    function isSignedIn() : bool {
        return (isset($_SESSION['user']));
    }
    
    function getUserFromSession() {
        return (isset($_SESSION['user']) ? $_SESSION['user'] : false);
    }

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
        if (!mysqli_set_charset($conn, DB_CHARSET)) {
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
    
    function fetchKategorienFromDB(&$conn) {
        $kategorien = [];
        
        $sql = 'SELECT * FROM kategorie ORDER BY name ASC';
        
        $result = mysqli_query($conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            
            while ($row = mysqli_fetch_assoc($result)) {
                $kategorien[] = $row;
            }
        }
        
        return $kategorien;
    }
    
    function fetchBeitraegeFromDB(&$conn, $filterByUserId = 0) {
        $beitraege = [];
        
        if ($filterByUserId) {
            $sql = 'SELECT * FROM beitrag WHERE user_id = ? ORDER BY erstellt_am DESC';
            
            // Prepared Statement erstellen
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $filterByUserId);
        } else {
            $sql = 'SELECT * FROM beitrag ORDER BY erstellt_am DESC';
            
            // Prepared Statement erstellen
            $stmt = mysqli_prepare($conn, $sql);
        }
        
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            
            while ($row = mysqli_fetch_assoc($result)) {
                $beitraege[] = $row;
            }
        }
        
        return $beitraege;
    }
    
    function fetchBeitragFromDB(&$conn, $beitragId) {
        $beitrag = null;
        
        $sql = 'SELECT * FROM beitrag WHERE beitrag_id = ?';

        // Prepared Statement erstellen
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $beitragId);
        
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) == 1) {
            $beitrag = mysqli_fetch_assoc($result);
        }
        
        return $beitrag;
    }
    
    function fetchKategorienByBeitragIdFromDB(&$conn, $beitragId) {
        $kategorien = [];
        
        $sql = 'SELECT k.* FROM kategorie k '
             . 'JOIN beitrag_has_kategorie bhk ON bhk.kategorie_id = k.kategorie_id '
             . 'WHERE bhk.beitrag_id = ?';

        // Prepared Statement erstellen
        $stmt = mysqli_prepare($conn, $sql);
        
        mysqli_stmt_bind_param($stmt, "i", $beitragId);
        
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            
            while ($row = mysqli_fetch_assoc($result)) {
                $kategorien[$row['kategorie_id']] = $row;
            }
        }
        
        return $kategorien;
    }
    
    function fetchKommentareByBeitragIdFromDB(&$conn, $beitragId) {
        $kommentare = [];
        
        $sql = 'SELECT k.*, u.vorname, u.nachname, u.username, u.email FROM kommentar k '
             . 'JOIN user u ON k.user_id = u.user_id '
             . 'WHERE k.beitrag_id = ? '
             . 'ORDER BY k.erstellt_am DESC';

        // Prepared Statement erstellen
        $stmt = mysqli_prepare($conn, $sql);
        
        mysqli_stmt_bind_param($stmt, "i", $beitragId);
        
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        if ($result && mysqli_num_rows($result) > 0) {
            
            while ($row = mysqli_fetch_assoc($result)) {
                $kommentare[] = $row;
            }
        }
        
        return $kommentare;
    }
    
    
    /**
     * Überprüft, ob der Request ein POST Request ist
     * @return bool
     */
    function isPostRequest() : bool {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }
    
    /**
     * Überprüft, ob der Request ein GET Request ist
     * @return bool
     */
    function isGetRequest() : bool {
        return ($_SERVER['REQUEST_METHOD'] === 'GET');
    }
    
    /**
     * Liest Parameterwert aus dem Formular aus oder gibt einen Standard-Wert zurück.
     * Zusätzlich kann der Wert auch getrimmt werden.
     * 
     * @param string $fieldName FeldName (Parametername)
     * @param type $defaultValue Standardwert
     * @param bool $doTrim Flag ob der Wert getrimmt werden soll
     * @return type
     */
    function formFieldValue(string $fieldName, $defaultValue, bool $doTrim = true) {
        $value = (isset($_POST[$fieldName]) ? $_POST[$fieldName] : $defaultValue);
        
        if ($doTrim) {
            return trim($value);
        }
        return $value;
    }
    
    
    /**
     * Funktion zum Validieren von Formulardaten
     * 
     * @param array $formData Formulardaten
     * @param array $validations Validierungen
     * @param array $valdationErrors Fehlermeldungen
     * @return bool
     */
    function validate(array $formData, array $validations, array &$valdationErrors) : bool {
        // Durchlaufe alle Felder und deren Validatoren
        foreach ($validations as $fieldName => $fieldValidations) {
            // Durchlaufe alle Validatoren eines einzelnen Feldes
            foreach ($fieldValidations as $validator => $validatorData) {
                switch ($validator) {
                    case 'not_empty':
                        if (empty($formData[$fieldName])) {
                            $valdationErrors[$fieldName] = $validatorData['error_msg'];
                        }
                        break;
                        
                    case 'min_length':
                        if (strlen($formData[$fieldName]) < $validatorData['min']) {
                            $valdationErrors[$fieldName] = $validatorData['error_msg'];
                        }
                        break;
                }
            }
        }
        
        return (count($valdationErrors) == 0);
    }
    
    /**
     * Escape Funktion für die Ausgabe auf der Seite
     * 
     * @param string $text
     * @return string
     */
    function _e(string $text) : string {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
    
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