<?php
    $value = 123456789;
    $expires = time() + 6*30*24*60*60; // aktueller Zeitpunkt + ein halbes Jahr
    $path = '/';
    $domain = 'localhost';
    $secure = false; // nur auslesbar (sowohl mittels JavaScript als auch HTTP), wenn die Seite über https aufgerufen wurde
    $httpOnly = true; // nur über HTTP - nicht über JavaScript auslesbar
    
    // Cookie erstellen (muss vor der 1. Ausgabe von einem HTML gemacht)
    //setcookie('my_cart_id', $value, $expires, $path, $domain, $secure, $httpOnly);
    
    // Cookie auslesen
    echo (isset($_COOKIE['my_cart_id']) ? $_COOKIE['my_cart_id'] : '');
    
    // Cookie löschen (Cookie setzen mit Zeit in der Vergangenheit)
    setcookie('my_cart_id', '', time() - 3600, $path, $domain, $secure, $httpOnly);
?>
<h1>Cookies</h1>