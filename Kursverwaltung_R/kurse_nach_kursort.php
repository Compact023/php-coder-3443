<?php
require_once 'config.php';
require_once 'functions.php';

connectToDB($conn);

// SQL Statement
$query = "SELECT kurs.*, kursort.name as kursort FROM kurs"
        . " JOIN kursort ON kursort.id = kurs.kursort_id";
        

// Überprüfen ob ein GetRequest gesendet wurde
if (isGetRequest()) {
    // Übergebene Daten aus GET-Request auslesen und in Array speichern
    $formData = [
        'kursortid' => formFieldValueGet('kursortid', ''),
    ];
    if ($formData['kursortid'] == '') {
        $query = $query . " ORDER BY kurs.beginndatum ASC";
        $stmt = $conn->prepare($query);
    } else {
        $query = $query . " WHERE kurs.kursort_id = ?" . " ORDER BY kurs.beginndatum ASC";
        echo var_dump($query) . '<br><br>';
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $formData['kursortid']);
    }
}



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
        <title>Kurse nach Kursort</title>
        <meta charset="UTF-8">
    </head>

    <body>
        <h1>Kurse nach Kursort</h1>

        <div>
            <form action="kurse_nach_kursort.php" method="GET">
                <label>Kursort</label>
                <select name="kursortid" id="kursortid">
                    <option value="" selected>Alle</option>
                    <?php foreach ($kurse as $kurs): ?>
                        <option value="<?php echo $kurs->kursort_id ?>"><?php echo $kurs->kursort ?></option>
                    <?php endforeach; ?> 
                </select>
                <button type="submit">Suchen</button> 
            </form>
        </div>
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
    </body>
</html>
