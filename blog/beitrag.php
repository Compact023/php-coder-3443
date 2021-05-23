<?php
    require_once 'config.php';
    
    $beitrag = null;
    $kommentare = [];
    
    $beitragId = (int) (isset($_GET['id']) ? $_GET['id'] : 0);
    $user = getUserFromSession();
    $userId = ($user ? $user['user_id'] : 0);
    
    $conn = connectToDB();
    
    $query = "SELECT * FROM beitrag WHERE aktiv = 1 AND beitrag_id = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    
    mysqli_stmt_bind_param($stmt, 'i', $beitragId);
    
    mysqli_stmt_execute($stmt);
    
    $result = mysqli_stmt_get_result($stmt);
    
    // Überprüfung ob SQL Statement erfolgreich war und ob Zeilen zurückgekommen sind
    if ($result && mysqli_num_rows($result) == 1) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            $beitrag = $row;
        }
        
        // Kommentare für den aktuellen Beitrag aus der DB auslesen
        $kommentare = fetchKommentareByBeitragIdFromDB($conn, $beitragId);
    }
    
    closeDB($conn);
    
    $pageTitle = 'Beitrag';
?>

<?php include_once BASE_PATH . '/inc/template/head.php'; ?>

<div class="container">
    <?php if ($beitrag): ?>
        <h1><?= $beitrag['titel'] ?></h1>
        
        <?php if (!empty($beitrag['bild'])): ?>
            <img src="<?= BASE_URL . $beitrag['bild'] ?>" alt="<?= $beitrag['titel'] ?>" class="img-thumbnail">
        <?php endif; ?>
            
        <?= $beitrag['text'] ?>
            
        <h2>Kommentare</h2>
        <div id="kommentar-liste">
            <?php if (count($kommentare) > 0): ?>
                <?php foreach ($kommentare as $kommentar): ?>
                    <?php require BASE_PATH . '/inc/kommentar.php'; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div id="keine-kommentare-gefunden" class="alert alert-dark" role="alert">
                    Keine Kommentare gefunden.
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (isSignedIn()): ?>
            <form id="add-kommentar-form" action="add_kommentar.php" method="POST">
                <div class="form-field">
                    <label for="kommentar">Kommentar</label>
                    <textarea id="kommentar" name="kommentar" rows="5" class="form-control" required></textarea>
                </div>
                
                <input type="hidden" name="beitrag_id" value="<?= $beitragId ?>">
                
                <button id="add-kommentar-btn" class="btn btn-primary">Absenden</button>
            </form>
        
            <script>
                // jQuery event-handler für das Absenden des Formulars
                $('#add-kommentar-form').submit(function(event) {
                    // "Normales" Absenden des Formulars und somit Verlassen der Seite verhindern
                    event.preventDefault();
                    
                    // AJAX Request für das Absenden des Formulars
                    $.ajax({
                        url: $(this).attr('action'),    // Zugriff auf das Attribut 'action' vom Formular
                        data: $(this).serialize(),      // Werte (Eingabefelder) aus dem Formular auslesen
                        method: $(this).attr('method')  // Zugriff auf das Attribut 'method' vom Formular
                    }).done(function(resultat) {
                        // wenn es das Element mit der ID keine-kommentare-gefunden gibt (.length > 0)
                        if ($('#keine-kommentare-gefunden').length > 0) {
                            $('#keine-kommentare-gefunden').hide();   // Element ausblenden
                        }
                        
                        $('#kommentar-liste').append(resultat);  // Kommentar zur Liste der Kommentare hinzufügen
                        $('#kommentar').val('');        // Kommentar-Eingabefeld leeren
                    });
                });
                
                // jQuery event-handler, wenn der Löschen-Button bei einem Kommentar geklickt wurde
                //$('.delete-kommentar').click(function(event) {
                $(document).on('click', '.delete-kommentar', function(event) {
                    // "Normalen" Klick auf den Link und somit Verlassen der Seite verhindern
                    event.preventDefault();
                    
                    // Speichern des Kommentar-HTML Elements (Vorfahre des Links auf den geklickt wurde) in einer Variable
                    var kommentarElement = $(this).parents('.kommentar-element'); 
                    
                    // AJAX Request für das Löschen des Kommentars
                    $.ajax({
                        url: $(this).attr('href'),      // Zugriff auf das Attribut 'href' vom Link
                        method: 'GET'
                    }).done(function(resultat) {
                        kommentarElement.remove();      // Kommentar-HTML-Element löschen
                        
                        // Wenn keine Kommentare mehr vorhanden sind -> Hinweis das keine Kommentare vorhanden sind einblenden
                        if ($('.kommentar-element').length == 0) {
                            $('#keine-kommentare-gefunden').show();
                        }
                    });
                });
            </script>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="alert alert-danger" role="alert">
            Beitrag nicht gefunden.
        </div>
    <?php endif; ?>
</div>

<?php include_once BASE_PATH . '/inc/template/foot.php'; ?>