<?php
class TestAction extends Action {
    public function doLaunch() {
        $config = &$this->config;
        echo "<pre>";print_r($config);echo "</pre>";
    }
    public function launch() {
        $result = &$this->result;
        $config = &$this->config;
        
        $test = &$this->getBean('Test');
        $testService = &$this->getService('TestService');
        $testServiceMysql = &$this->getService('TestService-mysql');
        $user      = $testService->read();
        $userMysql = $testServiceMysql->read();
        $bean = new Test();
        $bean->build($user);
        //echo "<pre>";
        //$paging = new Paging();
        //$paging->build();
        //$paging->setCount(88);
        //$paging->carry();
        //print_r($this);
        //print_r($testS);
        //print_r($testS_1);
        //print_r($this->beans);
        $result->push($test, 'request_user');
        $result->push($bean, 'result_user');
        //print_r($testService);print_r($testServiceMysql);print_r($user);print_r($userMysql);
        $result->push(array($user, $userMysql), 'result_users');
        //$result->push($paging->toPages(),'pages');
        $result($config);
        //echo File::read('json.json');
        //Debug::push($this->result);
        //echo "</pre>";
    }
}
?>
