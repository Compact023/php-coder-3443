<?php
    $text = '';
    $suchbegriff = '';
    $anzahlVorkommen = 0;
    $textMarkierungen = '';
    
    // ist das Formular mit einem POST Request abgesendet worden?
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $text = (isset($_POST['text']) ? $_POST['text'] : '');
        $suchbegriff = (isset($_POST['term']) ? $_POST['term'] : '');

        $anzahlVorkommen = substr_count($text, $suchbegriff);
        $textMarkierungen = str_replace($suchbegriff, "<b>$suchbegriff</b>", $text);
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
        <title>Suche nach Text</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Suche nach Text</h1>
        <form action="form_search_term.php" method="POST">
            <div class="form-field">
                <label for="text">Text</label>
                <textarea id="text" name="text" rows="10" cols="160"><?= $text ?></textarea>
            </div>
            
            <div class="form-field">
                <label for="term">Suche nach:</label>
                <input type="search" id="term" name="term" value="<?= $suchbegriff ?>">
            </div>
            
            <button type="submit">Zeichenkette suchen</button>
        </form>
        
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p>Suche nach "<b><?= $suchbegriff ?></b>": <?= $anzahlVorkommen ?> Mal gefunden.</p>
            <p><?= nl2br($textMarkierungen) ?></p>
        <?php endif; ?>
    </body>
</html>