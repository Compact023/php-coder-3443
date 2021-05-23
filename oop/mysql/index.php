<?php

require_once 'config.php';

// "Alte" prozedurale Version zum Aufbauen einer DB-Verbindung
// $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// "neue" objektorientierte Variante
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


// Überprüfen, ob die Verbindung zur Datenbank nicht aufgebaut werden konnte
if (!$conn) {
    // "alte" Version: Ausführung beenden und Fehlermeldung ausgeben
    //die('Es konnte keine DB-Verbindung hergestellt werden ' . mysqli_connect_error());
    
    // "neue" Version:
    die('Es konnte keine DB-Verbindung hergestellt werden ' . $conn->connect_error);
}


// "alte" Version: Zeichensatz hinterlegen
//mysqli_set_charset($conn, DB_CHARSET);

// "neue" Version
$conn->set_charset(DB_CHARSET);


$query = "SELECT * FROM buch WHERE autor_id = ?";


// "alte" Version: prepared Statement erstellen
//$stmt = mysqli_prepare($conn, $query);

// "neue" Version
$stmt = $conn->prepare($query);

$autorId = 1;


// "alte" Version: ? durch Werte in prepared Statement ersetzen
// mysqli_stmt_bind_param($stmt, "i", $autorId);

// "neue" Version
$stmt->bind_param("i", $autorId);


// "alte" Version: prepared statement ausführen
// mysqli_stmt_execute($stmt);

// "neue" Version
$stmt->execute();


// "alte" Version: Ergebnisse von der Datenbank holen
//$result = mysqli_stmt_get_result($stmt);

// "neue" Version
$result = $stmt->get_result();

$buecher = [];


// "alte" Version: Anzahl der Reihen im Resultat abfragen
// if ($result && mysqli_num_rows($result)) {...}

// "neue" Version
if ($result && $result->num_rows > 0) {
    
    // "alte" Version: Durchlaufen aller Datensätze und auslesen eines Datensatzes als assoziativen Arrays
    //while ($row = mysqli_fetch_assoc($result)) {...}
    
    // "neue" Version
    while ($row = $result->fetch_object()) {
        $buecher[] = $row;
    }
}

// "alte" Version DB Verbindung beenden
// mysqli_close($conn);

// "neue" Version
$conn->close();

?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Bücher</title>
    </head>
    <body>
        <h1>Bücher</h1>
        <?php if (count($buecher) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titel</th>
                    <th>ISBN</th>
                    <th>Erscheinungsdatum</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($buecher as $buch): ?>
                    <tr>
                        <td><?= $buch->buch_id ?></td>
                        <td><?= $buch->buch_titel ?></td>
                        <td><?= $buch->isbn ?></td>
                        <td>
                            <?php 
                                $datum = new DateTime($buch->erscheinungsdatum);
                                echo $datum->format('d.m.Y');
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>Keine Einträge gefunden.</p>
        <?php endif; ?>
    </body>
</html>