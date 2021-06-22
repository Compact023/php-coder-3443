<?php
require_once 'config.php';
require_once 'functions.php';

connectToDB($conn);

/*
 * 
 * ------Orte holen---------
 * 
 */

// SQL Statement
$query = "SELECT * from ort"
        . " ORDER BY ort.ort ASC";

$stmt = $conn->prepare($query);

// Statement ausführen
$stmt->execute();

// Ergebnis des Statements in resultat speichern
$result = $stmt->get_result();


// Resultate in Orte Array speichern
$orte = [];
if ($result && $result->num_rows > 0) {
    // Durchlaufen aller Datensätze und auslesen eines Datensatzes als assoziativen Arrays
    while ($row = $result->fetch_object()) {
        $orte[] = $row;
    }
}

// Abholen des Ergebnis des Get-Parameters wenn dieser gesetzt ist 
if(isGetRequest()){
    $formData = [
        'ortid' => formFieldValueGet('ort', 'all')
    ];
}

/*
 * 
 * ------Jobs holen holen---------
 * 
 */

// SQL Statement
$query = "SELECT job.*, anstellungsart.anstellungsart, ort.ort from job"
        . " JOIN anstellungsart ON job.anstellungsart_id = anstellungsart.anstellungsart_id"
        . " JOIN ort ON job.ort_id = ort.ort_id";

// Überprüfen ob gefiltert wurde und entsprechendes anpassen des SQL Statements
if($formData['ortid'] != 'all'){
    $query = $query . " WHERE job.ort_id = ?"
            . " ORDER BY ort ASC";
    
    // Prepared Statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $formData['ortid']);
} else {
    $query = $query . " ORDER BY ort ASC";
    $stmt = $conn->prepare($query);
}


// Statement ausführen
$stmt->execute();


// Ergebnis des Statements in resultat speichern
$result = $stmt->get_result();


// Resultate in Orte Array speichern
$jobs = [];
if ($result && $result->num_rows > 0) {
    // Durchlaufen aller Datensätze und auslesen eines Datensatzes als assoziativen Arrays
    while ($row = $result->fetch_object()) {
        $jobs[] = $row;
    }
}


// DB-Verbindung schließen;
closeDB($conn);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Jobs</title>
    </head>
    <body>
        <h1>Jobs</h1>
        <div>
            <form action="index.php" method="GET">
                <label for="ort">Ort</label>
                <select name="ort" id="ort">
                    <option value="all" >Alle</option>
                    <!-- Durchlaufen alle Objekte im Array orte -->
                    <?php foreach ($orte as $ort): ?>
                        <option value="<?= $ort->ort_id ?>" <?= ($formData['ortid'] == $ort->ort_id ? 'selected' : '') ?>><?= $ort->ort ?></option>
                    <?php endforeach; ?> 
                </select>
                <button type="submit">filtern</button> 
            </form>
        </div>
        <!-- Abfrage ob Jobs gefunden wurden -> Wenn ja, Zeige sie an -->
        <?php if ($jobs) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Job-Nr.</th>
                        <th>Titel</th>
                        <th>Anstellungsart</th>
                        <th>Mindestgehalt</th>
                        <th>Ort</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?= $job->job_id ?></td>
                            <td><?= $job->titel ?></td>
                            <td><?= $job->anstellungsart ?></td>
                            <td><?= number_format($job->mindestgehalt, 2, ',', '.') ?></td>
                            <td><?= $job->ort ?></td>
                            <td><a href="job_details.php?id=<?= $job->job_id ?>">Details</a></td>                      
                        </tr>
                    <?php endforeach; ?>
                </tbody> 
            </table>
        <!-- Abfrage ob Job gefunden wurden -> Wenn nein, Zeige Fehlermeldung -->
        <?php else: ?>
            <p>Keine Jobs vorhanden!</p>
        <?php endif; ?>
    </body>
</html>
