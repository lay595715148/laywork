<?php
class ActionFactory extends Base {
    public static function getInstance($classname,&$config) {
        $instance = new $classname($config);
        if(is_subclass_of($instance, 'Action')) {
            Debug::push($instance);
            return $instance;
        } else {
            unset($instance);
            return null;
        }
    }
}
?>
