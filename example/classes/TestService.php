<?php
class TestService extends Service {
    public function read($args = '') {
        $user   = new Test();
        $fields = $user->toFields();
        $cond   = new Condition();
        $filter = "userid:=1&username:~12'3";
        $cell   = Cell::parseFilterString($filter);
        $index  = $cond->putCell($cell);
        $arrge  = new Arrange();
        $index  = $arrge->putOrder(array('userid','username'),true);
        $result = $this->store->select('user',$fields,(is_a($this->store,'Mysql'))?$cond:array('userid'=>1),'',$arrge);//
        $user   = $this->store->toArray(1);
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
