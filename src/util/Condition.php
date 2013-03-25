<?php
class Condition extends Base {
    private $index = 0;
    private $conds = array();
    private $orpos = false;
    public function __construct() {
    }
    public function getConds() {
        return $this->conds;
    }
    public function setOrpos($orpos) {
        $this->setOrpos($orpos);
    }
    public function getOrpos() {
        return $this->orpos;
    }
    /**
     * @param Cell|array<Cell> $cell 
     * @return int the index of cell or the first index of cells's first or false
     */
    public function putCell($cell) {
        $conds  = &$this->conds;
        $index  = &$this->index;
        $return = $index;

        if(is_array($cell)) {
            foreach($cell as $c) {
                if(!is_a($c,'Cell')) continue;
                $conds[$index] = $c;
                $index++;
            }
        } else {
            if(!is_a($cell,'Cell')) return false;
            $conds[$index] = $cell;
            $index++;
        }

        return $return;
    }
    /**
     * @param $index int the index of cell
     */
    public function removeCell($index) {
        $conds = &$this->conds;
        if(array_key_exists($index,$conds)) {
            unset($conds[$index]);
            return true;
        } else {
            return false;
        }
    }
    /**
     * no mysql_real_escape_string
     * 
     * to connect all the cell to SQL string
     * use 'OR' form index $orpos,if $orpos = true or 0 all the cell will connect with 'OR'
     * 
     */
    public function toSQLString() {
        $orpos = &$this->orpos;
        $conds = &$this->conds;
        $sql   = '';
        foreach($conds as $k=>$cell) {
            if($sql === '') {
                $sql .= $cell->toSQLString();
            } else {
                $sql .= ( $orpos === true || is_numeric($orpos) && $k >= $orpos )?' OR ':' AND ';
                $sql .= $cell->toSQLString();
            }
        }
        return $sql;
    }
}
?>
