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
        
        <form action="form_anrede_switch.php" method="GET">
            <label for="anrede">Anrede</label>
            <select name="anrede" id="anrede">
                <option>Herr</option>
                <option>Frau</option>
                <option>Divers</option>
            </select>
            <br>
            <label for="name">Name</label>
            <input type="text" name="name" id="name">
            
            <button type="submit">Absenden</button>
        </form>
        
        <div style="margin-top: 100px">
            <?php
                if ( isset($_GET['anrede']) ) {
                    $salutation = $_GET['anrede'];
                    $name = $_GET['name'];
                    
                    switch ($salutation) {
                        case 'Herr':
                            echo "Sehr geehrter Herr $name, es freut uns, dass Sie hier sind.";
                            break;
                        
                        case 'Frau':
                            echo "Sehr geehrte Frau $name, es freut uns, dass Sie hier sind.";
                            break;

                        default:
                            echo "Guten Tag $name, es freut uns, dass Sie hier sind.";
                    }
                }
            ?>
        </div>
    </body>
</html>
