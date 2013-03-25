<?php
class UserService extends Service {
    public function read($args = '') {
         $result = $this->store->select('user','',array('userid'=>2));//
         $user = $this->store->toArray(1);
         return $user;
    }
    public function reads($args = '', $order = '', $paging = '') {
    }
    public function write($args) {
    }
    public function modify($args, $values = '') {
    }
    public function remove($args) {
    }
}
?>
