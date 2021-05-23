<?php

/**
 * Description of Konto
 *
 * @author Thomas
 */
class Konto {
    protected $kontonummer;
    protected $eigentuemer;
    protected $guthaben;
    
    function getKontonummer() {
        return $this->kontonummer;
    }

    function getEigentuemer() {
        return $this->eigentuemer;
    }

    function getGuthaben() {
        return number_format($this->guthaben, 2, ',', '.');
    }

    function setKontonummer($kontonummer): void {
        $this->kontonummer = $kontonummer;
    }

    function setEigentuemer($eigentuemer): void {
        $this->eigentuemer = $eigentuemer;
    }

    function setGuthaben($guthaben): void {
        if ($guthaben > 0) {
            $this->guthaben = $guthaben;
        } else {
            $this->guthaben = 0;
        }
    }
        
    protected function einzahlen($betrag) {
        $this->guthaben += $betrag;
    }
    
    protected function auszahlen($betrag) {
        if ($this->guthaben >= $betrag) {
            $this->guthaben -= $betrag;
            return true;
        }
        return false;
    }
    
    function ueberweisen($betrag, Konto $anderesKonto) {
        if ($this->auszahlen($betrag)) {
            $anderesKonto->einzahlen($betrag);
            return true;
        }
        return false;
    }
}
