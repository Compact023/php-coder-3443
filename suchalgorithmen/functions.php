<?php

    /**
     * Erzeugt ein Array mit einer beliebigen Anzahl von zufälligen Zahlen
     * 
     * @param int $cntElements Anzahl der Elemente
     * @param int $minValue minimaler Wert
     * @param int $maxValue maximalen Wert
     * @return array
     */
    function getRandomElements(int $cntElements = 10, int $minValue = 1, int $maxValue = 100) : array
    {
        $numbers = [];
        
        for ($i = 0; $i < $cntElements; $i++) {
            $numbers[] = random_int($minValue, $maxValue);
        }
        
        return $numbers;
    }
    
    /**
     * Gibt ein Array in Form einer Tabelle aus
     * 
     * @param array $numbers
     */
    function printElements(array $numbers) 
    {
        echo '<table><thead><tr>';
        
        for ($i = 0; $i < count($numbers); $i++) {
            echo "<th>$i</th>";
        }
        
        echo '</tr></thead><tbody><tr>';
        
        foreach ($numbers as $number) {
            echo "<td>$number</td>";
        }
        
        echo '</tr></tbody></table>';
    }
   
    
    /**
     * Gibt zurück, ob ein gesuchter Wert im Array enthalten ist (sequenzielle Suche)
     * 
     * @param array $numbers Array-Elemente
     * @param int $numberToFind Wert der gesucht wird
     * @return bool
     */
    function contains(array $numbers, int $numberToFind) : bool
    {
        // Durchlaufe alle Elemente
        foreach ($numbers as $number) {
            
            // wenn das aktuelle Element gleich ist mit dem gesuchten
            if ($number == $numberToFind) {
                // gib true zurück
                return true;
            }
        }
        
        // Wenn alle Elemente durchlaufen wurden, aber das gesuchte Element nicht enthalten war, wird false zurück gegeben
        return false;
    }
    
    
    function binarySearch(array $numbers, int $numberToFind) : bool
    {
        $left = 0;                      
        $right = count($numbers) - 1;
        
        // Solange die zu durchsuchende Menge nicht leer ist
        while ($left <= $right)
        {
            // Mitte berechnen
            $middle = (int) $left + (($right - $left) / 2);

            // Element gefunden (Element in der mitte ist gesuchtes Element)
            if ($numbers[$middle] == $numberToFind) {
                return true;
            }
            
            if ($numbers[$middle] > $numberToFind)
            {
                // im linken Bereich weitersuchen
                $right = $middle - 1;     
            } else {
                // im rechten Bereich weitersuchen
                $left = $middle + 1;
            }
        }

        return false;
    }