<?php
/**
 * 
 * @author liaiyong
 * @abstract
 *
 */
abstract class Bean extends Base {
    /**
     * class properties.
     * please don't modify them in all methods except for '__construct','__set','__get'
     */
    protected $properties = array();
    /**
     * alias of class properties.
     * please don't modify them in all methods except for '__construct','__set','__get'
     */
    protected $aliases = array();
    public function __construct($properties = '') {
        if(is_array($properties)) {
            $this->properties = $properties;
        }
    }
    /**
     * isset method
     */
    public function __isset($name) {
        return isset($this->properties[$name]);
    }
    /**
     * unset method
     */
    public function __unset($name) {
        unset($this->properties[$name]);
    }
    /**
     * magic setter,set value to class property
     * 
     * @see Base::__set()
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value) {
        if(empty($this->aliases)) {
            foreach(array_keys($this->properties) as $key) {
                $propertyAliasKey = strtolower($key);
                $this->aliases[$propertyAliasKey] = $key;
            }
        }
        
        $propertyAliasName  = strtolower($name);
        if (array_key_exists($propertyAliasName, $this->aliases)) {
            $key = $this->aliases[$propertyAliasName];
            $this->properties[$key] = $value;
        } else {
            echo "<br/>There is no property:$name in class:".get_class($this)."<br/>";
        }
    }
    /**
     * magic setter,get value of class property
     * 
     * @see Base::__get()
     * @param string $name
     * @return mixed|void
     */
    public function __get($name) {
        if(empty($this->aliases)) {
            foreach(array_keys($this->properties) as $key) {
                $propertyAliasKey = strtolower($key);
                $this->aliases[$propertyAliasKey] = $key;
            }
        }
        
        $propertyAliasName  = strtolower($name);
        if (array_key_exists($propertyAliasName, $this->aliases)) {
            $key = $this->aliases[$propertyAliasName];
            return $this->properties[$key];
        } else {
            echo "<br/>There is no property:$name in class:".get_class($this)."<br/>";
        }
    }
    /**
     * magic call method,auto call setter or getter
     * 
     * @see Base::__call()
     * @param string $method
     * @param array $arguments
     * @return mixed|void
     */
    public function __call($method, $arguments) {
        if(method_exists($this,$method)) {
            return (call_user_func_array(array($this, $method), $arguments));
        } else {
            if(strtolower(substr($method, 0, 3)) === 'get') {
                $name = substr($method, 3);
                return $this->$name;
            } else if(strtolower(substr($method, 0, 3)) === 'set'){
                $name = substr($method, 3);
                $this->$name = $arguments[0];
            } else {
                echo "<br/>There is no method:".$method."( ) in class:".get_class($this)."<br/>";
            }
        }
    }
    /**
     * magic invoke method
     * 
     * @return array
     */
    public function __invoke() {
        return $this->properties;
    }

    /**
     * to array value of class properties
     * 
     * @return array
     */
    public function toArray() {
        return $this->properties;
    }

    /**
     * read values from variables(super global varibles or user-defined variables) then auto inject to this.
     * default read from $_REQUEST
     * @param integer|array $scope
     * @return void|Bean
     */
    public function build($scope = 0) {
        if(is_numeric($scope) || !$scope) {
            $scope = &Scope::parseScope($scope);
        } else if(!is_array($scope)){
            echo '<br/>There is a type error in class:'.get_class($this).' method:build( ) param:$scope<br/>';
            return;
        }
        foreach($this->toArray() as $k=>$v) {
            if(array_key_exists($k, $scope)) {
                $this->$k = $scope[$k];
            }
        }
        return $this;
    }
}
?>
