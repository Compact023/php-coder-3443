<?php
require_once 'functions.php';

if (isGetRequest()) {
    // Übergebene Daten aus POST-Request auslesen und in Array speichern
    $formData = [
        'fachbereich' => formFieldValueGET('fachbereich', '')
    ];
}

// DB-Verbindung
$conn = connectToDB();

$whereCondition = '';
if($formData['fachbereich']){
   $whereCondition =  " WHERE fachbereich.name like ?";
}

$query = "SELECT kurs.*, fachbereich.name AS fachbereich, kursort.name AS kursort FROM kurs"
        . " JOIN fachbereich ON fachbereich.id = kurs.fachbereich_id"
        . " JOIN kursort ON kursort.id = kurs.kursort_id"
        . "$whereCondition";
$stmt = mysqli_prepare($conn, $query);



@mysqli_stmt_bind_param($stmt, "s", $formData['fachbereich']);
mysqli_stmt_execute($stmt);

// SQL Statement an die Datenbank senden und Ergebnis in $result speichern
$result = mysqli_stmt_get_result($stmt);
$kurse = [];
if ($result) {
    // Durchlaufen aller Zeilen im Ergebnis
    while ($row = mysqli_fetch_assoc($result)) {
        $kurse[] = $row;
    }
}



// Kursorte aus der DB holen
$query = "SELECT * from fachbereich";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);

// SQL Statement an die Datenbank senden und Ergebnis in $result speichern
$result = mysqli_stmt_get_result($stmt);
$fachbereiche = [];

// Überprüfung ob die DB-Anfrage (SQL) erfolgreich war
if ($result) {
    // Durchlaufen aller Zeilen im Ergebnis
    while ($row = mysqli_fetch_assoc($result)) {
        $fachbereiche[] = $row;
    }
}

// Verbindung zur Datenbank schließen
closeDB($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <nav>
            <h2>Navigation</h2>
            <ul id="navigation" name="navigation">
                <li><a href="index.php">Startseite</a></li>
                <li><a href="kurse_nach_fachbereich.php">Kurse nach Fachbereich</a></li>
                <li><a href="kurse_nach_kursort.php">Kurse nach Kursort</a></li>
            </ul>
        </nav>
        <main>
            <h1>Kursübersicht</h1>
            <form action="kurse_nach_fachbereich.php" method="GET">
                <select name="fachbereich" id="fachbereich" required>
                    <option value="alle" disabled selected>Alle</option>
                    <?php foreach ($fachbereiche as $fachbereich): ?>
                        <option value="<?= $fachbereich['name'] ?>" <?= ($fachbereich['name'] == @$formData['fachbereich'] ? 'selected' : '') ?>>
                            <?= $fachbereich['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Suchen</button>
                <button type="button" onclick="location.href = 'kurse_nach_fachbereich.php';">Zurücksetzen</button>
            </form>
            <table>
                <tr>
                    <th>Kursnummer</th>
                    <th>Name</th>
                    <th>Beginndatum</th>
                    <th>Kursort</th>
                </tr>
                <?php foreach ($kurse as $kurs): ?>
                    <tr>
                        <td><?= $kurs['kursnummer'] ?></td>
                        <td>
                            <a href="kurs_detail.php?kursnummer=<?= $kurs['kursnummer'] ?>"><?= $kurs['name'] ?></a>
                        </td>
                        <td><?= $kurs['beginndatum'] ?></td>
                        <td><?= $kurs['kursort'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </main>
    </body>
</html>
