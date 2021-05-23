<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Formular</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Formular</h1>
        
        <?php
            if ( isset($_GET['zahl']) ) {
                $number = intval($_GET['zahl']);
                
                if ($number < 2) {
                    $isPrime = false;
                } else {
                    $isPrime = true;
                }

                for ($count = 2; $count <= ($number / 2); $count++) {
                    if ($number % $count == 0) {
                        $isPrime = false;
                        break;
                    }
                }

                if ($isPrime) {
                    echo "Die Zahl $number ist eine Primzahl";
                } else {
                    echo "Die Zahl $number ist keine Primzahl";
                }

            }
        ?>
        
        <form action="form_primzahl.php" method="GET">
            <label for="zahl">Zahl</label>
            <input type="text" name="zahl" id="zahl">
            <button type="submit">Prüfen</button>
        </form>
        
        <h2>Alle Zahlen zwischen 2 und 100 die eine Primzahl sind</h2>
        <?php
            for ($number = 2; $number <= 100; $number++) {
                $isPrime = true;

                for ($count = 2; $count <= ($number / 2); $count++) {
                    if ($number % $count == 0) {
                        $isPrime = false;
                        break;
                    }
                }

                if ($isPrime) {
                    echo "$number ";
                }
            }
        ?>
    </body>
</html>
