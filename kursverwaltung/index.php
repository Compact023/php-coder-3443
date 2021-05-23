<?php
require_once 'functions.php';

// DB-Verbindung
$conn = connectToDB();

// SQL Statement
$query = "SELECT kurs.*, kursort.name AS kursort FROM kurs"
        . " JOIN kursort ON kursort.id = kurs.kursort_id";

// SQL Statement an die Datenbank senden und Ergebnis in $result speichern
$result = mysqli_query($conn, $query);

$kurse = [];

// Überprüfung ob die DB-Anfrage (SQL) erfolgreich war
if ($result) {
    // Durchlaufen aller Zeilen im Ergebnis
    while ($row = mysqli_fetch_assoc($result)) {
        $kurse[] = $row;
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
