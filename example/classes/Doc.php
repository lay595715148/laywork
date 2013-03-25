<?php
class Doc extends Bean {
    public function __construct() {
        $this->properties = array(
            'docid' => 0,
            'docname' => '',
            'describe' => ''
        );
    }
}
?>
