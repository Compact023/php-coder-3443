<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Lotto-Zahlen-Generator</title>
        <style>
            .numbers .number {
                background: black;
                border-radius: 50%;
                color: white;
                display: inline-block;
                line-height: 3rem;
                width: 3rem;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1>Lotto-Zahlen-Generator</h1>
        <h2>Lotto-Zahlen (6 aus 45)</h2>
        <?php
            function numberExists($numbers, $numberToFind) {
                foreach ($numbers as $number) {
                    if ($number == $numberToFind) {
                        return true;
                    }
                }
                return false;
            }

            // 4 12 22 33 35 42
            $lottoZahlen = [];
            
            do {
                $zufallszahl = rand(1,45);
                
                // wenn die $zufallszahl noch nicht im Array ($lottoZahlen) enthalten ist?
                //if (!in_array($zufallszahl, $lottoZahlen)) {
                if (!numberExists($lottoZahlen, $zufallszahl)) {
                    // hinzufügen der Zahl zu den Lottozahlen
                    $lottoZahlen[] = $zufallszahl;
                }
            } while (count($lottoZahlen) < 6);
            
            // array sortieren
            sort($lottoZahlen);
        ?>
        <div class="numbers">
            <?php foreach ($lottoZahlen as $lottoZahl): ?>
                <div class="number"><?= $lottoZahl ?></div>
            <?php endforeach; ?>
        </div>
        
        <h2>Joker-Zahlen</h2>
        <?php
            // 5 3 5 9 8 2
            $jokerZahlen = [];
            
            for ($i = 0; $i < 6; $i++) {
                $jokerZahlen[] = rand(0,9);
            }
        ?>
        <div class="numbers">
            <?php foreach ($jokerZahlen as $jokerZahl): ?>
                <div class="number"><?= $jokerZahl ?></div>
            <?php endforeach; ?>
        </div>
    </body>
</html>

