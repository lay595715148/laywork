<?php
class ConfigurationAdapterXML extends Base implements ConfigurationAdapter {
	/** Path to the XML schema used for validation. */
	const SCHEMA_PATH = 'configuration.xsd';
	public function convert($url) {
        if(is_string($url) && file_exists($url)) {
            $config = &Transfer::xml2PHPArray($url);
            $this->parseBean($config);
            $this->parseStore($config);
            $this->parseService($config);
            $this->parseAction($config);
            //echo "<pre>";print_r($config);echo "</pre>";
            unset($config['comment']);
        } else {
            return false;
        }
        return $config;
    }
    private function parseBean(&$config) {
        $beans     = &$config[Config::KEY_BEANS];
        $autoBuild = &$beans['@attributes'][Config::KEY_BEAN_AUTO_BUILD];
        $autoBuild = ($autoBuild == 'true')?true:false;
        $config[Config::KEY_BEAN] = $beans['@attributes'];
        unset($beans['@attributes']);
        unset($beans['comment']);

        $beann = array();
        foreach($beans[Config::KEY_BEAN] as $k=>$bean) {
            $name       = $bean['@attributes'][Config::KEY_NAME];
            $properties = $bean[Config::KEY_BEAN_PROPERY];

            $beann[$name][Config::KEY_BEAN_CLASSNAME] = $bean['@attributes'][Config::KEY_BEAN_CLASSNAME];
            $beann[$name][Config::KEY_BEAN_SCOPE] = (array_key_exists(Config::KEY_BEAN_SCOPE,$bean))?$bean['@attributes'][Config::KEY_BEAN_SCOPE]:false;
            $beann[$name][Config::KEY_BEAN_PROPERTIES] = array();
            foreach($properties as $property) {
                $pname = $property['@attributes'][Config::KEY_NAME];
                $default = $property['@attributes'][Config::KEY_PROPERTY_DEFAULT];
                $beann[$name][Config::KEY_BEAN_PROPERTIES][$pname] = $default;
            }
        }
        $beans = $beann;
    }
    private function parseStore(&$config) {
        $stores      = &$config[Config::KEY_STORES];
        $autoConnect = &$stores['@attributes'][Config::KEY_STORE_AUTO_CONNECT];
        $autoConnect = ($autoConnect === 'true')?true:false;
        $showSql     = &$stores['@attributes'][Config::KEY_STORE_SHOW_SQL];
        $showSql     = ($showSql === 'true')?true:false;
        $config[Config::KEY_STORE] = $stores['@attributes'];
        unset($stores['@attributes']);
        unset($stores['comment']);

        $storen = array();
        foreach($stores[Config::KEY_STORE] as $k=>$store) {
            $name    = $store['@attributes'][Config::KEY_NAME];
            if(isset($store['@attributes'][Config::KEY_STORE_SHOW_SQL])) {
                $show_sql = &$store['@attributes'][Config::KEY_STORE_SHOW_SQL];
                $show_sql = ($show_sql === 'true')?true:false;
            }

            $storen[$name] = $store['@attributes'];
            if($store == 'json') {
                $storen[$name][Config::KEY_STORE_JSON_NAME] = $store[Config::KEY_STORE_JSON_NAME];
            } else if($store == 'memory') {
                $storen[$name][Config::KEY_STORE_MEMORY_NAME] = $store[Config::KEY_STORE_MEMORY_NAME];
            } else if($store == 'xml') {
                $storen[$name][Config::KEY_STORE_XML_NAME] = $store[Config::KEY_STORE_XML_NAME];
            }
        }
        $stores = $storen;
    }
    private function parseService(&$config) {
        $services = &$config[Config::KEY_SERVICES];
        $autoInit = &$services['@attributes'][Config::KEY_SERVICE_AUTO_INIT];
        $autoInit = ($autoInit === 'true')?true:false;
        $config[Config::KEY_SERVICE] = $services['@attributes'];
        unset($services['@attributes']);
        unset($services['comment']);

        $servicen = array();
        foreach($services[Config::KEY_SERVICE] as $k=>$service) {
            $name = $service['@attributes'][Config::KEY_NAME];
            $servicen[$name] = $service['@attributes'];
        }
        $services = $servicen;
    }
    private function parseAction(&$config) {
        $actions  = &$config[Config::KEY_ACTIONS];
        $autoDisp = &$actions['@attributes'][Config::KEY_ACTION_AUTO_DISPATCH];
        $autoDisp = ($autoDisp === 'true')?true:false;
        $config[Config::KEY_ACTION] = $actions['@attributes'];
        unset($actions['@attributes']);
        unset($actions['comment']);

        $actionn = array();
        foreach($actions[Config::KEY_ACTION] as $k=>$action) {
            $name = $action['@attributes'][Config::KEY_NAME];
            if(isset($action['@attributes'][Config::KEY_ACTION_AUTO_DISPATCH])) {
                $autoDispatch   = &$action['@attributes'][Config::KEY_ACTION_AUTO_DISPATCH];
                $autoDispatch   = ($autoDispatch === 'true')?true:false;
            }
            $actionn[$name] = $action['@attributes'];
            $actionn[$name][Config::KEY_ACTION_BEANS] = $action[Config::KEY_ACTION_BEAN];
            $actionn[$name][Config::KEY_ACTION_SERVICES] = $action[Config::KEY_ACTION_SERVICE];
        }
        $actions = $actionn;
    }
}
?>
