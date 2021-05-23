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
            if ( isset($_GET['tag']) ) {
                $day = $_GET['tag'];

                // wenn der Tag Samstag oder (||) Sonntag ist
                if ( $day == 'Samstag' || $day == 'Sonntag' ) {
                    echo "Der Tag ist am Wochende";
                } else if ( $day == 'Montag' || $day == 'Dienstag' || $day == 'Mittwoch' || $day == 'Donnerstag' || $day == 'Freitag' ) {
                    echo "Der Tag ist unter der Woche";
                } else {
                    echo "Ungültiger Tag";
                }
                
                /*switch ($day) {
                    case 'Samstag':
                    case 'Sonntag':
                        echo "Der Tag ist am Wochende";
                        break;
                    
                    case 'Montag':
                    case 'Dienstag':
                    case 'Mittwoch':
                    case 'Donnerstag':
                    case 'Freitag':
                        echo "Der Tag ist unter der Woche";
                        break;
                    
                    default:
                        echo "Ungültiger Tag";
                }*/
            } else {
                echo "Sie haben noch keine Tag eingegeben.";
            }
        ?>
        
        <form action="form_wochentag.php" method="GET">
            <label for="tag">Tag eingeben</label>
            <input type="search" name="tag" id="tag">
            <button type="submit">Suchen</button>
        </form>
    </body>
</html>
