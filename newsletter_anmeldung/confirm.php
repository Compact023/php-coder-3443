<?php

require_once 'functions.php';
$verify = (isset($_GET['verify']) ? $_GET['verify'] : false);
$confirmationOk = false;

if ($verify) {
    $conn = connectToDB();
    
    // entsprechenden Datensatz anhand des Verify-Parameters suchen
    $query = 'SELECT * FROM anmeldung WHERE verify = ?';
    
    // Prepared Statement erstellen
    $stmt = mysqli_prepare($conn, $query);
    
    // ? durch Werte/Parameter ersetzen
    mysqli_stmt_bind_param($stmt, "s", $verify);
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) == 1) {
        $anmeldung = mysqli_fetch_assoc($result);
        
        // ist der Anmelde-Link noch gültig?
        $aktuellesDatum = time();
        $verifyUntil = strtotime($anmeldung['verify_until']);
        
        if ($aktuellesDatum < $verifyUntil) {
            // Update
            $query = 'UPDATE anmeldung SET status = ?, verify = ?, verify_until = ? WHERE id = ?';
            $newStatus = 'subscribed';
            $newVerify = null;
            $newVerifyUntil = null;
            
            // Prepared Statement erstellen
            $stmt = mysqli_prepare($conn, $query);

            // ? durch Werte/Parameter ersetzen
            mysqli_stmt_bind_param($stmt, "sssi", $newStatus, $newVerify, $newVerifyUntil, $anmeldung['id']);
            
            if (mysqli_stmt_execute($stmt)) {
                // 2. Email versenden
                $toAddress = $anmeldung['email'];
                $toName = $anmeldung['vorname'] . ' ' . $anmeldung['nachname'];
                $subject = 'Newsletter-Anmeldung abgeschlossen';
                $body = "<h3>Vielen Dank für deine Anmeldung!</h3>"
                        . "<p>Deine Anmeldung ist hiermit abgeschlossen.</p>";

                if (sendMail($toAddress, $toName, $subject, $body)) {
                    $confirmationOk = true;
                }
            }
        }
    }
    
    closeDB($conn);
}

?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Newsletter Bestätigung</title>
    </head>
    <body>
        <h1>Newsletter Bestätigung</h1>
        <?php if ($confirmationOk): ?>
            <p>Deine Anmeldung ist jetzt abgeschlossen.</p>
        <?php else: ?>
            <p>Der Link ist ungültig.</p>
        <?php endif; ?>
    </body>
</html>