<?php
// einzeiliger Kommentar

/*
 * mehrzeiligen Kommentar
 */
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>PHP Test</title>
    </head>
    <body>
        <h1>PHP Test</h1>
        <h2><?php echo '"Hallo Thomas"'; ?></h2>
        
        <?php
            echo "Wie geht's?";
            echo "Draußen ist es dunkel.";
            
            $first_name = 'Thomas';
            $last_name = 'Mustermann';
            $full_name = $first_name . ' ' . $last_name;
            
            echo $full_name;
            
            // Definition einer Konstante 
            define('MWST', 0.20); // Alternativ: const MWST = 0.20;
            $price = 20;
            
            $grossPrice = $price + $price * MWST;
            
            echo "Bruttopreis: € $grossPrice";
            
            $zahl = 4;
            $zahl++;
            
            // Schleife
            $count = 1;
            while ($count <= 10) {
                echo "<div>$count</div>";
                $count++;
            }
            
            $count = 1;
            do {
                echo "<div>$count</div>";
                $count++;
            } while ($count <= 10);
            
            for ($count = 1; $count <= 10; $count++) {
                echo "<div>$count</div>";
            }
        ?>
        <h2>Beispiel: alle Zahlen zwischen 0 und 100 die durch 3 teilbar sind</h2>
        <?php
            for ($count = 1; $count <= 100; $count++){
                if ($count % 3 == 0) {
                    echo "<div>$count</div>";
                }
            }
        ?>
        
        <h2>Besonderheiten zu Datentypen</h2>
        <?php
            echo gettype("test");
            echo is_bool($count);
            
            $isPrime = true;
            if ($isPrime) {
                echo 'das ist eine Testanweisung';
            }
        ?>
        
        <h2>Funktionsaufruf</h2>
        <?php
            include_once 'functions.php';
            
            $a = 5.2;
            $b = 7.3;
            
            $sum = calculateSum($a, $b);
            echo '<p>Die Summe von ' . number_format($a, 2, ',', '.') . ' + ' . number_format($b, 2, ',', '.') . ' = ' . number_format($sum, 2, ',', '.') . '</p>';
        ?>
        
        <h3>Funktionen mit dynamischer Anzahl von Parametern</h3>
        <?php
            $sum = calculateSumDynamic(5,12,11,17);
            echo '<p>Die Summe der übergebenen Parameter ist: ' . number_format($sum, 2, ',', '.') . '</p>';
        ?>
    </body>
</html>
