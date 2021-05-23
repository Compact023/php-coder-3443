<?php
    session_start();
    
    require_once 'functions.php';

    // Überpüfung ob es ein User-Element in der Session gibt
    if (!isSignedIn()) {
        // Weiterleitung auf login.php
        header('Location: login.php');

        // Beenden der PHP Ausführung
        exit(); //die()
    }
    
    // sicheres Passwort geneieren (HASH)
    //echo password_hash("pwd4max", PASSWORD_DEFAULT);
?>
<h1>Index</h1>
<p>Sie sind angemeldet als <?php echo $_SESSION['user']['name']; ?>.</p>
<p>
    ID: <?= $_SESSION['user']['user_id'] ?><br>
    Name: <?= $_SESSION['user']['name'] ?><br>
    Rolle: <?= $_SESSION['user']['rolle_id'] ?><br>
    E-Mail: <?= $_SESSION['user']['email'] ?>
</p>
<p><a href="logout.php">logout</a></p>
