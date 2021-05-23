<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once 'config.php';

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
    
    function closeDB(&$conn) {
        if ($conn) {
            // Datenbankverbindung schließen
            mysqli_close($conn);
        }
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
                $errorKey = (isset($validatorData['error_key']) ? $validatorData['error_key'] : $fieldName);
                
                switch ($validator) {
                    case 'empty':
                        if (!empty($formData[$fieldName])) {
                            $valdationErrors[$errorKey] = $validatorData['error_msg'];
                        }
                        break;
                        
                    case 'not_empty':
                        if (empty($formData[$fieldName])) {
                            $valdationErrors[$errorKey] = $validatorData['error_msg'];
                        }
                        break;
                        
                    case 'email':
                        if (!filter_var($formData[$fieldName], FILTER_VALIDATE_EMAIL)) {
                            $valdationErrors[$errorKey] = $validatorData['error_msg'];
                        }
                        break;
                        
                    case 'min_length':
                        if (strlen($formData[$fieldName]) < $validatorData['min']) {
                            $valdationErrors[$errorKey] = $validatorData['error_msg'];
                        }
                        break;
                        
                    case 'ts_duration':
                        if ($formData[$fieldName] + $validatorData['duration'] > time()) {
                            $valdationErrors[$errorKey] = $validatorData['error_msg'];
                        }
                        break;
                        
                    case 'equals':
                        if ($formData[$fieldName] !== $validatorData['compare_to']) {
                            $valdationErrors[$errorKey] = $validatorData['error_msg'];
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
     * Funktion zum Senden einer Email
     * 
     * @param string $toEmail Empfänger-Adresse
     * @param string $toName Empfägner-Name
     * @param string $subject Betreff
     * @param string $body Email-Text
     * @param string $cc CC-Adresse
     * @param string $bcc BCC-Adresse
     * @param array $attachments Datei-Anhänge
     * @return bool
     */
    function sendMail(string $toEmail, string $toName, string $subject, string $body, string $cc = '', string $bcc = '', array $attachments = []) : bool {
        // PHPMailer Objekt anlegen
        $mail = new PHPMailer(true);
        
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = SMTP_HOST;                              //Set the SMTP server to send through
        $mail->SMTPAuth   = SMTP_AUTHENTICATION;                    //Enable SMTP authentication
        $mail->Username   = SMTP_USER;                              //SMTP username
        $mail->Password   = SMTP_PASSWORD;                          //SMTP password
        $mail->SMTPSecure = SMTP_ENCRYPTION;                        //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = SMTP_PORT;                              //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        // Absender und Empfänger hinterlegen
        $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
        $mail->addAddress($toEmail, $toName);

        if (!empty($cc)) {
            $mail->addCC($cc);
        }
        
        if (!empty($bcc)) {
            $mail->addBCC($bcc);
        }
        
        // Attachment hinzufügen
        if (count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                $mail->addAttachment($attachment);
            }
        }

        // Email-Text ist eine HTML-Email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Mail versenden
        return $mail->send();
    }