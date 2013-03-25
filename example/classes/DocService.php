<?php
class DocService extends Service {
    public function read($args = '') {
         $result = $this->store->select('doc','',array('docid'=>2));//
         $doc = $this->store->toArray(1);
         return $doc;
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
