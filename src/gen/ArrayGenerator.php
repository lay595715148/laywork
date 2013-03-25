<?php
class ArrayGenerator {
    protected $pointer;
    public    $degerler=array();
   
    function loadString($string){
        $this->pointer = simplexml_load_string($string);
        return $this->pointer;
    }
   
    function loadFile($file){
        $this->pointer = simplexml_load_file($file);
        return $this->pointer;
    }
   
    function nam() {
        return $this->pointer->getName();
    }
    function chd() {
        return $this->pointer->children();
    }
    function att() {
        return $this->pointer->attributes();
    }
    function toArray() {
        foreach ($this->chd() as $sq){
            $this->degerler[$this->getname()][$sq->getname()][] = $sq; // How many key
        }
        return $this->degerler;
    }
   
} 
?>
