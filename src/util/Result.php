<?php
class Result {
    private static $instance = null;
    public static function getInstance($invoke = '') {
        if(self::$instance == null) {
            self::$instance = new Result($invoke);
        }
        return self::$instance;
    }

    private $result = array();
    private $invoke = '';
    private function __construct($invoke = '') {
        $this->invoke = $invoke;
    }

    public function __invoke() {
        $result = &$this->result;
        $invoke = &$this->invoke;

        switch($invoke) {
            case Config::RESULT_JSON:
                echo json_encode($result);
                break;
            case Config::RESULT_TEXT:
                echo "<pre>";print_r($result);echo "</pre>";
                break;
            case Config::RESULT_DUMP:
                echo "<pre>";var_dump($result);echo "</pre>";
                break;
            case Config::RESULT_JS:
                echo "callback(",json_encode($result),");";
                break;
            case Config::RESULT_VAR:
                return $result;
                break;
            default:
                return $result;
                break;
        }
    }

    public function push($arg, $key = '') {
        $result = &$this->result;
        if($key) {
            $result[$key] = $this->iterator($arg);
        } else {
            $result[] = $this->iterator($arg);
        }
    }
    private function iterator($arg) {
        if(is_array($arg)) {
            foreach($arg as $k=>$ar) {
                $arg[$k] = $this->iterator($ar);
            }
            return $arg;
        } else if(is_object($arg)){
            if(is_subclass_of($arg, 'Bean')) {
                return $arg();
            } else {
                return get_class($arg).' object';
            }
        } else {
            return $arg;
        }
    }
    public function shift($key = '') {
        $result = &$this->result;
        if($key && is_string($key) && array_key_exists($key, $result)) {
            $ret = $result[$key];
            $result = array_diff_key($result, array($key=>$ret));
            return $ret;
        } else {
            $ret = array_shift($result);
            return $ret;
        }
    }
    public function pop($key = '') {
        $result = &$this->result;
        if($key && is_string($key) && array_key_exists($key, $result)) {
            $ret = $result[$key];
            $result = array_diff_key($result, array($key=>$ret));
            return $ret;
        } else {
            $ret = array_pop($result);
            return $ret;
        }
    }
    public function get($key = '') {
        $result = &$this->result;
        if($key === -1){
            return end($result);
        } else if((is_string($key) || is_numeric($key)) && array_key_exists($key, $result)) {
            return $result[$key];
        } else {
            return false;
        }
    }
}
?>
