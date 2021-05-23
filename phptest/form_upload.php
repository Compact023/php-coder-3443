<?php

require_once 'form_functions.php';

$validationErrors = [];
$dateipfad = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    print_r($_FILES);
    
    $dateipfad = uploadFile('file-upload', 'uploads', $validationErrors);
    //$dateipfad = uploadFile('file-upload', 'uploads', $validationErrors, 500 * 1024);
    //$dateipfad = uploadFile('file-upload', 'uploads', $validationErrors, 500 * 1024, ['pdf', 'txt']);
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
        <title>Upload Formular</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Upload Formular</h1>
        <?php if ($dateipfad): ?>
            <p>Der Upload war erfolgreich:</p>
            <?php if (fileIsImage($dateipfad)): ?>
                <img src="<?= $dateipfad ?>" alt="">
            <?php else: ?>
                <a href="<?= $dateipfad ?>" target="_blank">Datei ansehen</a>
            <?php endif; ?>
        <?php else: ?>
            <form action="form_upload.php" method="POST" enctype="multipart/form-data">
                <?php if (isset($validationErrors['file-upload']) && count($validationErrors['file-upload']) > 0): ?>
                    <?php foreach ($validationErrors['file-upload'] as $validationError): ?>
                        <div class="error-message"><?= $validationError ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <label for="file-upload">Datei-Upload</label>
                <input type="file" id="file-upload" name="file-upload">
                <button type="submit">Upload</button>
            </form>
        <?php endif; ?>
    </body>
</html>