<?php
class Test extends TBean {
    public function __construct($properties = '') {
        if(is_array($properties)) {
            $this->properties = $properties;
        } else {
            $this->properties = array(
                'userid' => 0,
                'username' => '',
                'password' => ''
            );
        }
    }
}
?>
