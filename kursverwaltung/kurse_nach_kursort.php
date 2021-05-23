<?php
require_once 'functions.php';

if (isGetRequest()) {
    // Übergebene Daten aus POST-Request auslesen und in Array speichern
    $formData = [
        'kursort' => formFieldValueGET('kursort', '')
    ];
}

// DB-Verbindung
$conn = connectToDB();

$whereCondition = '';
if($formData['kursort']){
   $whereCondition =  " WHERE kursort.name like ?";
}

$query = "SELECT kurs.*, kursort.name AS kursort FROM kurs"
        . " JOIN kursort ON kursort.id = kurs.kursort_id"
        . "$whereCondition";
$stmt = mysqli_prepare($conn, $query);



@mysqli_stmt_bind_param($stmt, "s", $formData['kursort']);
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
$query = "SELECT * from kursort";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);

// SQL Statement an die Datenbank senden und Ergebnis in $result speichern
$result = mysqli_stmt_get_result($stmt);
$kursorte = [];

// Überprüfung ob die DB-Anfrage (SQL) erfolgreich war
if ($result) {
    // Durchlaufen aller Zeilen im Ergebnis
    while ($row = mysqli_fetch_assoc($result)) {
        $kursorte[] = $row;
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
            <form action="kurse_nach_kursort.php" method="GET">
                <select name="kursort" id="kursort" required>
                    <option value="alle" disabled selected>Alle</option>
                    <?php foreach ($kursorte as $kursort): ?>
                        <option value="<?= $kursort['name'] ?>" <?= ($kursort['name'] == @$formData['kursort'] ? 'selected' : '') ?>>
                            <?= $kursort['name'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Suchen</button>
                <button type="button" onclick="location.href = 'kurse_nach_kursort.php';">Zurücksetzen</button>
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
