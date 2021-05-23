<?php

/**
 * Description of Buch
 *
 * @author Thomas
 */
class Buch {
    protected $autor;
    protected $titel;
    protected $preis;
            
    function getAutor() {
        return $this->autor;
    }

    function getTitel() {
        return $this->titel;
    }

    function setAutor(Autor $autor): void {
        $this->autor = $autor;
    }

    function setTitel($titel): void {
        $this->titel = $titel;
    }
    
    function getAutorName() {
        return $this->getAutor()->getName();
    }
    
    function setAutorName($name) {
        if (!$this->getAutor() instanceof Autor) {
            $this->setAutor(new Autor());
        }
        $this->getAutor()->setName($name);
    }
    
    function getPreis() {
        return $this->preis;
    }

    function setPreis($preis): void {
        $this->preis = $preis;
    }

    function getBruttoPreis() {
        return number_format(($this->preis * 1.2), 2, ',', '.');
    }
}
