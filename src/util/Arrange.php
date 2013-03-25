<?php
/**
 * 
 */
class Arrange extends Base {
    private $index = 0;
    private $order = array();
    public function getOrder() {
        return $this->order;
    }
    public function putOrder($field,$desc = false) {
        $order = &$this->order;
        $index = &$this->index;
        $return = $index;

        if(is_array($field)) {
            foreach($field as $f) {
                $order[$index] = array('field'=>$f,'desc'=>$desc);
                $index++;
            }
        } else {
            $order[$index] = array('field'=>$field,'desc'=>$desc);
            $index++;
        }
        return $return;
    }
    public function removeOrder($index) {
        $order = &$this->order;
        if(array_key_exists($index,$order)) {
            unset($order[$index]);
            return true;
        } else {
            return false;
        }
    }
    public function toSQLString() {
        $order = &$this->order;
        $sql   = '';
        foreach($order as $k=>$ord) {
            if($sql === '') {
                $sql .= '`'.$ord['field'].(($ord['desc'])?'` DESC ':'` ASC ');
            } else {
                $sql .= ',';
                $sql .= '`'.$ord['field'].(($ord['desc'])?'` DESC ':'` ASC ');
            }
        }
        return $sql;
    }
}
?>
