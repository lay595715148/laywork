<?php
class ConfiguratorDefault implements Configurator {
    /** XML configuration file format. */
    const FORMAT_XML = 'xml';
    
    /** PHP configuration file format. */
    const FORMAT_PHP = 'php';
    
    /** INI (properties) configuration file format. */
    const FORMAT_INI = 'ini';

    /** JSON configuration file format. */
    const FORMAT_JSON = 'json';
    /** Defines which adapter should be used for parsing which format. */
    protected $config = array();
    private $adapters = array(
            self::FORMAT_XML => 'ConfigurationAdapterXML',
            self::FORMAT_INI => 'ConfigurationAdapterINI',
            self::FORMAT_PHP => 'ConfigurationAdapterPHP',
            self::FORMAT_JSON => 'ConfigurationAdapterJSON',
        );
    private static $defaultConfiguration = array(
        );

    public function configure($input = null) {
        $this->config = $this->parse($input);
        return $this->doConfigure();
    }
    private function doConfigure() {
        $this->configureBean();
        $this->configureStrore();
        $this->configureService();
        $this->configureAction();
        return $this->config;
    }
    public function parse($input) {
        if (!isset($input)) {// No input - use default configuration
            $config = self::$defaultConfiguration;
        } else if (is_array($input)) {// Array input - contains configuration within the array
            $config = $input;
        } else if (is_string($input)) {// String input - contains path to configuration file
            $config = $this->parseFile($input);
            if($config == false) {
                $config = self::$defaultConfiguration;
            }
        } else {// Anything else is an error
            $config = self::$defaultConfiguration;
        }
        
        return $config;
    }
    private function parseFile($url) {
        if (!file_exists($url)) {
            return false;
        }
        
        $type  = $this->getConfigType($url);
        $class = $this->adapters[$type];

        $adapter = new $class();
        return $adapter->convert($url);
    }

    /** Determines configuration file type based on the file extension. */
    private function getConfigType($url) {
        $info = pathinfo($url);
        $ext  = strtolower($info['extension']);
        
        switch($ext) {
            case 'xml':
                return self::FORMAT_XML;
            
            case 'ini':
            case 'properties':
                return self::FORMAT_INI;
            
            case 'php':
                return self::FORMAT_PHP;

            case 'json':
                return self::FORMAT_PHP;
                
            default:
                return false;
        }
    }
    protected function configureBean() {
        //set variables
        $bean     = &$this->config[Config::KEY_BEAN];
        $beans    = &$this->config[Config::KEY_BEANS];

        //beans
        foreach($beans as $k=>$v) {
            $beans[$k] = array_merge($bean, $beans[$k]);
        }
    }
    protected function configureStrore() {
        //set variables
        $store    = &$this->config[Config::KEY_STORE];
        $stores   = &$this->config[Config::KEY_STORES];
        //stores
        foreach($stores as $k=>$v) {
            $stores[$k] = array_merge($store, $stores[$k]);
        }
    }
    protected function configureService() {
        //set variables
        $service  = &$this->config[Config::KEY_SERVICE];
        $services = &$this->config[Config::KEY_SERVICES];
        $store    = &$this->config[Config::KEY_STORE];
        $stores   = &$this->config[Config::KEY_STORES];
        //services
        foreach($services as $k=>$v) {
            $services[$k] = array_merge($service, $services[$k]);

            //service store
            if(array_key_exists(Config::KEY_SERVICE_STORE, $services[$k])) {
                $serviceStore = &$services[$k][Config::KEY_SERVICE_STORE];
            } else {
                $services[$k][Config::KEY_SERVICE_STORE] = '';
            }
            if($serviceStore && is_string($serviceStore) && array_key_exists($serviceStore, $stores)) {
                $serviceStore = $stores[$serviceStore];
            } else {
                $serviceStore = '';
            }
        }
    }
    protected function configureAction() {
        //set variables
        $bean     = &$this->config[Config::KEY_BEAN];
        $beans    = &$this->config[Config::KEY_BEANS];
        $action   = &$this->config[Config::KEY_ACTION];
        $actions  = &$this->config[Config::KEY_ACTIONS];
        $service  = &$this->config[Config::KEY_SERVICE];
        $services = &$this->config[Config::KEY_SERVICES];
        //actions
        foreach($actions as $k=>&$v) {
            $actions[$k] = array_merge($action, $actions[$k]);

            //action beans
            if(array_key_exists(Config::KEY_ACTION_BEANS, $actions[$k])) {
                $actionBeans = &$actions[$k][Config::KEY_ACTION_BEANS];
            } else {
                $actions[$k][Config::KEY_ACTION_BEANS] = array();
            }
            if(is_string($actionBeans)) {
                $actionBeans = explode(',',$actionBeans);
                //$actionBeans = (array_key_exists($actionBeans, $beans))?array($actionBeans => $beans[$actionBeans]):array();
            }
            if(is_array($actionBeans)) {
                foreach($actionBeans as $_k=>&$_v) {
                    if(is_numeric($_k) && array_key_exists($_v, $beans)) {
                        $actionBeans[$_v] = $beans[$_v];
                        $actionBeans = array_diff_key($actionBeans,array($_k=>$_v));
                    } else if(is_string($_k) && array_key_exists($_k, $beans)){
                        $actionBeans[$_k] = (is_array($actionBeans[$_k]))?array_merge($actionBeans[$_k], $beans[$_k]):$beans[$_k];
                    }
                }
            } else {
                $actionBeans = '';
            }

            //action services
            if(array_key_exists(Config::KEY_ACTION_SERVICES, $actions[$k])) {
                $actionServices = &$actions[$k][Config::KEY_ACTION_SERVICES];
            } else {
                $actions[$k][Config::KEY_ACTION_SERVICES] = array();
            }
            if(is_string($actionServices)) {
                $actionServices = explode(',',$actionServices);
                //$actionServices = (array_key_exists($actionServices, $services))?array($actionServices => $services[$actionServices]):array();
            }
            if(is_array($actionServices)) {
                foreach($actionServices as $_k=>&$_v) {
                    if(is_numeric($_k) && array_key_exists($_v, $services)) {
                        $actionServices[$_v] = $services[$_v];
                        $actionServices = array_diff_key($actionServices,array($_k=>$_v));
                    } else if(is_string($_k) && array_key_exists($_k, $services)){
                        $actionServices[$_k] = (is_array($actionServices[$_k]))?array_merge($actionServices[$_k], $services[$_k]):$services[$_k];
                    }
                }
            } else {
                $actionServices = '';
            }
        }
    }
}
?>
