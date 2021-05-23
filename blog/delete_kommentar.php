<?php
    require_once 'config.php';

    if (isSignedIn()) {
        $kommentarId = (isset($_GET['id']) ? $_GET['id'] : 0);
        
        $user = getUserFromSession();
        
        $conn = connectToDB();
        
        $query = 'DELETE FROM kommentar WHERE kommentar_id = ? AND user_id = ?';
        
        $stmt = mysqli_prepare($conn, $query);
        
        mysqli_stmt_bind_param($stmt, 'ii', $kommentarId, $user['user_id']);
        
        mysqli_stmt_execute($stmt);
        
        closeDB($conn);
    }
