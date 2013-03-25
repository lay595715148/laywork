<?php
abstract class Service extends Base {
    /**
     * service config array
     */
    protected $config = array();
    /**
     * object of subclass Store  
     * @var <Store>
     */
    protected $store = null;
    public function __construct(&$config = '') {
        $this->config = $config;
    }
    /**
     * initilize store
     */
    public function init() {
        $config = &$this->config;
        $store  = &$this->store;

        $storeConfig = &$config[Config::KEY_SERVICE_STORE];
        $classname   = &$storeConfig[Config::KEY_STORE_CLASSNAME];
        $store       = StoreFactory::getInstance($classname, $storeConfig);
        if($storeConfig[Config::KEY_STORE_AUTO_CONNECT]) {
            $store->connect();
        }
    }
    /**
     * base of reading a record
     * @param array|<Bean>|Condition $args
     */
    public abstract function read($args = '');
    /**
     * base of reading multi records
     * @param array|<Bean>|Condition $args
     * @param string|array $order
     * @param string|Paging $paging
     */
    public abstract function reads($args = '', $order = '', $paging = '');
    /**
     * base of writing into a record
     * @param array|<Bean> $args
     */
    public abstract function write($args);
    /**
     * base of modifing record
     * @param array|<Bean>|Condition $args
     * @param array $values
     */
    public abstract function modify($args, $values = '');
    /**
     * base of deleting record
     * @param array|<Bean>|Condition $args
     */
    public abstract function remove($args);
}
?>
