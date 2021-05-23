<?php 
    require_once '../config.php';
    
    // Wenn der Benutzer nicht angemeldet ist
    if (!isSignedIn()) {
        // Weiterleitung zur Login-Seite
        header('Location: ' . BASE_URL . 'admin/login.php');
    }
    
    $conn = connectToDB();
    
    $beitraege = fetchBeitraegeFromDB($conn);
    
    closeDB($conn);
    
    $pageTitle = 'Meine Blog-Beiträge'; 
?>

<?php include_once BASE_PATH . '/inc/template/head.php'; ?>

<div class="container">
    <h1>Meine Blog-Beiträge</h1>
    
    <?php if (count($beitraege) > 0): ?>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titel</th>
                    <th>Erstellt am</th>
                    <th class="text-center">Veröffentlicht ?</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($beitraege as $beitrag): ?>
                <tr>
                    <td><?= $beitrag['beitrag_id'] ?></td>
                    <td><?= $beitrag['titel'] ?></td>
                    <td><?= $beitrag['erstellt_am'] ?></td>
                    <td class="text-center"><?= ($beitrag['aktiv'] ? 'Ja' : 'Nein') ?></td>
                    <td class="text-end">
                        <a href="edit_beitrag.php?id=<?= $beitrag['beitrag_id'] ?>" class="btn btn-primary btn-sm">bearbeiten</a>
                        <a href="delete_beitrag.php?id=<?= $beitrag['beitrag_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Wollen Sie diesen Beitrag wirklich löschen?');">löschen</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    
    <?php else: ?>
        <p>Keine Beiträge gefunden.</p>
    <?php endif; ?>
    
    <a href="<?= BASE_URL ?>admin/add_beitrag.php" class="btn btn-primary">Beitrag hinzufügen</a>
</div>

<?php include_once BASE_PATH . '/inc/template/foot.php'; ?>
