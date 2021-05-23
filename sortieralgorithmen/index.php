<?php require_once 'functions.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sortieralgorithmen</title>
    </head>
    <body>
        <h1>Sortieralgorithmen</h1>
        <h2>Selection-Sort</h2>
        <?php
            // Array mit Zufallszahlen erstellen
            $numbers = getRandomElements();
            
            // Ausgabe des Arrays
            printElements($numbers);
            
            sortWithSelectionSort($numbers, false);
            
            echo '<p>Fertig Sortiert:</p>';
            printElements($numbers);
        ?>
        
        <h2>Bubble-Sort</h2>
        <?php
            // Array mit Zufallszahlen erstellen
            $numbers = getRandomElements();
            
            // Ausgabe des Arrays
            printElements($numbers);
            
            sortWithBubbleSort($numbers, false);
            
            echo '<p>Fertig Sortiert:</p>';
            printElements($numbers);
        ?>
        
        <h2>Quick-Sort</h2>
        <?php
            // Array mit Zufallszahlen erstellen
            $numbers = getRandomElements();
            
            // Ausgabe des Arrays
            printElements($numbers);
            
            $numbers = sortWithQuickSort($numbers);
            
            echo '<p>Fertig Sortiert:</p>';
            printElements($numbers);
        ?>
    </body>
</html>
