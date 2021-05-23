
<?php
require_once 'functions.php';

if (isGetRequest()) {
    // Übergebene Daten aus POST-Request auslesen und in Array speichern
    $formData = [
        'kursnummer' => formFieldValueGET('kursnummer', '')
    ];
}

// DB-Verbindung
$conn = connectToDB();

// SQL Statement
$query = "SELECT kurs.*, fachbereich.name AS fachbereich FROM kurs"
        . " JOIN fachbereich ON fachbereich.id = kurs.fachbereich_id"
        . " WHERE kursnummer like ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $formData['kursnummer']);
mysqli_stmt_execute($stmt);

// SQL Statement an die Datenbank senden und Ergebnis in $result speichern
$result = mysqli_stmt_get_result($stmt);

$kurs = [];

// Überprüfung ob die DB-Anfrage (SQL) erfolgreich war
if ($result &&  mysqli_num_rows($result) == 1) {
        $kurs =  mysqli_fetch_assoc($result);
    
}





// Kurstermine holen
// SQL Statement
$query = "SELECT kurstermin.*, trainer.nachname AS trainer FROM kurstermin"
        . " JOIN trainer ON trainer.id = kurstermin.trainer_id"
        . " WHERE kursnummer like ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $formData['kursnummer']);
mysqli_stmt_execute($stmt);

// SQL Statement an die Datenbank senden und Ergebnis in $result speichern
$result = mysqli_stmt_get_result($stmt);

$kurstermine = [];

// Überprüfung ob die DB-Anfrage (SQL) erfolgreich war
if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
        $kurstermine[] = $row;
    }
    
}




// Teilnehmer holen
// SQL Statement
$query = "SELECT kurs_teilnehmer.*, teilnehmer.vorname AS vorname, teilnehmer.nachname AS nachname, teilnehmer.email AS email, teilnehmer.geburtsdatum AS geburtsdatum FROM kurs_teilnehmer"
        . " JOIN teilnehmer ON kurs_teilnehmer.teilnehmer_id = teilnehmer.id"
        . " WHERE kurs_teilnehmer.kursnummer like ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $formData['kursnummer']);
mysqli_stmt_execute($stmt);

// SQL Statement an die Datenbank senden und Ergebnis in $result speichern
$result = mysqli_stmt_get_result($stmt);

$kursteilnehmer = [];

// Überprüfung ob die DB-Anfrage (SQL) erfolgreich war
if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
        $kursteilnehmer[] = $row;
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
            <h1>Kurs: <?= $kurs['kursnummer'];?> <?= $kurs['name'];?></h1>
            <table>
                <tr>
                    <td><bold>Beginndatum:</bold></td>
                    <td><?= $kurs['beginndatum'] ?></td>
                </tr>
                <tr>
                    <td><bold>Dauer:</bold></td>
                    <td><?= $kurs['dauer'] ?></td>
                </tr>
                <tr>
                    <td><bold>Fachbereich:</bold></td>
                    <td><?= $kurs['fachbereich'] ?></td>
                </tr>
                <tr>
                    <td><bold>Beschreibung:</bold></td>
                    <td><?= $kurs['beschreibung'] ?></td>
                </tr>
            </table>
            
            
            <h2>Termine</h2>
            <table>
                <tr>
                    <th>Beginn</th>
                    <th>Einheiten</th>
                    <th>Trainer</th>
                </tr>
                <?php foreach ($kurstermine as $kurstermin): ?>
                    <tr>
                        <td><?= $kurstermin['beginn'] ?></td>
                        <td><?= $kurstermin['dauer'] ?></td>
                        <td><?= $kurstermin['trainer'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            
             <h2>Teilnehmer</h2>
            <table>
                <tr>
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th>E-Mail</th>
                    <th>Geburtsdatum</th>
                </tr>
                <?php foreach ($kursteilnehmer as $teilnehmer): ?>
                    <tr>
                        <td><?= $teilnehmer['vorname'] ?></td>
                        <td><?= $teilnehmer['nachname'] ?></td>
                        <td><?= $teilnehmer['email'] ?></td>
                        <td><?= $teilnehmer['geburtsdatum'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </main>
    </body>
</html>
