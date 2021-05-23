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
            if ( isset($_GET['suchbegriff']) ) {
                $searchTerm = $_GET['suchbegriff'];

                echo "Sie haben nach \"$searchTerm\" gesucht.";
            } else {
                echo "Sie haben noch keine Suchbegriff eingegeben.";
            }
        ?>
        
        <form action="form.php" method="GET">
            <label for="suchbegriff">Suchbegriff</label>
            <input type="search" name="suchbegriff" id="suchbegriff">
            <button type="submit">Suchen</button>
        </form>
    </body>
</html>
