<?php require_once 'functions.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Suchalgorithmen</title>
    </head>
    <body>
        <h1>Suchalgorithmen</h1>
        <h2>Sequenzielle Suche (contains Funktion)</h2>
        <?php
            // Array mit Zufallszahlen generieren
            $numbers = getRandomElements();
            
            // Zahl die wir suchen definieren
            $numberToFind = 51;
            
            // Array ausgeben
            printElements($numbers);
            
            if (contains($numbers, $numberToFind)) {
                echo "<p>Die Zahl $numberToFind ist im Array enthalten.</p>";
            } else {
                echo "<p>Die Zahl $numberToFind ist nicht im Array enthalten.</p>";
            }
        ?>
        
        <h2>Binäre Suche</h2>
        <p>Voraussetzung ist, dass das Array sortiert ist.</p>
        <?php
            // Array sortieren
            sort($numbers);
            
            if (binarySearch($numbers, $numberToFind)) {
                echo "<p>Die Zahl $numberToFind ist im Array enthalten.</p>";
            } else {
                echo "<p>Die Zahl $numberToFind ist nicht im Array enthalten.</p>";
            }
        ?>
    </body>
</html>
