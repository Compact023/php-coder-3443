<?php
require_once 'config.php';
require_once 'functions.php';

connectToDB($conn);

$formData = [];

// Überprüfen ob ein GetRequest gesendet wurde
if (isGetRequest()) {
    // Übergebene Daten aus GET-Request auslesen und in Array speichern
    $formData = array_merge($formData, [
        'kursnummer' => formFieldValueGet('id', ''),
    ]);
}

// SQL Statement
$query = "SELECT kurs.*, fachbereich.name as fachbereich FROM kurs"
        . " JOIN fachbereich ON fachbereich.id = kurs.fachbereich_id"
        . " WHERE kurs.kursnummer = ?";

echo var_dump($query) . '<br><br>';

// Prepared Statement erstellen
$stmt = $conn->prepare($query);

// Parameter binden
$stmt->bind_param('i', $formData['kursnummer']);

// Statement ausführen
$stmt->execute();

// Ergebnis des Statements in resultat speichern
$result = $stmt->get_result();

// leeren Array erzeugen
$kursdetails = null;

// Anzahl der Reihen im Resultat überprüfen
if ($result && $result->num_rows == 1) {
    // Durchlaufen aller Datensätze und auslesen eines Datensatzes als assoziativen Arrays
    $kursdetails = $result->fetch_object();
} else {
    die('Es ist ein Problem aufgetreten! Bitte wenden Sie sich an den Administrator.');
}


// SQL Statement
$query = "SELECT kurstermin.*, trainer.nachname as trainer FROM kurstermin"
        . " JOIN trainer ON trainer.id = kurstermin.trainer_id"
        . " WHERE kurstermin.kursnummer = ?";

echo var_dump($query) . '<br><br>';

// Prepared Statement erstellen
$stmt = $conn->prepare($query);

// Parameter binden
$stmt->bind_param('i', $formData['kursnummer']);

// Statement ausführen
$stmt->execute();

// Ergebnis des Statements in resultat speichern
$result = $stmt->get_result();

// leeren Array erzeugen
$termine = [];

// Überprüfen vom Resultat (ob es erfolgreich war und ob die Anzahl der zurückgelieferten Zeilen == 1 ist)
if ($result && $result->num_rows >= 1) {
    while ($row = $result->fetch_object()) {
        $termine[] = $row;
    }
} else {
    die('Es ist ein Problem aufgetreten! Bitte wenden Sie sich an den Administrator.');
}



// SQL Statement
$query = "SELECT kurs_teilnehmer.*, teilnehmer.vorname as vorname, teilnehmer.nachname as nachname, teilnehmer.email as email, teilnehmer.geburtsdatum as geburtsdatum FROM kurs_teilnehmer"
        . " JOIN teilnehmer ON teilnehmer.id = kurs_teilnehmer.teilnehmer_id"
        . " WHERE kurs_teilnehmer.kursnummer = ?";

echo var_dump($query) . '<br><br>';

// Prepared Statement erstellen
$stmt = $conn->prepare($query);

// Parameter binden
$stmt->bind_param('i', $formData['kursnummer']);

// Statement ausführen
$stmt->execute();

// Ergebnis des Statements in resultat speichern
$result = $stmt->get_result();

// leeren Array erzeugen
$teilnehmer = [];

// Überprüfen vom Resultat (ob es erfolgreich war und ob die Anzahl der zurückgelieferten Zeilen == 1 ist)
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_object()) {
        $teilnehmer[] = $row;
    }
} else {
    die('Es ist ein Problem aufgetreten! Bitte wenden Sie sich an den Administrator.');
}

closeDB($conn);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Kursdetails</title>
    </head>

    <body>
        <h1>Kurs: <?php echo $kursdetails->kursnummer ?> <?php echo $kursdetails->name?></h1>
        <table>
            <tbody>
                <tr>
                    <td><strong>Beginndatum:</strong></td>
                    <td><?php echo $kursdetails->beginndatum ?></td>
                </tr>
                <tr>
                    <td><strong>Dauer:</strong></td>
                    <td><?php echo $kursdetails->dauer ?> Einheiten</td>
                </tr>
                <tr>
                    <td><strong>Fachbereich:</strong></td>
                    <td><?php echo $kursdetails->fachbereich ?></td>
                </tr>
                <tr>
                    <td><strong>Beschreibung:</strong></td>
                    <td><?php echo $kursdetails->beschreibung ?></td>
                </tr>
            </tbody>
        </table>

        <br>

        <h2>Termine</h2>
        <table>
            <thead>
                <tr>
                    <th>Beginn</th>
                    <th>Einheiten</th>
                    <th>Trainer</th>
                </tr>
            </thead>
            <?php foreach ($termine as $termin): ?>
                <tbody>
                    <tr>
                        <td><?php echo $termin->beginn ?></td>
                        <td><?php echo $termin->dauer ?></td>
                        <td><?php echo $termin->trainer ?></td>
                    </tr>
                </tbody>
            <?php endforeach; ?>            
        </table>

        <br>

        <h2>Teilnehmer</h2>
        <table>
            <thead>
                <tr>
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th>E-Mail</th>
                    <th>Geburtsdatum</th>
                </tr>
            </thead>
            <?php foreach ($teilnehmer as $teilnehmer): ?>
                <tbody>
                    <tr>
                        <td><?php echo $teilnehmer->vorname ?></td>
                        <td><?php echo $teilnehmer->nachname ?></td>
                        <td><?php echo $teilnehmer->email ?></td>
                        <td><?php echo $teilnehmer->geburtsdatum ?></td>
                    </tr>
                <?php endforeach; ?> 
            </tbody>
        </table>
        
        <br>
        
        <nav><a href="index.php">Zurück zur Startseite</a></nav>

    </body>
</html>