<h2>Array mit 10 zufälligen Zahlen</h2>

<?php
    $numbers = getRandomNumbers(10);
    printArray($numbers);
?>

<h3>Summe des Arrays</h3>
<?php
    $sum = 0;
    foreach ($numbers as $number) {
        $sum += $number;
    }
    echo "<p>Die Summe beträgt: $sum</p>";
?>

<h3>Durchschnitt des Arrays</h3>
<?php
    $avg = $sum / count($numbers);
    echo "<p>Der Durchschnitt beträgt: $avg</p>";
?>

<h3>Minimum des Arrays</h3>
<?php
    $min = getMinimum($numbers);
    echo "<p>Das Minimum ist: $min</p>";
?>

<h3>Maximum des Arrays</h3>
<?php
    $max = getMaximum($numbers);
    echo "<p>Das Maximum ist: $max</p>";
?>