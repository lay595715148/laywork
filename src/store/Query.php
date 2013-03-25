<?php
interface Query {
    /**
     * execute data store query
     * @param string|mixed $qStr
     * @param string $encode
     * @param boolean $showQStr
     */
    public function query($qStr, $encode = 'UTF8', $showQStr = false);
    /**
     * insert data into target store
     * @param string $target
     * @param string|array $fields
     * @param string|array $values
     */
    public function insert($target, $fields = '', $values = '');
    /**
     * delete data from target store
     * @param string $target
     * @param array|Condition $condition
     */
    public function delete($target, $condition = '');
    /**
     * update data from target store
     * @param string $target
     * @param string|array $fields
     * @param string|array $values
     * @param array|Condition $condition
     */
    public function update($target, $fields = '', $values = '', $condition = '');
    /**
     * select data from target store
     * @param string $target
     * @param string|array $fields
     * @param string|array|Condition $condition
     * @param string|array $order
     * @param string $limit
     */
    public function select($target, $fields = '', $condition = '', $group = '', $order = '', $limit = '');
    /**
     * change query/select result to array
     * @param integer $count
     * @param mixed $result
     */
    public function toArray($count = 0, $result = '');
    /**
     * get query/select/insert/delete/update result
     */
    public function toResult();
    /**
     * get the count of query/select/insert/delete/update result
     * @param mixed $result
     */
    public function toCount($result = '');
}
?>
