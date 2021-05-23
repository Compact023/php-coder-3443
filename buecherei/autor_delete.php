<?php

require_once 'functions.php';

$buchId = (isset($_GET['id']) ? $_GET['id'] : 0);

if ($buchId) {
    // Datenbank-Verbindung aufbauen
    $conn = connectToDB();

    $query = "DELETE FROM autor WHERE autor_id = $buchId";
    
    // SQL Statement an die Datenbank senden
    mysqli_query($conn, $query);
    
    // Datenbank-Verbindung schließen
    closeDB($conn);
}

// Weiterleitung
header('Location: index.php');