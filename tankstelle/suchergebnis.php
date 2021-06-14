<?php
require_once 'config.php';
require_once 'functions.php';

// DB Connection aufbauen
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Überprüfen, ob die Verbindung zur Datenbank nicht aufgebaut werden konnte
if (!$conn) {
    die('Es konnte keine DB-Verbindung hergestellt werden ' . $conn->connect_error);
}

// Zeichensatz für Datenbank angeben
$conn->set_charset(DB_CHARSET);

// Überprüfen ob ein GetRequest gesendet wurde
if (isGetRequest()) {
    // Übergebene Daten aus GET-Request auslesen und in Array speichern
    $formData = [
        'kunde' => formFieldValueGET('suche', '')
    ];
} else {
    echo '<p>Bitte verwenden Sie die Steuerung der Website!</p>';
}

/**
 * Kundendaten
 */
// SQL Statement
$query = "SELECT * FROM kunde"
        . " WHERE kunde_id like ?";

// Prepared Statement erstellen
$stmt = $conn->prepare($query);

// ? des prepared Statement binden
$stmt->bind_param("i", $formData['kunde']);

// Statement ausführen
$stmt->execute();

// Ergebnis des Statement in resultat speichern
$result = $stmt->get_result();

// leeres Array erzeugen
$kunde = [];

// Resultat auf gültigkeit prüfen und in Objekt spreichern
if ($result && $result->num_rows == 1) {
    $kunde = $result->fetch_object();
} else {
    // Wenn kein Ergebnis gefunden wurde Fehlermeldung ausgeben und Programm beenden
    die('Der Kunde wurde nicht gefunden oder ungültige Eingabe!');
}

/**
 * Verbrauchsdaten
 */
// SQL Statement
$query = "SELECT * FROM verbrauch"
        . " WHERE kunde_id like ?";

// Prepared Statement erstellen
$stmt = $conn->prepare($query);

// ? des prepared Statement binden
$stmt->bind_param("i", $formData['kunde']);

// Statement ausführen
$stmt->execute();

// Ergebnis des Statement in resultat speichern
$result = $stmt->get_result();

// Leere Arrays vorbereiten
$verbraeuche = [];
$preis = [];

// Anzahl der Reihen im Resultat überprüfen
if ($result && $result->num_rows > 0) {

    // Durchlaufen aller Datensätze und auslesen eines Datensatzes als assoziativen Arrays
    while ($row = $result->fetch_object()) {
        $verbraeuche[] = $row->menge;
        $preis[] = $row->preis;
    }
    
    //Zusammenzählen der einzelnen Verbräuche und Preise
    $treibstoffverbrauch = null;
    foreach ($verbraeuche as $verbrauch) {
        $treibstoffverbrauch += $verbrauch;
    }
    
    $gesamtpreis = null;
    foreach ($preis as $preis) {
        $gesamtpreis += $preis;
    }
}

// Version DB Verbindung beenden
$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <main>
            <h1>Suchergebnis</h1>
            <table>
                <tr>
                    <td><strong>Kundennummer:</strong></td>
                    <td><?= $kunde->kunde_id ?></td>
                </tr>
                <tr>
                    <td><strong>Vorname:</strong></td>
                    <td><?= $kunde->vorname ?></td>
                </tr>
                <tr>
                    <td><strong>Nachname:</strong></td>
                    <td><?= $kunde->nachname ?></td>
                </tr>
                <tr>
                    <td><strong>Strasse:</strong></td>
                    <td><?= $kunde->strasse ?></td>
                </tr>
                <tr>
                    <td><strong>PLZ:</strong></td>
                    <td><?= $kunde->plz ?></td>
                </tr>
                <tr>
                    <td><strong>Ort:</strong></td>
                    <td><?= $kunde->ort ?></td>
                </tr>
                <tr>
                    <td><strong>Geburtsdatum:</strong></td>
                    <td><?= $kunde->geburtsdatum ?></td>
                </tr>
                <tr>
                    <td><br></td>
                    <td><br></td>
                </tr>
                <tr>
                    <td><strong>Treibstoffverbrauch:</strong></td>
                    <td><?= number_format($treibstoffverbrauch, 15, '.', ',') ?></td>
                </tr>
                <tr>
                    <td><strong>Gesamtpreis:</strong></td>
                    <td><?= number_format($gesamtpreis, 15, '.', ',') ?></td>
                </tr>
            </table>
        </main>
    </body>
</html>
