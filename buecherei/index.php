<?php
    require_once 'functions.php';

    // Verbindung zur Datenbank aufbauen
    $conn = connectToDB();
    
    $autoren = fetchAutorenFromDB($conn);
    
    $kategorien = fetchKategorienFromDB($conn);
    
    // SQL-Statement
    $query = "SELECT buch.*, autor.name AS autor, kategorie.name AS kategorie, verlag.name AS verlag FROM buch"
            ." JOIN autor ON autor.autor_id = buch.autor_id"
            ." JOIN kategorie ON kategorie.kategorie_id = buch.kategorie_id"
            ." JOIN verlag ON verlag.verlag_id = buch.verlag_id";

    // SQL Statement an die Datenbank senden und Ergebnis in $result speichern
    $result = mysqli_query($conn, $query);

    $buecher = [];
    
    // Überprüfung ob die DB-Anfrage (SQL) erfolgreich war
    if ($result) {
        // Durchlaufen aller Zeilen im Ergebnis
        while ($row = mysqli_fetch_assoc($result)) {
            $buecher[] = $row;
        }
    }

    // Verbindung zur Datenbank schließen
    closeDB($conn);
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
        <title>Bücherei</title>
    </head>
    <body>
        <h1>Bücherei</h1>
        <h2>Bücher</h2>
        <?php if (count($buecher) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titel</th>
                        <th>Kategorie</th>
                        <th>Autor</th>
                        <th>Verlag</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($buecher as $buch): ?>
                        <tr>
                            <td><?= $buch['buch_id'] ?></td>
                            <td><?= $buch['buch_titel'] ?></td>
                            <td><?= $buch['kategorie'] ?></td>
                            <td><?= $buch['autor'] ?></td>
                            <td><?= $buch['verlag'] ?></td>
                            <td>
                                <a href="buch_edit.php?id=<?= $buch['buch_id'] ?>">bearbeiten</a>
                            </td>
                            <td>
                                <a href="buch_delete.php?id=<?= $buch['buch_id'] ?>" onclick="return confirm('Wollen Sie das Buch wirklich löschen?');">löschen</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Keine Bücher gefunden.</p>
        <?php endif; ?>
            
        <a href="buch_add.php">Buch hinzufügen</a>
        
        
        
        <h2>Autoren</h2>
        <?php if (count($autoren) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($autoren as $autor): ?>
                        <tr>
                            <td><?= $autor['autor_id'] ?></td>
                            <td><?= $autor['name'] ?></td>
                            <td>
                                <a href="autor_edit.php?id=<?= $autor['autor_id'] ?>">bearbeiten</a>
                            </td>
                            <td>
                                <a href="autor_delete.php?id=<?= $autor['autor_id'] ?>" onclick="return confirm('Wollen Sie den Autor wirklich löschen?');">löschen</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Keine Autoren gefunden.</p>
        <?php endif; ?>
            
        <a href="autor_add.php">Autor hinzufügen</a>
        
        
        <h2>Kategorien</h2>
        <?php if (count($kategorien) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kategorien as $kategorie): ?>
                        <tr>
                            <td><?= $kategorie['kategorie_id'] ?></td>
                            <td><?= $kategorie['name'] ?></td>
                            <td>
                                <a href="kategorie_edit.php?id=<?= $kategorie['kategorie_id'] ?>">bearbeiten</a>
                            </td>
                            <td>
                                <a href="kategorie_delete.php?id=<?= $kategorie['kategorie_id'] ?>" onclick="return confirm('Wollen Sie die Kategorie wirklich löschen?');">löschen</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Keine Kategorien gefunden.</p>
        <?php endif; ?>
            
        <a href="kategorie_add.php">Kategorie hinzufügen</a>
    </body>
</html>
