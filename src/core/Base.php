<?php
abstract class Base {
    public function __call($method, $arguments) {
        if(!method_exists($this,$method)) {
            echo "<br/>There is no object method:".$method."( ) in class:".get_class($this)."<br/>";
        }
    }
    public static function __callStatic($method, $arguments) {
        if(!method_exists($this,$method)) {
            echo "<br/>There is no static method:".$method."( ) in class:".get_class($this)."<br/>";
        }
    }
    /**
     * magic setter
     * 
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value) {
        if(!property_exists($this,$name)) {
            echo "<br/>There is no property:$name in class:".get_class($this)."<br/>";
        }
    }
    /**
     * magic getter
     * 
     * @param string $name
     * @return void
     */
    public function __get($name) {
        if(!property_exists($this,$name)) {
            echo "<br/>There is no property:$name in class:".get_class($this)."<br/>";
        }
    }
}
?>
