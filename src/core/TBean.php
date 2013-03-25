<?php
/**
 * this is a magic Bean,it's useful for Mysql or other database store.
 * it depends property-field mapping
 */
abstract class TBean extends Bean{
    public function toTable() {
        $className = get_class($this);
        return Config::$mapping[Config::MAPPING_KEY_TABLES][$className];
    }
    public function toField($property) {
        $className = get_class($this);
        $mapping   = Config::$mapping[$className];
        $field     = ($mapping && array_key_exists($property,$mapping))?$mapping[$property]:$property;
        return $field;
    }
    public function toFields() {
        $className = get_class($this);
        $mapping   = Config::$mapping[$className];
        $fields    = array();
        if($mapping) {
            foreach($this->toArray() as $k=>$v) {
                $name     = $k;
                $fields[] = array_key_exists($name,$mapping)?$mapping[$name]:$name;
            }
        }
        return $fields;
    }
    public function toValues() {
        $className = get_class($this);
        $mapping   = Config::$mapping[$className];
        $values    = array();
        if($mapping) {
            foreach($this->toArray() as $k=>$v) {
                $name           = $k;
                $field          = array_key_exists($name,$mapping)?$mapping[$name]:$name;
                $values[$field] = $v;
            }
        }
        return $values;
    }
    public function rowsToEntities($rows) {
        $entities  = array();
        $className = get_class($this);
        if(is_array($rows)) {
            foreach($rows as $k=>$row) {
                if(is_array($row) && class_exists($className)) {
                    $bean       = new $className();
                    $return     = $bean->rowToEntity($row);
                    $entities[] = $bean;
                }
            }
        }
        return $entities;
    }
    public function rowToEntity($row) {
        $className = get_class($this);
        $mapping   = Config::$mapping[$className];
        if(is_array($row) && $mapping) {
            foreach($this->toArray() as $k=>$v) {
                    $name     = $k;
                    $key      = array_key_exists($name,$mapping)?$mapping[$name]:$name;
                    $this->$k = $row[$key];
            }
        }
        return $this;
    }
    public function rowsToArray($rows) {
        $arrs      = array();
        $className = get_class($this);
        if(is_array($rows)) {
            foreach($rows as $k=>$row) {
                if(is_array($row) && class_exists($className)) {
                    $bean   = new $className();
                    $arr    = $bean->rowToArray($row);
                    $arrs[] = $arr;
                }
            }
        }
        return $arrs;
    }
    public function rowToArray($row) {
        $arr = array();
        if(is_array($row)) {
            $bean = $this->rowToEntity($row);
            $arr  = $bean->toArray();
        }
        return $arr;
    }
}
?>
