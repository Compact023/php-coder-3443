<?php

require_once 'config.php';

function connectToDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    // Überprüfen, ob die Verbindung zur Datenbank nicht aufgebaut werden konnte
    if (!$conn) {
        die('Es konnte keine DB-Verbindung hergestellt werden ' . $conn->connect_error);
    }

    $conn->set_charset(DB_CHARSET);
    
    return $conn;
}

function closeDB($conn) {
    $conn->close();
}

function getParameter($name, $default = '') {
    return (isset($_GET[$name]) ? $_GET[$name] : $default);
}