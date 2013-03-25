<?php
/**
 * @author liaiyong
 */
class Config {
    const DEBUG = true;
    const XML_CONFIG = false;
    const FORMAT_CONFIG = 'xml';
    const FORMAT_CONFIG_PHP = 'php';
    const FORMAT_CONFIG_XML = 'xml';
    const FORMAT_CONFIG_INI = 'ini';
    const FORMAT_CONFIG_JSON = 'json';
    const XML_CONFIG_FILE = 'example/config.template.xml';//relative laywork
    const PHP_CONFIG_FILE = 'example/config.php';
    const INI_CONFIG_FILE = 'example/config.ini';
    const JSON_CONFIG_FILE = 'example/config.json';

    const CONFIG_CACHE = false;//true means opening config's cache,false means closing config's cache
    const CONFIG_CACHE_EXPIRES = 10;//expires time of cache config
    const CONFIG_CACHE_DIR = 'cache';
    const CONFIG_CACHE_FILE = 'config.php';
    const PHP_SUFFIX = '.php';
    const RESULT_JSON = 'json';//print variable json string
    const RESULT_TEXT = 'text';//print variable
    const RESULT_DUMP = 'dump';//print variable and type
    const RESULT_VAR  = 'var';//return variable
    const RESULT_JS   = 'js';//return a js callback function => callback(here is json data object or array);
    const PAGING_KEY = 'paging';
    const SEARCH_KEY = 'search';
    const PAGING_SCOPE = 0;
    const SEARCH_SCOPE = 0;
    const KEY_TYPE_STRING = 'string';
    const KEY_TYPE_NUMBER = 'number';

    const DEFAULT_AUTO_DISPATCH = true;
    const DEFAULT_DISPATCH_KEY = 'key';
    const DEFAULT_DISPATCH_STYLE = 'do*';
    const DEFAULT_METHOD = 'launch';

    const KEY_NAME = 'name';
    const KEY_ACTION = 'action';
    const KEY_ACTION_AUTO_DISPATCH = 'auto-dispatch';
    const KEY_ACTION_DISPATCH_KEY = 'dispatch-key';
    const KEY_ACTION_DISPATCH_SCOPE = 'dispatch-scope';
    const KEY_ACTION_DISPATCH_STYLE = 'dispatch-style';
    const KEY_ACTION_CLASSNAME = 'classname';
    const KEY_ACTION_BEAN = 'bean';
    const KEY_ACTION_BEANS = 'beans';
    const KEY_ACTION_SERVICE = 'service';
    const KEY_ACTION_SERVICES = 'services';
    const KEY_ACTION_RESULT = 'result';
    const KEY_ACTIONS = 'actions';
    const KEY_PROPERTY_DEFAULT = 'default';
    const KEY_BEAN = 'bean';
    const KEY_BEAN_AUTO_BUILD = 'auto-build';
    const KEY_BEAN_SCOPE = 'scope';
    const KEY_BEAN_CLASSNAME = 'classname';
    const KEY_BEAN_PROPERY = 'property';
    const KEY_BEAN_PROPERTIES = 'properties';
    const KEY_BEANS = 'beans';
    const KEY_SERVICE = 'service';
    const KEY_SERVICE_AUTO_INIT = 'auto-init';
    const KEY_SERVICE_CLASSNAME = 'classname';
    const KEY_SERVICE_STORE = 'store';
    const KEY_SERVICES = 'services';
    const KEY_RESULT = 'result';
    const KEY_RESULTS = 'results';
    const KEY_STORE = 'store';
    const KEY_STORE_AUTO_CONNECT = 'auto-connect';
    const KEY_STORE_CLASSNAME = 'classname';
    const KEY_STORE_TYPE = 'type';
    const KEY_STORE_SHOW_SQL = 'show-sql';
    const KEY_STORE_DB_HOST = 'host';
    const KEY_STORE_DB_USERNAME = 'username';
    const KEY_STORE_DB_PASSWORD = 'password';
    const KEY_STORE_DB_DATABASE = 'database';
    const KEY_STORE_DB_ENCODING = 'encoding';
    const KEY_STORE_DB_SHOW_SQL = 'show-sql';
    const KEY_STORE_MEMORY_HOST = 'host';
    const KEY_STORE_MEMORY_NAME = 'filename';
    const KEY_STORE_MEMORY_DIR = 'dir';
    const KEY_STORE_MEMORY_SUFFIX = 'suffix';
    const KEY_STORE_MEMORY_PREFIX = 'prefix';
    const KEY_STORE_JSON_HOST = 'host';
    const KEY_STORE_JSON_NAME = 'filename';
    const KEY_STORE_JSON_DIR = 'dir';
    const KEY_STORE_JSON_SUFFIX = 'suffix';
    const KEY_STORE_JSON_PREFIX = 'prefix';
    const KEY_STORE_XML_HOST = 'host';
    const KEY_STORE_XML_NAME = 'filename';
    const KEY_STORE_XML_DIR = 'dir';
    const KEY_STORE_XML_SUFFIX = 'suffix';
    const KEY_STORE_XML_PREFIX = 'prefix';
    const KEY_STORE_LDAP_HOST = 'host';
    const KEY_STORE_LDAP_VERSION = 'version';
    const KEY_STORE_LDAP_USERDN = 'userdn';
    const KEY_STORE_LDAP_BASEDN = 'basedn';
    const KEY_STORE_LDAP_PASSWORD = 'password';
    const KEY_STORE_LDAP_SHOW_FILTER = 'show-filter';
    const KEY_STORE_LDAP_CHILDNAME = 'childname';//basedn的下一级名, example basedn = 'dc=laysoft,dc=cn';下一级的dn = 'ou=other,dc=laysoft,dc=cn',ou即为下一级名
    const KEY_STORES = 'stores';

    const MAPPING_KEY_TABLES = 'tables';

    public static $config = array();
    /**
     * mapping of class's attribute to table's field or other's field
     */
    public static $mapping = array(
        'tables' => array(
            'Test'     => 'user',
            'User'     => 'user',
            'Doc'      => 'doc',
            'LdapUser' => 'other'
        ),
        'Test' => array(
            'SEQUENCE' => 'userid',

            'userid'   => 'userid',
            'username' => 'username',
            'password' => 'password'
        ),
        'User' => array(
            'SEQUENCE' => 'userid',
            'UNIQUE'   => array('userid'),
            'PRIMARY'  => 'userid',

            'userid'   => 'userid',
            'username' => 'username',
            'password' => 'password'
        ),
        'Doc' => array(
            'SEQUENCE' => 'docid',
            'UNIQUE'   => 'docid',
            'PRIMARY'  => 'docid',

            'docid'    => 'docid',
            'docname'  => 'docname',
            'describe' => 'describe'
        ),
        'LdapUser' => array(
            'userid'   => 'uid',
            'password' => 'userPassword',
            'username' => 'username',
            'role'     => 'role'
        )
    );

    /**
     * class's file mapping of your's application
     */
    public static $class = array(
            'Test'             => 'example/classes/Test.class.php',
            'TestAction'       => 'example/classes/TestAction.php',
            'TestService'      => 'example/classes/TestService.php',
            'IndexAction'      => 'example/classes/IndexAction.php',
            'User'             => 'example/classes/User.php',
            'UserAction'       => 'example/classes/UserAction.php',
            'UserService'      => 'example/classes/UserService.php',
            'Doc'              => 'example/classes/Doc.php',
            'DocAction'        => 'example/classes/DocAction.php',
            'DocService'       => 'example/classes/DocService.php',
            'LdapService'      => 'example/classes/LdapService.php'
        );

    /**
     * class's file mapping of 'laywork'
     */
    public static $clazz = array(
            'Application'       => 'src/Application.php',
            'Config'            => 'src/config/Config.php',

            'Base'              => 'src/core/Base.php',
            'Bean'              => 'src/core/Bean.php',
            'TBean'             => 'src/core/TBean.php',
            'Action'            => 'src/core/Action.php',
            'PagingAction'      => 'src/core/benefit/PagingAction.php',
            'SearchAction'      => 'src/core/benefit/SearchAction.php',
            'PSAction'          => 'src/core/benefit/PSAction.php',
            'Service'           => 'src/core/Service.php',
            'Store'             => 'src/core/Store.php',
            'Debug'             => 'src/core/Debug.php',
            'ActionException'   => 'src/core/exception/ActionException.php',
            'AutoloadException' => 'src/core/exception/AutoloadException.php',

            'Query'             => 'src/store/Query.php',//interface
            'Mysql'             => 'src/store/Mysql.php',
            'Memory'            => 'src/store/Memory.php',
            'Json'              => 'src/store/Json.php',
            'SQLite'            => 'src/store/SQLite.php',
            'Ldap'              => 'src/store/Ldap.php',
            'Mecahe'            => 'src/store/Mecahe.php',

            'ActionFactory'     => 'src/factory/ActionFactory.php',
            'BeanFactory'       => 'src/factory/BeanFactory.php',
            'ServiceFactory'    => 'src/factory/ServiceFactory.php',
            'StoreFactory'      => 'src/factory/StoreFactory.php',

            'Cell'              => 'src/util/Cell.php',
            'Condition'         => 'src/util/Condition.php',
            'Arrange'           => 'src/util/Arrange.php',
            'File'              => 'src/util/File.php',
            'Result'            => 'src/util/Result.php',
            'Paging'            => 'src/util/Paging.php',
            'Search'            => 'src/util/Search.php',
            'Scope'             => 'src/util/Scope.php',
            'Transfer'          => 'src/util/Transfer.php',

            'Security'          => 'src/security/Security.php',

            'ArrayGenerator'    => 'src/gen/ArrayGenerator.php',
            'JsonGenerator'     => 'src/gen/JsonGenerator.php',
            'SQLGenerator'      => 'src/gen/SQLGenerator.php',
            'XMLGenerator'      => 'src/gen/XMLGenerator.php',

            'FileNotFoundException' => 'src/exception/FileNotFoundException.php',

            'LogicException'              => 'src/util/Properties.php',
            'InvalidArgumentException'    => 'src/util/Properties.php',
            'Properties'                  => 'src/util/Properties.php',
            'Properties_ISection'         => 'src/util/Properties.php',
            'Properties_Section_Blank'    => 'src/util/Properties.php',
            'Properties_Section_Comment'  => 'src/util/Properties.php',
            'Properties_Section_Property' => 'src/util/Properties.php',

            'Configurator'             => 'src/config/Configurator.php',
            'ConfiguratorDefault'      => 'src/config/ConfiguratorDefault.php',
            'ConfigurationAdapter'     => 'src/config/ConfigurationAdapter.php',
            'ConfigurationAdapterJSON' => 'src/config/ConfigurationAdapterJSON.php',
            'ConfigurationAdapterINI'  => 'src/config/ConfigurationAdapterINI.php',
            'ConfigurationAdapterPHP'  => 'src/config/ConfigurationAdapterPHP.php',
            'ConfigurationAdapterXML'  => 'src/config/ConfigurationAdapterXML.php',

            'MysqlException'    => 'src/store/MysqlException.php',
            'LdapException'     => 'src/store/LdapException.php'
        );
    /**
     * load cached config
     * @return boolean
     */
    private static function loadCache() {
        global $layworkPath;
        $filename = $layworkPath.Config::CONFIG_CACHE_DIR .'/'. Config::CONFIG_CACHE_FILE;
        if(!Config::CONFIG_CACHE || !file_exists($filename) || filemtime($filename) + Config::CONFIG_CACHE_EXPIRES < time()) {
            return false;
        } else {
            Config::$config = include($filename);
            return true;
        }
    }
    /**
     * cache initialized config
     * @return boolean
     */
    private static function writeCache() {
        global $layworkPath;
        $config = &Config::$config;
        $bool   = File::write(Transfer::array2PHPContent($config),$layworkPath.Config::CONFIG_CACHE_DIR,Config::CONFIG_CACHE_FILE);
        return $bool;
    }
    /**
     * initialize and set up config
     * @return array
     */
    public static function setup() {
        global $layworkPath;

        if(Config::loadCache()) {
            return Config::$config;
        }

		$configurator = Config::getConfigurator();
        switch(Config::FORMAT_CONFIG) {
            case Config::FORMAT_CONFIG_PHP:
		        Config::$config = $configurator->configure($layworkPath.Config::PHP_CONFIG_FILE);
                break;
            case Config::FORMAT_CONFIG_XML:
		        Config::$config = $configurator->configure($layworkPath.Config::XML_CONFIG_FILE);
                break;
            case Config::FORMAT_CONFIG_INI:
		        Config::$config = $configurator->configure($layworkPath.Config::INI_CONFIG_FILE);
                break;
            case Config::FORMAT_CONFIG_JSON:
		        Config::$config = $configurator->configure($layworkPath.Config::JSON_CONFIG_FILE);
                break;
        }

        Config::writeCache();

        return Config::$config;
    }
    /**
     * Creates a configurator instance based on the provided 
     * configurator class. If no class is given, returns an instance of
     * the default configurator.
     * 
     * @param string|Configurator $configurator The configurator class 
     * or Configurator instance.
     */
    public static function getConfigurator($configurator = null) {
        $instance = false;
        if ($configurator === null) {
            $instance = new ConfiguratorDefault();
        } else if(is_object($configurator)) {
            if ($configurator instanceof Configurator) {
                $instance = &$configurator;
            } else {
                $instance = new ConfiguratorDefault();
            }
        } else if (is_string($configurator)) {
            $instance = new $configurator();
            if (!($instance instanceof Configurator)) {
                unset($instance);
                $instance = new ConfiguratorDefault();
            }
        } else {
            $instance = new ConfiguratorDefault();
        }

        return $instance;
    }
}
?>
