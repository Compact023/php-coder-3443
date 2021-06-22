<?php
require_once 'config.php';
require_once 'functions.php';

connectToDB($conn);

// Überprüfen ob ein GetRequest gesendet wurde
if (isGetRequest()) {
    // Übergebene Daten aus GET-Request auslesen und in Array speichern
    $formData = [
        'jobid' => formFieldValueGet('id', ''),
    ];
}

// SQL Statement
$query = "SELECT job.*, branche.branche, anstellungsart.anstellungsart, ort.ort, firma.* from job"
        . " JOIN branche ON job.branche_id = branche.branche_id"
        . " JOIN anstellungsart ON job.anstellungsart_id = anstellungsart.anstellungsart_id"
        . " JOIN ort ON job.ort_id = ort.ort_id"
        . " JOIN firma ON job.firma_id = firma.firma_id"
        . " WHERE job.job_id = ?";


// Prepared Statement erstellen
$stmt = $conn->prepare($query);

// Parameter binden
$stmt->bind_param('i', $formData['jobid']);

// Statement ausführen
$stmt->execute();

// Ergebnis des Statements in resultat speichern
$result = $stmt->get_result();

// leeren Array erzeugen
$job = null;

// Anzahl der Reihen im Resultat überprüfen
if ($result && $result->num_rows == 1) {
    // Durchlaufen aller Datensätze und auslesen eines Datensatzes als assoziativen Arrays
    $job = $result->fetch_object();
}

closeDB($conn);
?>

<html>
    <head>
        <title>Job Detailansicht</title>
        <meta charset="UTF-8">
    </head>
    <body>
        <h1>Job Detailansicht</h1>

        <!-- Abfrage ob Job gefunden wurden -> Wenn ja, Zeige die Details -->
        <?php if ($job) : ?>


            <table style="text-align: left">
                <tbody>
                    <tr>
                        <th>Job-Nr.</th>
                        <td><?= $job->job_id ?></td>
                    </tr>
                    <tr>
                        <th>Titel</th>
                        <td><?= $job->titel ?></td>
                    </tr>
                    <tr>
                        <th>Beschreibung</th>
                        <td><?= $job->beschreibung ?></td>
                    </tr>
                    <tr>
                        <th>Branche</th>
                        <td><?= $job->branche ?></td>
                    </tr>
                    <tr>
                        <th>Anstellungsart</th>
                        <td><?= $job->anstellungsart ?> km</td>
                    </tr>
                    <tr>
                        <th>Mindestgehalt</th>
                        <td><?= $job->mindestgehalt ?></td>
                    </tr>
                    <tr>
                        <th>Ort</th>
                        <td><?= $job->ort ?></td>
                    </tr>
                    <tr>
                        <th>Firma<br><br></th>
                        <td><?= $job->firma ?><br><a href="<?= $job->homepage ?>"><?= $job->homepage ?></a><br><a href="mailto:<?= $job->email ?>"><?= $job->email ?></a></td> 
                    </tr>

                </tbody>
            </table>      


            <br>
        <!-- Abfrage ob Job gefunden wurden -> Wenn nein, Zeige Fehlermeldung -->
        <?php else: ?>
            <p>Der Job wurde nicht gefunden!</p>
        <?php endif; ?>
    </body>
    <nav><a href="index.php">Zurück zur Startseite</a></nav>

</html>