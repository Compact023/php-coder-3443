<?php
    session_start();
    
    $_SESSION = [];
    
    // Sitzung beenden
    session_destroy();
    
    // Weiterleitung auf index.php
    header('Location: index.php');