<?php
class UserAction extends Action {
    public function launch() {
        $result = &$this->result;
        $config = &$this->config;
        $UserService = &$this->getService('UserService');
        $user = $UserService->read();
        $bean = new User();
        $bean->build($user);
        echo "<pre>";
        $result->push($bean, 'result_user');
        Debug::push($this->beans);
        echo "</pre>";
    }
}
?>
