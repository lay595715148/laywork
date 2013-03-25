<?php
abstract class Store extends Base {
    protected $config = array();
    public function __construct(&$config = '') {
        $this->config = $config;
    }
    public abstract function connect();
    public abstract function close();
}
?>
