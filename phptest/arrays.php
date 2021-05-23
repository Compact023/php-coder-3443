<?php
require_once 'functions.php';

$numbers = array(10, 5, 2, 7, 1, 3, 4, 8, 9, 6);
$cities = ['Linz', 'Wien', 'Berlin', 'Zürich', 'London'];

$cities[2] = 'München';
unset($cities[3]); // Element am Index 3 löschen
$cities[] = 'Hawaii'; // Neues Element am Ende des Arrays hinzufügen
$cities[13] = 'Paris'; // Neues Element mit Index 13 hinzufügen
$cities[] = 'Barcelona';

foreach ($cities as $city) {
    echo "<div>$city</div>";
}

$capitalCities = [
    'Austria' => 'Vienna',
    'Germany' => 'Berlin',
    'GB' => 'London'
];

$capitalCities['Austria'] = 'Linz';
unset($capitalCities['Germany']); // Element mit Schlüssel "Germany" löschen
$capitalCities['Italy'] = 'Rome'; // neues Element mit Schlüssel "Italy" hinzufügen

printArray($capitalCities, "Land", "Hauptstadt");

$capitalCities = array_flip($capitalCities);

printArray($capitalCities, 'Key');

$progLang = [
    ['PHP', 'Version 5.6', 'plattformunabhängig'],
    ['JAVA', 'Version 8', 'plattformunabhängig'],
    ['C#', 'Version 6', 'Windows']
];

echo "<div>" . $progLang[2][1] . "</div>";

$progLangAssoc = [
    'PHP' => [
        'version' => 'Version 5.6',
        'plattform' => 'plattformunabhängig'
    ],
    'JAVA' => [
        'version' => 'Version 8',
        'plattform' => 'plattformunabhängig'
    ],
    'C#' => [
        'version' => 'Version 6',
        'plattform' => 'Windows'
    ],
];
echo "<div>" . $progLangAssoc['JAVA']['version'] . "</div>";

echo json_encode($progLangAssoc);