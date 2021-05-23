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
     * Funktion zum Tauschen zweier Werte
     * 
     * @param int $element1
     * @param int $element2
     */
    function swapElements(int &$element1, int &$element2)
    {
        $tmp = $element1;
        $element1 = $element2;
        $element2 = $tmp;
    }
    
    /**
     * Sortiert die Array-Elemente mit dem Selection-Sort Algorithmus
     * 
     * @param array $numbers Array mit den zu sortierenden Elementen
     * @param bool $asc Array Elemente aufsteigend sortieren
     */
    function sortWithSelectionSort(array &$numbers, bool $asc = true)
    {        
        // Durchlaufen aller Elemente von 0 bis zum vorletzten Element
        for ($i = 0; $i < count($numbers) - 1; $i++) {
            $minIndex = $i;
            
            // die innere Schleife wird benötigt um das kleiste Element der restlichen Elemente ausfindig zu machen
            // Durchlaufen aller Elemente ab $i + 1 bis zum letzten Element
            for ($j = $i + 1; $j < count($numbers); $j++) {
                // Soll das Array aufsteigend sortiert werden?
                if ($asc) {
                    if ($numbers[$minIndex] > $numbers[$j]) {
                        $minIndex = $j;
                    }
                } else {
                    if ($numbers[$minIndex] < $numbers[$j]) {
                        $minIndex = $j;
                    }
                }
            }
            
            // an dieser Stelle kennen wir das Element mit dem kleinsten Wert (an der Position $minIndex)
            // Element an der Stelle $i mit dem Element an der Stelle $minIndex tauschen
            swapElements($numbers[$i], $numbers[$minIndex]);
            
            // Zwischenschritte ausgeben
            //echo '<p>Durchlauf ' . ($i + 1) . ':</p>';
            //printElements($numbers);
        }
    }
    
    /**
     * Sortiert die Array-Elemente mit dem Bubble-Sort Algorithmus
     * 
     * @param array $numbers Array mit den zu sortierenden Elementen
     * @param bool $asc Array Elemente aufsteigend sortieren
     */
    function sortWithBubbleSort(array &$numbers, bool $asc = true)
    {
        for ($i = count($numbers) - 1; $i > 0; $i--) {
            
            // Innere Schleife vergleicht die Nachbar miteinander
            // Resultat eines Durchlaufs ist, dass das größte Element ganz hinten zu finden ist
            for ($j = 0; $j < $i; $j++) {
                // Soll das Array aufsteigend sortiert werden?
                if ($asc) {
                    if ($numbers[$j] > $numbers[$j + 1]) {
                        swapElements($numbers[$j], $numbers[$j+1]);
                    }
                } else {
                    if ($numbers[$j] < $numbers[$j + 1]) {
                        swapElements($numbers[$j], $numbers[$j+1]);
                    }
                }
            }
            
            // Zwischenschritte ausgeben
            //echo '<p>Durchlauf ' . (count($numbers) - $i) . ':</p>';
            //printElements($numbers);
        }
    }
    
    /**
     * Sortiert die Array-Elemente mit dem QuickSort Algorithmus
     * 
     * @param array $numbers Array mit den zu sortierenden Elementen
     * @return array
     */
    function sortWithQuickSort(array $numbers) : array
    {
        $lowerNumbers = [];
        $biggerNumbers = [];
        
        // Wenn weniger als zwei Element im Array enthalten sind
        if (count($numbers) < 2) {
            return $numbers;
        }
        
        // Speichern des aktuellen Index vom numbers Array -> im Normalfall ist das, das der Index des ersten Elements
        $pivotIndex = key($numbers);
        
        // Entfernt das erste Array-Element und gibt den Wert zurück
        $pivotValue = array_shift($numbers);
        
        // Teile die Numbers anhand des Pivot-Elements in zwei Gruppen auf (kleinere Elemente und größere Elemente)
        foreach ($numbers as $number) {
            if ($number <= $pivotValue) {
                $lowerNumbers[] = $number;
            } else {
                $biggerNumbers[] = $number;
            }
        }
        
        // Verknüpfe die sortierten kleineren Nummern mit dem Pivot-Element und den sortierten größeren Nummern, wobei vor dem Verknüpfen zuerst der selbe Vorgang auf die kleieren und größeren Elemente anzuwenden ist
        return array_merge(sortWithQuickSort($lowerNumbers), [$pivotIndex => $pivotValue], sortWithQuickSort($biggerNumbers));
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
        
        return false;
    }