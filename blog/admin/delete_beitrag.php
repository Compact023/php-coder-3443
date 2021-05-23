<?php
    require_once '../config.php';
    
    // Wenn der Benutzer nicht angemeldet ist
    if (!isSignedIn()) {
        // Weiterleitung zur Login-Seite
        header('Location: ' . BASE_URL . 'admin/login.php');
    }
    
    // User aus Session holen
    $user = getUserFromSession();
    
    $beitragId = (isset($_GET['id']) ? $_GET['id'] : 0);
    
    if ($beitragId) {
        // Verbindung zur Datenbank herstellen
        $conn = connectToDB();

        $sql = 'DELETE FROM beitrag WHERE beitrag_id = ?';
        
        $stmt = mysqli_prepare($conn, $sql);
        
        mysqli_stmt_bind_param($stmt, "i", $beitragId);
        
        mysqli_stmt_execute($stmt);

        closeDB($conn);
    }
    
    
    // Weiterleitung
    header('Location: ' . BASE_URL . 'admin/index.php');