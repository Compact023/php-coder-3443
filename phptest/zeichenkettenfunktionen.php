<?php

// Kundennummer formatiert ausgeben: Kundennummer: AB000012
$nummer = 12;
printf("<p>Kundennummer: AB%06d</p>", $nummer);

// Kundennummer als Binärwert ausgeben
printf("<p>Kundennummer binär: %b</p>", $nummer);

// Betrag als Fließkommazahl formatiert ausgeben (zwei Nachkommastellen)
$zahl = 12.1;
printf("<p>Ergebnis: %07.2f netto, %07.2f brutto</p>", $zahl, $zahl * 1.2);

// Zeichen mit bestimmten ASCII CODE ausgeben
$asciiWert = 65;
printf("<p>Zeichen mit dem ASCII Code $asciiWert: %c </p>", $asciiWert);


// Formatierte Ausgabe von einer Zeichenfolge
$text1 = 'hallo';
$text2 = 'welt';
printf("<p>Zwei Parameter: <b>%'*8s</b> und <b>%s</b></p>", $text1, $text2);

// Ausgabe einer Zahl mit number_format
$betrag = 12345.6789;
echo "<p>Betrag: " . number_format($betrag, 2, ",", ".") . "</p>";

$email = 'max.mustermann@musterfirma.at';
$name = strstr($email, '@musterfirma', true);
echo "<p>$name</p>";

// Text ab dem ersten Vorkommen des Zeichens a ausgeben
$text = strchr($email, 'a');
echo "<p>$text</p>";

// Text ab dem letzten Vorkommen des Zeichens a ausgeben (r => reverse)
$text = strrchr($email, 'a');
echo "<p>$text</p>";

$dateiName = 'wifi.kurse.csv';
$punktErstePosition = strpos($dateiName, '.');
$punktLetztePosition = strrpos($dateiName, '.');
echo "<p>Der '.' in $dateiName kommt als erstes an der Stelle $punktErstePosition und als letztes an der Stelle $punktLetztePosition vor</p>";

$dateiEndung = substr($dateiName, $punktLetztePosition);
echo "<p>Die Dateiendung von $dateiName ist $dateiEndung</p>";

$dateiNameLaenge = strlen($dateiName);
echo "<p>Die Länge des Dateinamens $dateiName beträgt: $dateiNameLaenge</p>";

echo "<p>Das Wort 'max' kommt in 'max maxxxxximum max' " . substr_count('max maxxxxximum max', 'max') . " mal vor </p>";

