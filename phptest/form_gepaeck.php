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
            if ( isset($_GET['gewicht']) ) {
                $weight = $_GET['gewicht'];

                switch (true) {
                    case ($weight <= 10):
                        echo "Der Preis für ihr Gepäck beträgt € 10,--";
                        break;
                    
                    case ($weight <= 20):
                        echo "Der Preis für ihr Gepäck beträgt € 20,--";
                        break;
                    
                    case ($weight <= 30):
                        echo "Der Preis für ihr Gepäck beträgt € 30,--";
                        break;
                    
                    default:
                        echo "Ihr Gepäck ist zu schwer";
                }
            } else {
                echo "Sie haben noch kein Gewicht eingegeben.";
            }
        ?>
        
        <form action="form_gepaeck.php" method="GET">
            <label for="gewicht">Gewicht des Gepäcks eingeben</label>
            <input type="text" name="gewicht" id="gewicht">
            <button type="submit">Preis berechnen</button>
        </form>
    </body>
</html>
