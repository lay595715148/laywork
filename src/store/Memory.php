<?php
/**
 * Memory data connect class
 * 
 * @category   
 * @package   store 
 * @author    liaiyong <liaiyong@dcux.com>
 * @version   1.0 
 * @copyright 2005-2012 dcux Inc.
 * @link      http://www.dcux.com
 * 
 */
class Memory extends Store implements Query {
    const PHP_FILE_SUFFIX = '.php';
    const PHP_SEQUENCE_NAME = 'sequences';

    private $link    = array();
    private $result  = array();
    /**
     * connect link
     * 
     * @return mixed
     */
    public function connect() {
        $config = &$this->config;
        $link   = &$this->link;
        $host   = (array_key_exists(Config::KEY_STORE_MEMORY_HOST,$config))?$config[Config::KEY_STORE_MEMORY_HOST]:false;
        $name   = (array_key_exists(Config::KEY_STORE_MEMORY_NAME,$config))?$config[Config::KEY_STORE_MEMORY_NAME]:false;
        $dir    = (array_key_exists(Config::KEY_STORE_MEMORY_DIR,$config))?$config[Config::KEY_STORE_MEMORY_DIR]:false;

        if($name) {
            return $this->choose($name);
        } else if($dir && $host) {
            global $layworkPath;
            $path = $layworkPath.$host.'/'.$dir.'/';
            $link = $this->ls($path,Memory::PHP_FILE_SUFFIX);
            return ($link)?true:false;
        } else {
            return false;
        }
        return true;
    }
    /**
     * close link
     * 
     * @return mixed
     */
    public function close() {
        $link = &$this->link;
        $link = array();
    }
    /**
     * choose a
     */
    private function choose($name) {
        global $layworkPath;
        $config = &$this->config;
        $link   = &$this->link;
        $host   = $config[Config::KEY_STORE_MEMORY_HOST];
        $dir    = $config[Config::KEY_STORE_MEMORY_DIR];
        if($host && $dir){
            $path = $layworkPath.$host.'/'.$dir.'/';
            $link = $this->load($path,$name,Memory::PHP_FILE_SUFFIX);
        }
        return ($link)?true:false;
    }
    /**
     * @return array
     */
    private function ls($path, $mask) {
        $config  = &$this->config;
        $return  = array();
        $handler = opendir($path);

        while ($f = readdir($handler)) {
            if ($f == '.' || $f == '..' || !eregi($mask, $f) ) continue;
            $file     = $path.$f;
            $filename = basename($f,$mask);
            if (is_dir($file)) continue;
            if(file_exists($file)) {
                $return[$filename] = include_once($file);
            }
        }

        closedir($handler);
        return $return;
    }
    /**
     * @param string $path 
     * @param mixed $name 
     * @param string $mask 
     * @return array
     */
    private function load($path,$name,$mask) {
        $return   = array();
        $config   = &$this->config;
        $prefix   = ($config[Config::KEY_STORE_MEMORY_PREFIX])?$config[Config::KEY_STORE_MEMORY_PREFIX]:'';
        $suffix   = ($config[Config::KEY_STORE_MEMORY_SUFFIX])?$config[Config::KEY_STORE_MEMORY_SUFFIX]:'';
        if(is_string($name)) {
            $name = explode(',',$name);
        }
        if(is_array($name)) {
            foreach($name as $v) {
                $file     = $path.$prefix.$v.$suffix.$mask;
                $filename = $prefix.$v.$suffix;
                if(file_exists($file)) {
                    $return[$filename] = include_once($file);
                }
            }
        }
        return $return;
    }

    public function query($sql,$encode = 'UTF-8',$showsql = false) {

        $this->result = false;
    }
    /**
     * 查询记录
     * 
     * @param string $table 存储名
     * @param mixed $fields 查询的字段
     * @param mixed $condition 查询条件
     * @param mixed $order 排序语句
     * @param mixed $limit 取得记录
     * @return mixed
     */
    public function select($name, $fields = '', $condition = '', $group = '', $order = '', $limit = '') {
        global $layworkPath;
        $config   = &$this->config;
        $link     = &$this->link;
        $result   = &$this->result;
        $host     = $config[Config::KEY_STORE_MEMORY_HOST];
        $dir      = $config[Config::KEY_STORE_MEMORY_DIR];
        $prefix   = ($config[Config::KEY_STORE_MEMORY_PREFIX])?$config[Config::KEY_STORE_MEMORY_PREFIX]:'';
        $suffix   = ($config[Config::KEY_STORE_MEMORY_SUFFIX])?$config[Config::KEY_STORE_MEMORY_SUFFIX]:'';

        $classname = $this->getClassByName($name);
        $mapping   = Config::$mapping[$classname];

        $basename = $prefix.$name.$suffix;
        $path     = $layworkPath.$host.'/'.$dir.'/';
        $data     = $link[$basename];
        if(!$data || !is_array($data)) return;

        $result = array();
        $i      = 0;
        $start  = 0;
        $end    = 0;

        if($limit && is_string($limit)) {
            $temp = explode(",",$limit);
            if(count($temp) == 2) {
                $start = 0 + substr($temp[0],6);
                $end   = 0 + $temp[1];
            } else {
                $start = 0;
                $end   = 0 + substr($temp[0],6);
            }
        }

        foreach($data as $k=>$v) {
            $clone = array();
            if(is_array($fields)) {
                foreach($fields as $field) {
                    if(array_search($field,$mapping)) {
                        $clone[$field] = $v[$field];
                    } else if(array_key_exists($field,$mapping)) {
                        $temp = $mapping[$field];
                        $clone[$temp] = $v[$temp];
                    }
                }
            } else if(empty($fields)) {
                $clone = $v;
            }

            if(is_array($condition)) {
                $is = true;
                foreach($condition as $field=>$value) {
                    if(array_key_exists($field,$mapping) && !array_search($field,$mapping))
                        $field = $mapping[$field];
                    if($v[$field] != $value) {
                        $is = false;break;
                    }
                }
                if($is) {
                    if( $i >= $start ) $result[] = $clone;
                    $i++;
                }
            } else if(!$condition) {
                if( $i >= $start ) $result[] = $clone;
                $i++;
            } else {
                $i++;
            }

            if(($i - $start) >= $end && $end > 0) break;
        }

        return $result;
    }
    /**
     * 插入记录
     * 
     * @param string $name 存储名
     * @param mixed $fields 插入字段
     * @param mixed $values 插入字段对应的值
     * @return mixed
     */
    public function insert($name,$fields = '',$values = '') {
        global $layworkPath;
        $config   = &$this->config;
        $link     = &$this->link;
        $result   = &$this->result;
        $host     = $config[Config::KEY_STORE_MEMORY_HOST];
        $dir      = $config[Config::KEY_STORE_MEMORY_DIR];
        $prefix   = ($config[Config::KEY_STORE_MEMORY_PREFIX])?$config[Config::KEY_STORE_MEMORY_PREFIX]:'';
        $suffix   = ($config[Config::KEY_STORE_MEMORY_SUFFIX])?$config[Config::KEY_STORE_MEMORY_SUFFIX]:'';

        $classname = $this->getClassByName($name);
        $mapping   = Config::$mapping[$classname];

        $basename = $prefix.$name.$suffix;
        $seq_name = $prefix.Memory::PHP_SEQUENCE_NAME;
        $path     = $layworkPath.$host.'/'.$dir.'/';
        $content  = &$link[$basename];
        $sequence = &$link[$seq_name];
        $increase = false;
        if(!$content || !is_array($content)) $content = array();

        $clone = array();
        if(!empty($fields) && is_array($fields)) {
            foreach($fields as $field) {
                if(array_search($field,$mapping)) {
                    $clone[$field] = $values[$field];
                } else if(array_key_exists($field,$mapping)) {
                    $temp = $mapping[$field];
                    $clone[$temp] = $values[$field];
                }
            }
        } else if(empty($fields)) {
            foreach($values as $field=>$value) {
                if(array_search($field,$mapping)) {
                    $clone[$field] = $value;
                } else if(array_key_exists($field,$mapping)) {
                    $temp = $mapping[$field];
                    $clone[$temp] = $value;
                }
            }
        }
        //auto increase
        if(!empty($clone) && $sequence && $sequence[$name]) {
            foreach($sequence[$name] as $field=>$value) {
                if(!empty($clone[$field])) {
                    $sequence[$name][$field] = $clone[$field];
                    $increase = true;
                } else {
                    if(array_search($field,$mapping)) {
                        $sequence[$name][$field]++;
                        $clone[$field] = $sequence[$name][$field];
                        $increase = true;
                    } else if(array_key_exists($field,$mapping)) {
                        $temp = $mapping[$field];
                        $sequence[$name][$field]++;
                        $clone[$temp] = $sequence[$name][$field];
                        $increase = true;
                    }
                }
            }
        }
        //unique
        if(false) return;

        if(empty($clone)) {
            $result = false;
        } else {
            $content = array_merge($content, array($clone));
            $result  = File::write(Transfer::array2PHPContent($content), $path, $basename, Memory::PHP_FILE_SUFFIX);
            if($result && $increase) {
                File::write(Transfer::array2PHPContent($sequence), $path, $prefix.Memory::PHP_SEQUENCE_NAME.$suffix, Memory::PHP_FILE_SUFFIX);
                $link[$seq_name] = $sequence;
            }
        }

        return $result;
    }
    /**
     * 更新记录
     * 
     * @param string $name 存储名
     * @param mixed $fields 更新字段
     * @param mixed $values 更新字段对应的值
     * @param mixed $condition 更新条件
     * @return mixed
     */
    public function update($name,$fields = '',$values = '',$condition = '') {
        global $layworkPath;
        $config   = &$this->config;
        $link     = &$this->link;
        $result   = &$this->result;
        $host     = $config[Config::KEY_STORE_MEMORY_HOST];
        $dir      = $config[Config::KEY_STORE_MEMORY_DIR];
        $prefix   = ($config[Config::KEY_STORE_MEMORY_PREFIX])?$config[Config::KEY_STORE_MEMORY_PREFIX]:'';
        $suffix   = ($config[Config::KEY_STORE_MEMORY_SUFFIX])?$config[Config::KEY_STORE_MEMORY_SUFFIX]:'';

        $classname = $this->getClassByName($name);
        $mapping   = Config::$mapping[$classname];

        $basename = $prefix.$name.$suffix;
        $path     = $layworkPath.$host.'/'.$dir.'/';
        $content  = &$link[$basename];
        if(!$content || !is_array($content)) return;

        $change = false;
        for($i = 0;$i < count($content);$i++) {
            if(is_array($condition)) {
                $is = true;
                foreach($condition as $field=>$value) {
                    if(array_key_exists($field,$mapping) && !array_search($field,$mapping))
                        $field = $mapping[$field];
                    if($content[$i][$field] != $value) {
                        $is = false;break;
                    }
                }
                if($is) {
                    if(is_array($values) && is_array($fields)) {
                        foreach($fields as $field) {
                            if(array_search($field,$mapping)) {
                                $content[$i][$field] = $values[$field];
                                $change = true;
                            } else if(array_key_exists($field,$mapping)) {
                                $temp = $mapping[$field];
                                $content[$i][$temp] = $values[$field];
                                $change = true;
                            }
                        }
                    } else if(is_array($values)) {
                        foreach($values as $field=>$value) {
                            if(array_search($field,$mapping)) {
                                $content[$i][$field] = $values[$field];
                                $change = true;
                            } else if(array_key_exists($field,$mapping)) {
                                $temp = $mapping[$field];
                                $content[$i][$temp] = $values[$field];
                                $change = true;
                            }
                        }
                    }
                }
            } else if(!$condition) {
                if(is_array($values) && is_array($fields)) {
                    foreach($fields as $field) {
                        if(array_search($field,$mapping)) {
                            $content[$i][$field] = $values[$field];
                            $change = true;
                        } else if(array_key_exists($field,$mapping)) {
                            $temp = $mapping[$field];
                            $content[$i][$temp] = $values[$field];
                            $change = true;
                        }
                    }
                } else if(is_array($values)) {
                    foreach($values as $field=>$value) {
                        if(array_search($field,$mapping)) {
                            $content[$i][$field] = $values[$field];
                            $change = true;
                        } else if(array_key_exists($field,$mapping)) {
                            $temp = $mapping[$field];
                            $content[$i][$temp] = $values[$field];
                            $change = true;
                        }
                    }
                }
            }
        }

        if($change) {
            //$content = $data;
            $result = File::write(Transfer::array2PHPContent($content), $path, $basename, Memory::PHP_FILE_SUFFIX);
            //if($result) $link[$basename] = $content;
        } else {
            $result = false;
        }

        return $result;
    }
    /**
     * 删除记录
     * 
     * @param string $name 存储名
     * @param mixed $condition 删除条件
     * @return mixed
     */
    public function delete($name,$condition = '') {
        global $layworkPath;
        $config   = &$this->config;
        $link     = &$this->link;
        $result   = &$this->result;
        $host     = $config[Config::KEY_STORE_MEMORY_HOST];
        $dir      = $config[Config::KEY_STORE_MEMORY_DIR];
        $prefix   = ($config[Config::KEY_STORE_MEMORY_PREFIX])?$config[Config::KEY_STORE_MEMORY_PREFIX]:'';
        $suffix   = ($config[Config::KEY_STORE_MEMORY_SUFFIX])?$config[Config::KEY_STORE_MEMORY_SUFFIX]:'';

        $classname = $this->getClassByName($name);
        $mapping   = Config::$mapping[$classname];

        $basename = $prefix.$name.$suffix;
        $path     = $layworkPath.$host.'/'.$dir.'/';
        $content  = &$link[$basename];
        if(!$content || !is_array($content)) return;

        $temp   = array();
        $change = false;
        //for($i = 0;$i<count($content);$i++) {
        foreach($content as $k=>$v) {
            $clone = $v;

            if(is_array($condition)) {
                $is = true;
                foreach($condition as $field=>$value) {
                    if(array_key_exists($field,$mapping) && !array_search($field,$mapping)) {
                        $field = $mapping[$field];
                    }
                    if($v[$field] != $value) {
                        $is = false;break;
                    }
                }
                if($is) {
                    $change = true;
                } else {
                    $temp[] = $clone;
                }
            } else if(!$condition) {
                $change = true;
            }
        }

        if($change) {
            $content = $temp;unset($temp);
            $result = File::write(Transfer::array2PHPContent($content),$path,$basename,Memory::PHP_FILE_SUFFIX);
            //if($result) $link[$basename] = $content;
        } else {
            $result = false;
        }

        return $result;
    }
    private function getClassByName($name) {
        $tables    = &Config::$mapping[Config::MAPPING_KEY_TABLES];
        $classname = array_search($name,$tables);
        $classname = (is_array($classname))?$classname[0]:$classname;
        return $classname;
    }
    public function toArray($count = 0,$result = '') {
        $result = ($result)?$result:$this->result;
        $rows   = array();

        if($count == 1) {
            $rows = $result[0];
        } else if($count != 0) {
            $i = 0;
            foreach($result as $k=>$v) {
                if($i<$count) {
                    $rows[$i] = $result[$k];
                    $i++;
                } else {
                    break;
                }
            }
        } else {
            $i = 0;
            foreach($result as $k=>$v) {
                $rows[$i] = $result[$k];
                $i++;
            }
        }

        return $rows;
    }
    public function toResult() {
        return $this->result;
    }
    /**
     * 查询表的记录数
     * 
     * @param string $table 表名
     * @param mixed $condition 查询条件
     * @return mixed
     */
    public function count($name,$condition = '') {
        $config   = &$this->config;
        $link     = &$this->link;
        $result   = &$this->result;
        $prefix   = $config[Config::KEY_STORE_MEMORY_PREFIX];
        $suffix   = $config[Config::KEY_STORE_MEMORY_SUFFIX];

        $basename = $prefix.$name.$suffix;
        $content  = $link[$basename];
        if(!$content || !is_array($content)) return;
        return count($content);
    }
    /**
     * 查询结果的记录数
     * 
     * @param string $table 表名
     * @param mixed $condition 查询条件
     * @return mixed
     */
    public function toCount($isSelect = true,$result = '') {
        $result = ($result)?$result:$this->result;
        if($isSelect) 
            return count($result);
        else 
            return count($this->link);
    }
}
?>
