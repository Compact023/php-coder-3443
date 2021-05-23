<?php
    require_once 'config.php';
    
    $beitraege = [];
    
    $conn = connectToDB();
    
    $query = "SELECT * FROM beitrag WHERE aktiv = 1 ORDER BY erstellt_am DESC";
    
    $result = mysqli_query($conn, $query);
    
    // Überprüfung ob SQL Statement erfolgreich war und ob Zeilen zurückgekommen sind
    if ($result && mysqli_num_rows($result) > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) {
            $beitraege[] = $row;
        }
    }
    
    closeDB($conn);
    
    $pageTitle = 'Meine aktuellen Beiträge';
?>

<?php include_once BASE_PATH . '/inc/template/head.php'; ?>

<div class="container">
    <h1>Aktuelle Beiträge</h1>
    
    <div class="beitraege">
        <?php foreach ($beitraege as $beitrag): ?>
            <div class="card beitrag">
                <?php if (empty($beitrag['bild'])): ?>
                    <img src="//via.placeholder.com/350x150" class="card-img-top" alt="<?= $beitrag['titel'] ?>">
                <?php else: ?>
                    <div style="background: url('<?= BASE_URL . $beitrag['bild'] ?>') no-repeat center;" class="card-img-top" alt="<?= $beitrag['titel'] ?>"></div>
                <?php endif; ?>
                
                <div class="card-body">
                    <h5 class="card-title"><?= $beitrag['titel'] ?></h5>
                    <div class="card-text">
                        <?= $beitrag['text'] ?>
                    </div>
                    <a href="<?= BASE_URL ?>beitrag.php?id=<?= $beitrag['beitrag_id'] ?>" class="btn btn-primary">mehr...</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
        
    </div>
</div>

<?php include_once BASE_PATH . '/inc/template/foot.php'; ?>