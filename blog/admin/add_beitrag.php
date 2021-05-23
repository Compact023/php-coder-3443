<?php 
    require_once '../config.php';
    
    // Wenn der Benutzer nicht angemeldet ist
    if (!isSignedIn()) {
        // Weiterleitung zur Login-Seite
        header('Location: ' . BASE_URL . 'admin/login.php');
    }
    
    // User aus Session holen
    $user = getUserFromSession();
    
    // Verbindung zur Datenbank herstellen
    $conn = connectToDB();
    $validationErrors = [];
    
    // Wenn das Formular abgesendet wurde -> POST Request
    if (isPostRequest()) {
        // Bild hochladen
        $bild = uploadFile('bild', BASE_PATH . '/uploads', $validationErrors);
        
        // Formulardaten auslesen
        $formData = [
            'titel' => formFieldValue('titel', ''),
            'keywords' => formFieldValue('keywords', ''),
            'text' => formFieldValue('text', ''),
            'kategorien' => formFieldValue('kategorie', [], false),
            'aktiv' => formFieldValue('aktiv', 0, false),
            'bild' => ($bild ? $bild : null)
        ];
        
        // Formulardaten validieren
        $validations = [
            'titel' => [
                'not_empty' => [
                    'error_msg' => 'Bitte geben Sie einen Titel ein.'
                ],
            ],
            'text' => [
                'not_empty' => [
                    'error_msg' => 'Bitte geben Sie einen Text ein.'
                ]
            ],
        ];
        
        // Validierung der Formular Daten
        if (validate($formData, $validations, $validationErrors)) {

            $query = "INSERT INTO beitrag (titel, keywords, text, aktiv, bild, user_id)"
                    ." VALUES (?, ?, ?, ?, ?, ?)";

            // Prepared Statement erstellen
            $stmt = mysqli_prepare($conn, $query);

            // ? durch Parameter in Prepared Statement ersetzen
            mysqli_stmt_bind_param($stmt, "sssisi", $formData['titel'], $formData['keywords'], $formData['text'], $formData['aktiv'], $formData['bild'], $user['user_id']);

            // Prepared Statement ausführen
            // Wenn das Insert erfolgreich war
            if (mysqli_stmt_execute($stmt)) {
                // TODO: Beitrag-Kategorie-Mapping in DB einfügen
                
                // Beitrags-ID des zuletzt eingefügt Beitrags auslesen
                $beitragId = mysqli_stmt_insert_id($stmt);
                
                $query = "INSERT INTO beitrag_has_kategorie (beitrag_id, kategorie_id) VALUES (?, ?)";
                
                // Prepared Statement erstellen
                $stmt = mysqli_prepare($conn, $query);

                // ? durch Parameter in Prepared Statement ersetzen
                mysqli_stmt_bind_param($stmt, "ii", $beitragId, $kategorieId);

                foreach ($formData['kategorien'] as $kategorieId) {
                    mysqli_stmt_execute($stmt);
                }
                
                // DB Verbindung schließen
                closeDB($conn);

                // Weiterleitung auf die index.php
                header('Location: ' . BASE_URL . 'admin/index.php');
            } else {
                $errorMessages['general'] = 'Beim Einfügen in die Datenbank ist ein Fehler aufgetreten';
            }
        }
    }
    
    // Kategorien aus der DB auslesen
    $kategorien = fetchKategorienFromDB($conn);
    
    // Datenbankverbindung schließen
    closeDB($conn);
    
    $pageTitle = 'Blog-Beitrag hinzufügen'; 
?>

<?php include_once BASE_PATH . '/inc/template/head.php'; ?>

<div class="container">
    <h1><?= $pageTitle ?></h1>
    
    <form action="<?= BASE_URL ?>admin/add_beitrag.php" method="POST" enctype="multipart/form-data">
        <div class="form-field">
            <label for="titel">Titel</label>
            <input type="text" id="titel" name="titel" class="form-control" required>
        </div>
        
        <div class="form-field">
            <label for="keywords">Keywords</label>
            <textarea id="keywords" name="keywords" class="form-control"></textarea>
        </div>
        
        <div class="form-field">
            <label for="text">Text</label>
            <textarea id="text" name="text" class="form-control editor" rows="20"></textarea>
        </div>
        
        <div class="form-field">
            <label>Kategorien</label>
            
            <?php foreach ($kategorien as $kategorie): ?>
                <div class="form-check">
                    <input type="checkbox" id="kategorie<?= $kategorie['kategorie_id'] ?>" name="kategorie[]" class="form-check-input" value="<?= $kategorie['kategorie_id'] ?>">
                    <label for="kategorie<?= $kategorie['kategorie_id'] ?>" class="form-check-label"><?= $kategorie['name'] ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    
        <div class="form-field">
            <label for="bild">Bild</label>
            <input type="file" id="bild" name="bild" class="form-control">
        </div>
        
        <div class="form-field form-check">
            <input type="checkbox" id="aktiv" name="aktiv" class="form-check-input" value="1">
            <label for="aktiv" class="form-check-label">Beitrag veröffentlicht?</label>
        </div>
        
        <button type="submit" class="btn btn-primary">Hinzufügen</button>
        <a href="<?= BASE_URL ?>admin/index.php" class="btn btn-secondary">Abbrechen</a>
    </form>
</div>

<?php include_once BASE_PATH . '/inc/template/foot.php'; ?>