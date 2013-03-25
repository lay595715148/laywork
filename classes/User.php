<?php
class User extends Bean {
    public function __construct() {
        $this->properties = array(
            'userid' => 0,
            'username' => '',
            'password' => ''
        );
    }
}
?>
