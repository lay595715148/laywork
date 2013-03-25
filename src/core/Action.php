<?php
abstract class Action extends Base {
    const DEFAULT_METHOD = 'launch';
    protected $config   = array();
    protected $beans    = array();
    protected $services = array();
    /**
     * @var Result
     */
    protected $result = null;
    public function __construct(&$config = '') {
        $this->config = $config;
    }
    /**
     * 
     * @param string $name
     * @return Bean|boolean
     */
    protected function getBean($name) {
        $beans = &$this->beans;
        if(is_array($beans) && array_key_exists($name,$beans)) {
            return $beans[$name];
        }
        return false;
    }
    /**
     * 
     * @param string $name
     * @return Service|boolean
     */
    protected function getService($name) {
        $services = &$this->services;
        if(is_array($services) && array_key_exists($name,$services)) {
            return $services[$name];
        }
        return false;
    }
    /**
     * initialize beans and/or services
     * @return void
     */
    public function init() {
        $result   = &$this->result;
        $config   = &$this->config;
        $beans    = &$this->beans;
        $services = &$this->services;

        $result = Result::getInstance($config[Config::KEY_ACTION_RESULT]);

        $bs = &$config[Config::KEY_ACTION_BEANS];
        $ss = &$config[Config::KEY_ACTION_SERVICES];
        foreach($bs as $k=>&$b) {
            $classname = $b[Config::KEY_BEAN_CLASSNAME];
            $beanObj   = &BeanFactory::getInstance($classname, $b[Config::KEY_BEAN_PROPERTIES]);
            if($beanObj && $b[Config::KEY_BEAN_AUTO_BUILD]) {
                $beanObj->build($b[Config::KEY_BEAN_SCOPE]);
            }
            if($beanObj) {
                $beans = array_merge($beans,array($k=>$beanObj));
            }
        }
        foreach($ss as $k=>&$s) {
            $classname  = $s[Config::KEY_SERVICE_CLASSNAME];
            $serviceObj = &ServiceFactory::getInstance($classname, $s);
            if($serviceObj && $s[Config::KEY_SERVICE_AUTO_INIT]) {
                 $serviceObj->init();
            }
            if($serviceObj) {
                 $services = array_merge($services,array($k=>$serviceObj));
            }
        }
    }
    /**
     * do dispatch
     * @return void
     */
    public function dispatch() {
        $result   = &$this->result;
        $config   = &$this->config;
        $beans    = &$this->beans;
        $services = &$this->services;

        $key   = $config[Config::KEY_ACTION_DISPATCH_KEY];
        $style = $config[Config::KEY_ACTION_DISPATCH_STYLE];
        $scope = $config[Config::KEY_ACTION_DISPATCH_SCOPE];

        $variable = Scope::parseScope($scope);
        $dispatch = (array_key_exists($key,$variable))?$variable[$key]:false;
        if($dispatch) {
            $method = str_replace('*',$dispatch,$style);
        } else {
            $method = (Config::DEFAULT_METHOD) ? Config::DEFAULT_METHOD : Action::DEFAULT_METHOD;
        }

        $this->$method();
    }
    /**
     * When there is no dispatch or default dispatch to perform
     * @return void
     */
    public function launch() {
        $result = &$this->result;
        $config = &$this->config;
        $result($config);
    }
}
?>
