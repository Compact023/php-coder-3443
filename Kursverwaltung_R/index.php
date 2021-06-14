<?php
require_once 'config.php';
require_once 'functions.php';

connectToDB($conn);

// SQL Statement
$query = "SELECT kurs.*, kursort.name as kursort FROM kurs"
        . " JOIN kursort ON kursort.id = kurs.kursort_id"
        . " ORDER BY kurs.beginndatum ASC";

echo var_dump($query) . '<br><br>';

// Prepared Statement erstellen
$stmt = $conn->prepare($query);

// Statement ausführen
$stmt->execute();

// Ergebnis des Statements in resultat speichern
$result = $stmt->get_result();

// leeren Array erzeugen
$kurse = [];

// Anzahl der Reihen im Resultat überprüfen
if ($result && $result->num_rows > 0) {
    // Durchlaufen aller Datensätze und auslesen eines Datensatzes als assoziativen Arrays
    while ($row = $result->fetch_object()) {
        $kurse[] = $row;
    }
}


closeDB($conn);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Kursbübersicht</title>
    </head>

    <body>
        <nav>
            <h2>Navigation</h2>
            <?php //unordered list = punkte - ordered list = 1. 2. usw.  ?> 
            <ul> 
                <li><a href="index.php">Startseite</a></li>
                <li><a href="kurse_nach_fachbereich.php">Kurse nach Fachbereich</a></li>
                <li><a href="kurse_nach_kursort.php">Kurse nach Kursort</a></li>
            </ul>
        </nav>

        <main>
            <h1>Kursübersicht</h1>
            <table>
                <thead>
                    <tr>
                        <th>Kursnummer</th>
                        <th>Name</th>
                        <th>Beginndatum</th>
                        <th>Kursort</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($kurse as $kurs): ?>
                        <tr>
                            <td><?php echo $kurs->kursnummer ?></td>
                            <td><a href="kurs_detail.php?id=<?php echo $kurs->kursnummer ?>"><?= $kurs->name ?></a></td>
                            <td><?php echo $kurs->beginndatum ?></td>
                            <td><?php echo $kurs->kursort ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </main>

    </body>
</html>
