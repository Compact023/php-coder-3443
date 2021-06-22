<?php

function connectToDB(&$conn) {
    // Verbindung zur Datenbank aufbauen
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Überprüfen, ob die Verbindung zur Datenbank nicht aufgebaut werden konnte
    if (true <> $conn && !$conn) { //true <> $conn bedeutet das selbe wie !$conn
        // Ausführung beenden und Fehlermeldung ausgeben
        die('Es konnte keine DB-Verbindung hergestellt werden ' . $conn->connect_error);
    }

    // Überprüfen ob der Zeichensatz für die Verbindung zur DB nicht gesetzt werden konnte
    if (!$conn->set_charset(DB_CHARSET)) {
        die('Der Zeichensatz für die Verbindung zur DB konnte nicht gesetzt werden.');
    }

    // return $conn;
}

function closeDB(&$conn) {
    if ($conn) {
        // Datenbankverbindung schließen
        $conn->close();
    }
}

/**
 * Überprüft, ob der Request ein GET Request ist
 * @return bool
 */
function isGetRequest(): bool {
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
function formFieldValueGet(string $fieldName, $defaultValue, bool $doTrim = true) {
    $value = (isset($_GET[$fieldName]) ? $_GET[$fieldName] : $defaultValue);

    if ($doTrim) {
        return trim($value);
    }
    return $value;
}
