<?php
class Debug extends Base {
    public $mixes = array();
    private function __construct() {}
    private static $instance = null;
    private static function getInstance() {
        if(self::$instance == null) {
            self::$instance = new Debug();
        }
        return self::$instance;
    }
    public static function push(&$mixed) {
        $instance = self::getInstance();
        $mixes    = &$instance->mixes;
        array_push($mixes, $mixed);
    }
    public static function out() {
        $instance = self::getInstance();
        $mixes    = &$instance->mixes;
        if(Config::DEBUG) {
            echo "<pre>";print_r($mixes);echo "</pre>";
        }
    }
    public static function last() {
        $instance = self::getInstance();
        $mixes    = &$instance->mixes;
        if(Config::DEBUG) {
            echo "<pre>";print_r(end($mixes));echo "</pre>";
        }
    }
    public static function time() {
        global $sTime;
        list($usec, $sec) = explode(" ", microtime());
        $eTime = (float)$usec + (float)$sec;
        if(Config::DEBUG) {
            echo "<pre>";echo ("start time : ".$sTime);echo "</pre>";
            echo "<pre>";echo ("end   time : ".$eTime);echo "</pre>";
        }
        unset($sTime);unset($eTime);
    }
}
?>
