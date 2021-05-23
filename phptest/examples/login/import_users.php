<?php

require_once 'functions.php';

// Users aus Datei auslesen
$users = readUsersFromFile('users.csv');

// Wenn Users in der Datei enthalten waren -> in DB importieren
if (count($users) > 0) {
    
    // Datenbankverbindung aufbauen
    $conn = connectToDB();
    
    $countImportedUsers = 0;
    $query = "INSERT INTO user (name, email, passwort, rolle_id) VALUES (?, ?, ?, 1)";
    
    // Prepared Statement anlegen
    $stmt = mysqli_prepare($conn, $query);
    
    // ? durch Werte ersetzen
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $passwort);
    
    // Durchlaufen aller User und Einfügen in DB
    foreach ($users as $user) {
        $name = $user['vorname'] . ' ' . $user['nachname'];
        $email = $user['email'];
        $passwort = $user['passwort'];
        
        // Statement ausführen
        mysqli_stmt_execute($stmt);
        
        // Zähler für importierte User erhöhen
        $countImportedUsers++;
    }
    
    // Datenbankverbindung schließen
    closeDB($conn);
    
    echo "$countImportedUsers wurden importiert.";
}
