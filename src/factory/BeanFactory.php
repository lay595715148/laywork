<?php
class BeanFactory extends Base {
    public static function getInstance($classname, &$property = null) {
        $instance = new $classname();
        if(is_subclass_of($instance, 'Bean')) {
            if($property) $instance->build($property);
            Debug::push($instance);
            return $instance;
        } else {
            unset($instance);
            return null;
        }
    }
}
?>
