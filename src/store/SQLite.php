<?php 
class SQLite extends Store implements Query {
    private $link;
    private $result;
    public function connect() {
    }
    public function close() {
    }
    public function query($qStr, $encode = 'UTF8', $showQStr = false) {
    }
    public function insert($target, $fields = '', $values = '') {
    }
    public function delete($target, $condition = '') {
    }
    public function update($target, $fields = '', $values = '', $condition = '') {
    }
    public function select($target, $fields = '', $condition = '', $group = '', $order = '', $limit = '') {
    }
    public function toArray($count = 0, $result = '') {
    }
    public function toResult() {
    }
    public function toCount($result = '') {
    }
}
?>
