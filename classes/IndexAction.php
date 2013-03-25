<?php
class IndexAction extends Action {
    public function launch() {
        $config = &$this->config;
        $result = &$this->result;
        //echo "<pre>";
        //print_r(Config::$config);
        //print_r($xmlconfig);
        $secret = Security::encrypt(Security::ldapmd5(Security::random()));
        $txt = Security::decrypt($secret);
        $result->push(uniqid());
        $result->push($secret);
        $result->push($txt);
        $cond = new Condition();
        $filter = "userid:=1&username:~12'3";
        $cell = Cell::parseFilterString($filter);
        $cond->putCell($cell);
        $result->push($cond->toSQLString());
        
        //$result->push(Config::$config);
        $result($config);
        //echo "</pre>";
    }
    public function doLaunch() {
        $config = &$this->config;
        $result = &$this->result;
        $result->push(Config::$config,'config');
        //echo "<pre>";
        $result($config);
        //echo "</pre>";
    }
}
?>
