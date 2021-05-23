<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Objektorientierte Programmierung</title>
    </head>
    <body>
        <h1>Objektorientierte Programmierung</h1>
        <?php
            $sparbuch1 = new stdClass();
            $sparbuch2 = new stdClass();
            $sparbuch3 = $sparbuch2;
            
            if ($sparbuch1 == $sparbuch2) {
                echo '<p>die beiden Objekte sind gleich</p>';
            }
            
            // sparbuch1 und sparbuch2 sind nicht ident
            if ($sparbuch1 === $sparbuch2) {
                echo '<p>die beiden Objekte (sparbuch1 & sparbuch2) sind ident</p>';
            } else {
                echo '<p>die beiden Objekte (sparbuch1 & sparbuch2) sind nicht ident</p>';
            }
            
            if ($sparbuch3 === $sparbuch2) {
                echo '<p>die beiden Objekte (sparbuch2 & sparbuch3) sind ident</p>';
            }
            
            require_once 'Konto.php';
            $konto1 = new Konto();
            $konto1->setKontonummer(12345);
            $konto1->setEigentuemer('Max Mustermann');
            $konto1->setGuthaben(2412.50);
            
            $konto2 = new Konto();
            $konto2->setKontonummer(22222);
            $konto2->setEigentuemer('Susi Musterfrau');
            $konto2->setGuthaben(4323.20);
            
            echo '<p>' . $konto1->getKontonummer() . ' ' . $konto1->getEigentuemer() . ' ' . $konto1->getGuthaben() . '</p>';
            
            echo '<p>Überweisung von 4000 auf Konto2</p>';
            
            if ($konto1->ueberweisen(4000, $konto2)) {
                echo '<p>Die Überweisung wurde durchgeführt</p>';
            } else {
                echo '<p>Zu wenig Guthaben um die Überweisung durchzuführen</p>';
            }
                    
            echo '<p>' . $konto1->getKontonummer() . ' ' . $konto1->getEigentuemer() . ' ' . $konto1->getGuthaben() . '</p>';
            
            echo '<p>Überweisung von 200 auf Konto1</p>';
            
            if ($konto2->ueberweisen(200, $konto1)) {
                echo '<p>Die Überweisung wurde durchgeführt</p>';
            } else {
                echo '<p>Zu wenig Guthaben um die Überweisung durchzuführen</p>';
            }
            
            echo '<p>' . $konto1->getKontonummer() . ' ' . $konto1->getEigentuemer() . ' ' . $konto1->getGuthaben() . '</p>';
            
            
            
            require_once 'Autor.php';
            require_once 'Buch.php';
            
            $autor = new Autor();
            $autor->setName('J.R.R. Tolkien');
            
            $buch = new Buch();
            $buch->setTitel('Der Herr der Ringe - Die Gefährten');
            $buch->setAutor($autor);
            $buch->setPreis(9.99);
            
            echo '<p>Variante 1: Buchautor: ' . $buch->getAutor()->getName() . '</p>';
            echo '<p>Variante 2: Buchautor: ' . $buch->getAutorName() . '</p>';
            
            $buch2 = new Buch();
            $buch2->setTitel('Der Hobbit');
            $buch2->setAutorName('J.R.R. Tolkien');
            $buch2->setPreis(10.99);
            
            echo '<p>' . $buch2->getBruttoPreis(). '</p>';
            
            
            
        ?>
    </body>
</html>
