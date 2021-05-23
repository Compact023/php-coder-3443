<?php

/**
 * This function prints a array in a html-table
 * 
 * @param array $array array containing the elements
 * @param string $headingColumn1 heading of column 1
 * @param string $headingColumn2 heading of column 2
 */
function printArray(array $array, string $headingColumn1 = 'Schlüssel', string $headingColumn2 = 'Wert') {
    echo "<table><thead><tr><th>$headingColumn1</th><th>$headingColumn2</th></tr></thead><tbody>";
    foreach ($array as $key => $value) {
        echo "<tr><td>$key</td><td>$value</td></tr>";
    }
    echo '</tbody></table>';
}

/**
 * This function returns the sum of the parameters
 * 
 * @param float $number1
 * @param float $number2
 * @return float
 */
function calculateSum(float $number1, float $number2) :float {
    $sum1 = $number1 + $number2;
    return $sum1;
}

function calculateSumDynamic(...$numbers) {    
    $sum = 0;
    
    if (func_num_args() > 0) {
        foreach ($numbers as $number) {
            $sum += $number;
        }
    }
    
    return $sum;
}

function getRandomNumbers(int $cntElement, int $minValue = 0, int $maxValue = 100) {
    $numbers = [];
    
    for ($i = 0; $i < $cntElement; $i++) {
        $numbers[] = rand($minValue, $maxValue);
    }
    
    return $numbers;
}

function getMinimum(array $numbers) {
    $min = $numbers[0]; // Minimum ist das erste Elemente

    // Beginnend vom 2. Element durchlaufen wir alle Elemente bis zum Ende
    for ($i = 1; $i < count($numbers); $i++) {
        
        // wenn das Minimum größer ist als das aktuelle Element
        if ($min > $numbers[$i]) {
            // Minimum auf das aktuelle Element setzen
            $min = $numbers[$i];
        }
    }
    
    return $min;
}

function getMaximum(array $numbers) {
    $max = $numbers[0]; // Maximum ist das erste Elemente

    // Beginnend vom 2. Element durchlaufen wir alle Elemente bis zum Ende
    for ($i = 1; $i < count($numbers); $i++) {
        
        // wenn das Maximum größer ist als das aktuelle Element
        if ($max < $numbers[$i]) {
            // Maximum auf das aktuelle Element setzen
            $max = $numbers[$i];
        }
    }
    
    return $max;
}