<?php
require_once 'functions.php';
require_once 'config.php';

// DB-Verbindung
connectToDB($conn);
echo var_dump($conn) . '<br><br>';

// SQL Statement
$query = "SELECT vorstellungen.*, raeume.sitzplaetze AS sitzplaetze, filme.titel AS titel FROM vorstellungen"
        . " JOIN raeume ON raeume.raum_id = vorstellungen.raum_id"
        . " JOIN filme ON filme.film_id = vorstellungen.film_id"
        . " ORDER BY vorstellungen.beginnzeit DESC ";

echo var_dump($query) . '<br><br>';

// Prepared Statement erstellen
$stmt = $conn->prepare($query);

// Statement ausführen
$stmt->execute();

// Ergebnis des Statements in resultat speichern
$result = $stmt->get_result();

// leeren Array erzeugen
$vorstellungen = [];

// Anzahl der Reihen im Resultat überprüfen
if ($result && $result->num_rows > 0) {
    // Durchlaufen aller Datensätze und auslesen eines Datensatzes als assoziativen Arrays
    while ($row = $result->fetch_object()) {
        $vorstellungen[] = $row;
    }
}

echo var_dump($vorstellungen) . '<br><br>';

// Version DB Verbindung beenden
closeDB($conn);
?>



<html>
    <head>
        <title>Film Vorstellung</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <h1><strong>Film Vorstellung</strong></h1>
        <table>
            <thead>
                <tr id="ueberschrift">
                    <th>Film</th>
                    <th>Raum</th>
                    <th>Beginn</th>
                    <th>Plätze</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($vorstellungen as $vorstellung): $beginnzeit = strtotime($vorstellung->beginnzeit);?>
                    <?php if ($beginnzeit > time()): ?>
                        <tr>
                            <td><?= $vorstellung->titel ?></td>
                            <td>Raum<?= $vorstellung->raum_id ?></td>  

                            <td><?= date('H:i', $beginnzeit) ?></td>

                            <td><?= $vorstellung->sitzplaetze ?></td>
                            <td>
                                <?php if ($vorstellung->sitzplaetze > 0): ?>
                                    <a href="ticket_buchen.php?id=<?= $vorstellung->vorstellung_id ?>">buchen</a>
                                <?php else : ?>
                                    ausgebucht
                                <?php endif; ?>                         
                            </td>
                        </tr>
                    <?php endif; ?>     
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
</html>




