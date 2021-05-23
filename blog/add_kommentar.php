<?php
    require_once 'config.php';
    
    if (isPostRequest()) {
        $beitragId = formFieldValue('beitrag_id', 0);
        $text = formFieldValue('kommentar', '');
        $user = getUserFromSession();
        
        // einfache Validierung
        if ($beitragId > 0 && !empty($text) && $user) {
            $userId = $user['user_id'];
            
            $conn = connectToDB();
            
            $query = 'INSERT INTO kommentar (text, beitrag_id, user_id) VALUES (?, ?, ?)';
            
            $stmt = mysqli_prepare($conn, $query);
            
            mysqli_stmt_bind_param($stmt, "sii", $text, $beitragId, $user['user_id']);
            
            if (mysqli_stmt_execute($stmt)) {
                $kommentar = [
                    'kommentar_id' => mysqli_stmt_insert_id($stmt),
                    'vorname' => $user['vorname'],
                    'nachname' => $user['nachname'],
                    'text' => $text,
                    'erstellt_am' => date('Y-m-d H:i:s'),
                    'user_id' => $userId
                ];
                
                // Ausgabe des HTML für den Kommentar
                require BASE_PATH . '/inc/kommentar.php';
            }
            closeDB($conn);
        } else {
            die('Ungülter Request');
        }
    }