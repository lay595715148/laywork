<?php
//start time
list($usec, $sec) = explode(" ", microtime());
$sTime = (float)$usec + (float)$sec;

global $layworkPath;
if(strpos($layworkPath, './') === 0) { $layworkPath = substr($layworkPath, 2); }
//valid integrity of $layworkPath 
//

/**
 * require Config class file
 */
require_once $layworkPath.'src/config/Config.php';

//autoload function
function __autoload($classname) {
    global $layworkPath;
    if(array_key_exists($classname,Config::$clazz)) {
        //echo "<pre>require_once ".$layworkPath.Config::$clazz[$classname]."</pre>";
        require_once $layworkPath.Config::$clazz[$classname];
    } else if(array_key_exists($classname,Config::$class)) {
        //echo "<pre>require_once ".$layworkPath.Config::$class[$classname]."</pre>";
        require_once $layworkPath.Config::$class[$classname];
    } else {
        throw new AutoloadException("No file for class:'$classname'!");
    }
    if(!class_exists($classname) && !interface_exists($classname)) {
        throw new AutoloadException("No class:'$classname'!");
    }

}

/**
 * 
 * @author liaiyong
 * 
 */
class Application extends Base {
	/**
	 * static start application
     * @return void
	 */
    public static function start() {
        $self = self::getInstance();
        $self->run();
    }

    private static $instance = null;
    private function __construct() {}
    public static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new Application();
        }
        return self::$instance;
    }
    /**
     * running application
     * @return void
     */
    public function run() {
        $config = &Config::setup();
        $actions = &$config[Config::KEY_ACTIONS];
        $actionKey = $this->generActionKey();
        if(array_key_exists($actionKey, $actions)) {
            $actionConfig = &$actions[$actionKey];
        } else {
            throw new ActionException("Action:$actionKey is not exists");
        }
        if(is_array($actionConfig) && $actionConfig[Config::KEY_ACTION_CLASSNAME]) {
            $classname = $actionConfig[Config::KEY_ACTION_CLASSNAME];
            $actionObj = ActionFactory::getInstance($classname, $actionConfig);
            $actionObj->init();
            if($actionConfig[Config::KEY_ACTION_AUTO_DISPATCH]) {
                $actionObj->dispatch();
            } else {
                $actionObj->launch();
            }
        } else {
            throw new ActionException("Action:$actionKey's config is error");
        }
    }
    /**
     * generate action key of current
     * @return string
     */
    public function generActionKey() {
        global $layworkPath;
        $ext[0] = pathinfo(__FILE__);
        $ext[1] = pathinfo($_SERVER['PHP_SELF']);
        $ext[2] = $layworkPath;
        $upcount = substr_count($layworkPath,'../');
        if($layworkPath && $upcount) {
            $array = explode('/',$ext[1]['dirname']);
            $count = count($array);
            $str   = '';
            for($i = 0;$i < $upcount;$i++) {
                $index = $count - 1 - $i;
                $str = $array[$index].'.'.$str;
            }
            $key = $str.$ext[1]['filename'];
        } else {
            $key = str_replace('/','..',$layworkPath).$ext[1]['filename'];
        }
        return $key;
    }
    /**
     * 
     * generate action key of current
     * @return string
     */
    public function generOldActionKey() {
        global $layworkPath;
        $arrPre = explode('/',substr(__FILE__, 0, strrpos(__FILE__, '/src/Application.php')));
        $arrSelf = explode('/',$_SERVER['PHP_SELF']);
        //$arrFile = explode('/',__FILE__);
        $found = false;$iStart = false;$iEnd = false;$jStart = false;$jEnd = false;$up = 0;$down = 0;
        //find the postion of self
        foreach($arrPre as $k=>$v) {
            $temp = array_search($v,$arrSelf);
            if($temp === false && !$found) {
            } else if($temp != false){
                $found = true;
                if($iStart === false) {
                    $iStart = $temp;
                    $jStart = $k;
                }
                $iEnd = $temp;
                $jEnd = $k;
            } else if($temp === false && $found) {
                break;
            }
        }
        $up = count($arrPre) - $jEnd - 1;
        $down = count($arrSelf) - $iEnd - 2;
        //judge found sign
        if($found) {
            //$layworkPath = '';
            $actionName = '';
            for($i = 0;$i < $up;$i++) {
                $actionName = '../'.$actionName;
                //$layworkPath = $layworkPath.$arrPre[$jEnd + 1 + $i].'/';
            }
            for($i = 0;$i < $down;$i++) {
                $actionName = $actionName.$arrSelf[$iEnd + 1 + $i].'/';
                //$layworkPath = '../'.$layworkPath;
            }
        } else {
            $arrLay = explode('/',$layworkPath);
            $actionName = '';
            if($arrLay[0] === '.') {
                $arrLay = explode('/',substr($layworkPath,2));
            }
            foreach($arrLay as $k=>$v) {
                if($v) {
                    $actionName = '../'.$actionName;
                }
            }
            //$layworkPath = $layworkPath;
        }
        $temp = $actionName.basename($_SERVER['PHP_SELF'],Config::PHP_SUFFIX);
        $actionName = str_replace('/','-',$temp);
        return $actionName;
    }
}

/**
 * start running
 */
try {
    Application::start();
} catch(Exception $e) {
    echo $e->getMessage();
}

//Debug::last();
//Debug::time();
?>
