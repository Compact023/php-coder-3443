<?php

/**
 * Description of Autor
 *
 * @author Thomas
 */
class Autor {
    protected $name;
    
    function getName() {
        return $this->name;
    }

    function setName($name): void {
        $this->name = $name;
    }
}
