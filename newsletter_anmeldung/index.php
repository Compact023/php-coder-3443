<?php
session_start();

require_once 'functions.php';

$formData = [
    'ef' => '',
    'ts' => time()
];
$validationErrors = [];
$newsletterRegistrationOk = false;

if (isPostRequest()) {
    $formData = [
        'vorname' => formFieldValue('vorname', '', true),
        'nachname' => formFieldValue('nachname', '', true),
        'email' => formFieldValue('email', '', true),
        'ef' => formFieldValue('ef', ''),
        'ts' => formFieldValue('ts', time()),
        'csrfToken' => formFieldValue('csrfToken', '', true)
    ];
    
    $validations = [
        'vorname' => [
            'not_empty' => [
                'error_msg' => 'Bitte gib deinen Vornamen ein.',
            ],
        ],
        'nachname' => [
            'not_empty' => [
                'error_msg' => 'Bitte gib deinen Nachnamen ein.'
            ],
        ],
        'email' => [
            'not_empty' => [
                'error_msg' => 'Bitte gib deine Email-Adresse ein.'
            ],
            'email' => [
                'error_msg' => 'Bitte gib eine gültige Email-Adresse ein.'
            ]
        ],
        'ef' => [
            'empty' => [
                'error_key' => 'allgemein',
                'error_msg' => 'Die eingegebenen Werte sind ungültig.'
            ],
        ],
        'ts' => [
            'not_empty' => [
                'error_key' => 'allgemein',
                'error_msg' => 'Die eingegebenen Werte sind ungültig.'
            ],
            'ts_duration' => [
                'duration' => 3,
                'error_key' => 'allgemein',
                'error_msg' => 'Die eingegebenen Werte sind ungültig.'
            ],
        ],
        'csrfToken' => [
            'not_empty' => [
                'error_key' => 'allgemein',
                'error_msg' => 'Die eingegebenen Werte sind ungültig.'
            ],
            'equals' => [
                'compare_to' => (isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''),
                'error_key' => 'allgemein',
                'error_msg' => 'Die eingegebenen Werte sind ungültig.'
            ],
        ]
    ];
    
    // Wenn Validierung erfolgreich war
    if (validate($formData, $validations, $validationErrors)) {
        
        // Verbindung zur DB herstellen
        $conn = connectToDB();
        
        // Prüfen ob es die E-Mail Adresse bereits gibt
        $query = 'SELECT * FROM anmeldung WHERE email like ?';
        
        // Prepared Statement erstellen
        $stmt = mysqli_prepare($conn, $query);
        
        // ? durch Werte/Parameter ersetzen
        mysqli_stmt_bind_param($stmt, "s", $formData['email']);
        
        // Prepared Statement ausführen
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        
        // Gibt es bereits eine Newsletter-Anmeldung mit der eingegebenen E-Mail Adresse?
        if ($result && mysqli_num_rows($result) == 1) {
            $anmeldung = mysqli_fetch_assoc($result);
            
            // Wenn der Benutzer bereits zum Newsletter angemeldet ist
            if ($anmeldung['status'] == 'subscribed') {
                $validationErrors['allgemein'] = 'Du bist bereits zum Newsletter angemeldet.';
            } else {
                // Wenn es einen Verify-Link bzw. Verify-Datum gibt
                if ($anmeldung['verify_until'] && $anmeldung['verify']) {
                    $verifyUntil = strtotime($anmeldung['verify_until']);
                    
                    // Wenn der Link bereits abgelaufen ist
                    if ($verifyUntil <= time()) {
                        // Generiere einen neuen Verify-Parameter
                        $verify = uniqid('', true);
                    } else {
                        // Der Verify-Parameter blibt bestehen
                        $verify = $anmeldung['verify'];
                    }
                } else {
                    // Generiere einen neuen Verify-Parameter
                    $verify = uniqid('', true);
                }
                
                // VerifyUntil Datum auf aktulles Datum + 1 Tag setzen.
                $verifyUntil = date('Y-m-d H:i:s', time() + 24*60*60); 
                
                // SQL-Statement
                $query = 'UPDATE anmeldung SET verify = ?, verify_until = ? WHERE id = ?';
                
                // Prepared Statement erstellen
                $stmt = mysqli_prepare($conn, $query);
                
                // ? durch Werte/Parameter ersetzen
                mysqli_stmt_bind_param($stmt, "ssi", $verify, $verifyUntil, $anmeldung['id']);
                
                // SQL Statement ausführen
                if (mysqli_stmt_execute($stmt)) {
                    // Email versenden
                    $toAddress = $formData['email'];
                    $toName = $formData['vorname'] . ' ' . $formData['nachname'];
                    $subject = 'Bestätigung Newsletter-Anmeldung';
                    $body = "<h3>Vielen Dank für deine Anmeldung!</h3>"
                            . "<p>Klicke bitte hier um die Anmeldung abzuschließen:"
                            . "<a href='" . BASE_URL . "confirm.php?verify=$verify'>" . BASE_URL . "confirm.php?verify=$verify</a>"
                            . "</p>";

                    // Sende Email
                    if (sendMail($toAddress, $toName, $subject, $body)) {
                        $newsletterRegistrationOk = true;
                        unset($_SESSION['csrf_token']);
                    } else {
                        // Wenn beim E-Mail versenden ein Fehler aufgetreten ist
                        $validationErrors['allgemein'] = 'Beim Senden der Bestätigungsmail ist ein Fehler aufgetreten.';
                    }
                } else {
                    // Wenn beim SQL Statement ein Fehler aufgetreten ist
                    $validationErrors['allgemein'] = 'Beim Speichern der Newsletteranmeldung ist ein Fehler aufgetreten.';
                }
            }
        } else {
            // 1. Daten in Datenbank speichern
            $query = 'INSERT INTO anmeldung (vorname, nachname, email, anmeldedatum, status, verify, verify_until) VALUES (?,?,?,?,?,?,?)';

            // Prepared Statement erstellen
            $stmt = mysqli_prepare($conn, $query);

            $anmeldedatum = date('Y-m-d H:i:s');
            $status = 'unverified';
            $verify = uniqid('', true);
            $verifyUntil = date('Y-m-d H:i:s', time() + 24*60*60);

            // ? durch Werte/Parameter ersetzen
            mysqli_stmt_bind_param($stmt, "sssssss", $formData['vorname'], $formData['nachname'], $formData['email'], $anmeldedatum, $status, $verify, $verifyUntil);

            if (mysqli_stmt_execute($stmt)) {
                // 2. Email versenden
                $toAddress = $formData['email'];
                $toName = $formData['vorname'] . ' ' . $formData['nachname'];
                $subject = 'Bestätigung Newsletter-Anmeldung';
                $body = "<h3>Vielen Dank für deine Anmeldung!</h3>"
                        . "<p>Klicke bitte hier um die Anmeldung abzuschließen:"
                        . "<a href='" . BASE_URL . "confirm.php?verify=$verify'>" . BASE_URL . "confirm.php?verify=$verify</a>"
                        . "</p>";

                // Sende Email
                if (sendMail($toAddress, $toName, $subject, $body)) {
                    $newsletterRegistrationOk = true;
                    unset($_SESSION['csrf_token']);
                } else {
                    // Wenn beim E-Mail versenden ein Fehler aufgetreten ist
                    $validationErrors['allgemein'] = 'Beim Senden der Bestätigungsmail ist ein Fehler aufgetreten.';
                }
            }  else {
                // Wenn beim SQL Statement ein Fehler aufgetreten ist
                $validationErrors['allgemein'] = 'Beim Speichern der Newsletteranmeldung ist ein Fehler aufgetreten.';
            }
        }
        
        // Datenbankverbindung beenden
        closeDB($conn);
    }
} else if (isGetRequest()) {
    $formData['csrfToken'] = uniqid('');
    $_SESSION['csrf_token'] = $formData['csrfToken'];    
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
        <title>Newsletter Anmeldung</title>
    </head>
    <body>
        <h1>Newsletter Anmeldung</h1>
        <?php if ($newsletterRegistrationOk): ?>
        <p>
            <strong>Vielen Dank für deine Anmeldung!</strong><br>
            Du solltest in kürze eine Email mit einem Bestätigungslink bekommen, über dass du deine Anmeldung noch bestätigen musst.
        </p>
        <?php else: ?>
            <?php if (isset($validationErrors['allgemein'])): ?>
                <div class="error-message"><?= $validationErrors['allgemein'] ?></div>
            <?php endif; ?>
        
            <form action="index.php" method="POST">
                <div class="form-field">
                    <label for="vorname">Vorname</label>
                    <input type="text" id="vorname" name="vorname" value="<?= @$formData['vorname'] ?>" required>
                    
                    <?php if (isset($validationErrors['vorname'])): ?>
                        <div class="error-message"><?= $validationErrors['vorname'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-field">
                    <label for="nachname">Nachname</label>
                    <input type="text" id="nachname" name="nachname" value="<?= @$formData['nachname'] ?>" required>
                    
                    <?php if (isset($validationErrors['nachname'])): ?>
                        <div class="error-message"><?= $validationErrors['nachname'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= @$formData['email'] ?>" required>
                    
                    <?php if (isset($validationErrors['email'])): ?>
                        <div class="error-message"><?= $validationErrors['email'] ?></div>
                    <?php endif; ?>
                </div>

                <input type="hidden" name="ef" value="<?= @$formData['ef'] ?>">
                <input type="hidden" name="ts" value="<?= @$formData['ts'] ?>">
                <input type="hidden" name="csrfToken" value="<?= @$formData['csrfToken']; ?>">
                
                <button type="submit">Anmelden</button>
            </form>
        <?php endif; ?>
    </body>
</html>
