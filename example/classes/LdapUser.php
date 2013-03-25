<?php
class LdapUser extends TBean {
    public function __construct() {
        $this->properties = array(
            'uid' => 0,
            'userPassword' => '',
            'username' => '',
            'role' => ''
        );
    }
}
?>
