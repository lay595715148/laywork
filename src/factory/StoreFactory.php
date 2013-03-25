<?php
class StoreFactory extends Base {
    public static function getInstance($classname,&$config) {
        $instance = new $classname($config);
        if(is_subclass_of($instance, 'Store')) {
            Debug::push($instance);
            return $instance;
        } else {
            unset($instance);
            return null;
        }
    }
}
?>
