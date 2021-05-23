<div class="card mb-3 kommentar-element">
    <div class="card-header">
        <div class="user-name">
            <?= $kommentar['vorname'] . ' ' . $kommentar['nachname'] ?>
        </div>
        <div class="created">
            <?php
                $erstelltAm = strtotime($kommentar['erstellt_am']);
            ?>
            <?= date('d.m.Y H:i', $erstelltAm) ?>
            
            <?php if ($kommentar['user_id'] == $userId): ?>
                <a href="delete_kommentar.php?id=<?= $kommentar['kommentar_id'] ?>" class="btn btn-sm btn-danger delete-kommentar">löschen</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <p class="card-text"><?= _e($kommentar['text']) ?></p>
    </div>
</div>
