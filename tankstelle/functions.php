<?php    
    /**
     * Überprüft, ob der Request ein GET Request ist
     * @return bool
     */
    function isGetRequest() : bool {
        return ($_SERVER['REQUEST_METHOD'] === 'GET');
    }
    
    /**
     * Liest Parameterwert aus dem GET Formular aus oder gibt einen Standard-Wert zurück.
     * Zusätzlich kann der Wert auch getrimmt werden.
     * 
     * @param string $fieldName FeldName (Parametername)
     * @param type $defaultValue Standardwert
     * @param bool $doTrim Flag ob der Wert getrimmt werden soll
     * @return type
     */
    function formFieldValueGET(string $fieldName, $defaultValue, bool $doTrim = true) {
        $value = (isset($_GET[$fieldName]) ? $_GET[$fieldName] : $defaultValue);
        
        if ($doTrim) {
            return trim($value);
        }
        return $value;
    }