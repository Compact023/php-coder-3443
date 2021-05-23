<?php
session_start();

$sessionId = session_id();
//echo $sessionId;

// Setzen eines Wertes in der Session
//$_SESSION['vorname'] = 'Max';
//$_SESSION['nachname'] = 'Mustermann';

//$_SESSION['user'] = [
//    'vorname' => 'Susi',
//    'nachname' => 'Musterfrau'
//];

// Löschen eines Attributs aus der Session
//unset($_SESSION['user']);

// Löschen aller Elemente/Attribute aus der Session
//$_SESSION = [];

// Auslesen von Werten aus der Session
$vorname = (isset($_SESSION['vorname']) ? $_SESSION['vorname'] : '');
$nachname = (isset($_SESSION['nachname']) ? $_SESSION['nachname'] : '');

echo "<p>Hallo $vorname $nachname!</p>";

// Session beenden
session_destroy();