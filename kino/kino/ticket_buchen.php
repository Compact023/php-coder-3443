<?php
require_once 'functions.php';
require_once 'config.php';

connectToDB($conn);

// Überprüfen ob ein GetRequest gesendet wurde
if (isPostRequest()) {
    // Übergebene Daten aus POST-Request auslesen und in Array speichern
    $formData = [
        'vorname' => formFieldValue('vorname', ''),
        'nachname' => formFieldValue('nachname', ''),
        'email' => formFieldValue('email', ''),
        'telefon' => formFieldValue('telefon', '')
    ];
}

// SQL Statement
$query = "INSERT INTO kunden (vorname, nachname, email, telefon)"
        . " VALUES (?,?,?,?)";

echo var_dump($query) . '<br><br>';

// Prepared Statement erstellen
$stmt = $conn->prepare($query);

$stmt->bind_param('sssi', $formData['vorname'], $formData['nachname'], $formData['email'], $formData['telefon']);
        
// Statement ausführen
$stmt->execute();





// Überprüfen ob ein GetRequest gesendet wurde
if (isGetRequest()) {
    // Übergebene Daten aus GET-Request auslesen und in Array speichern
    $formData = [
        'vorstellung_id' => formFieldValueGet('id', ''),
    ];
}

// SQL Statement
$query = "SELECT vorstellungen.beginnzeit AS beginnzeit, filme.titel AS titel FROM vorstellungen"
        . " JOIN filme ON filme.film_id = vorstellungen.film_id"
        . " WHERE vorstellung_id LIKE ? ";

echo var_dump($query) . '<br><br>';

// Prepared Statement erstellen
$stmt = $conn->prepare($query);

$stmt->bind_param('i', $formData['vorstellung_id']);

echo $formData['vorstellung_id'] . '<br><br>';
        
// Statement ausführen
$stmt->execute();

// Ergebnis des Statements in resultat speichern
$result = $stmt->get_result();

echo var_dump($result) . '<br><br>';

// leeren Array erzeugen
$vorstellungen = null;

    // Überprüfen vom Resultat (ob es erfolgreich war und ob die Anzahl der zurückgelieferten Zeilen == 1 ist)
    if ($result && $result->num_rows == 1) {

        // Datensatz aus Ergebnis auslesen
        $vorstellungen = $result->fetch_object(); 
    }

echo var_dump($vorstellungen) . '<br><br>';

closeDB($conn);
?>


<html>
    <head>
        <title>Ticket kaufen</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>

        <h1>Ticket kaufen</h1>

        <h2>Film: <?php echo $vorstellungen->titel ?></h2>
        <?php $beginnzeit = strtotime($vorstellungen->beginnzeit) ?>
        <p>Beginn: <?php echo date('H:i', $beginnzeit)?></p>

        <form action="ticket_buchen.php" method="POST">
            <div class="form-field">
                <label for="vorname">Vorname*</label>
                <input type="text" name="vorname" required>
            </div>

            <div class="form-field">
                <label for="nachname">Nachname*</label>
                <input type="text" name="nachname" required>
            </div>

            <div class="form-field">
                <label for="email">Email*</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-field">
                <label for="telefon">Telefonnummer</label>
                <input type="tel" name="telefon">
            </div>

            <button type="button" onclick="location.href = 'index.php';">Zurück</button>
            
            <button type="submit">Kaufen</button>

        </form>

    </body>
</html>

