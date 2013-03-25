<?php
class Ldap extends Store implements Query {
    const OPTION_SELECT = 0;
    const OPTION_INSERT = 1;
    const OPTION_DELETE = 2;
    const OPTION_UPDATE = 3;

    private $link;
    private $result;
    private $bind = false;
    private $option = 0;
    private $dn;
    private $attrs = null;
    private $entry;
    public function connect() {
        $config = &$this->config;
        $link   = &$this->link;
        $host   = (array_key_exists(Config::KEY_STORE_LDAP_HOST,$config))?$config[Config::KEY_STORE_LDAP_HOST]:false;
        $ver    = (array_key_exists(Config::KEY_STORE_LDAP_VERSION,$config))?$config[Config::KEY_STORE_LDAP_VERSION]:false;

        $link   = @ldap_connect($host);
        if($link) {
            if($ver && !@ldap_set_option($link, LDAP_OPT_PROTOCOL_VERSION, $ver)) {
                throw new LdapException("Cannot set ldap option to version:$ver!");
            }
        } else {
            throw new LdapException("Cannot connect to ldap server:$host!");
        }
        return true;
    }
    public function close() {
        $link = &$this->link;
        return @ldap_close($link);
    }
    public function query($qStr, $encode = 'UTF8', $showQStr = false) {
        $config = &$this->config;
        $link   = &$this->link;
        $result = &$this->result;
        $option = &$this->option;
        $dn     = &$this->dn;
        $entry  = &$this->entry;
        $bind   = &$this->bind;

        if(!$link) { return false; }

        $bind   = $this->bind();

        if($showQStr) {
            echo '<pre>qString: '.$qStr."\n".'dn: '.$dn.'</pre>';
        } else if($config[Config::KEY_STORE_LDAP_SHOW_FILTER]) {
            $showQStr = $config[Config::KEY_STORE_LDAP_SHOW_FILTER];
            echo '<pre>qString: '.$qStr."\n".'dn: '.$dn.'</pre>';
        }

        switch($option) {
            case Ldap::OPTION_SELECT:
                if($qStr) {
                    $result = ldap_search($link, $dn, $qStr, $attrs, 1);
                } else {
                    $result = $this->bind($dn, $entry['userPassword']);
                }
                break;
            case Ldap::OPTION_INSERT:
                if($bind) { $result = ldap_add($link, $dn, $entry); }
                break;
            case Ldap::OPTION_DELETE:
                if($bind) { $result = ldap_delete($link, $dn); }
                break;
            case Ldap::OPTION_UPDATE:
                if($bind) { $result = ldap_modify($link, $dn, $entry); }
                break;
        }

        return $result;
    }
    /**
     * Add entries to LDAP directory
     */
    public function insert($target, $fields = '', $values = '') {
        $config = &$this->config;
        $link   = &$this->link;
        $result = &$this->result;
        $bind   = &$this->bind;
        $option = &$this->option;
        $dn     = &$this->dn;

        $option = Ldap::OPTION_INSERT;
        $result = $this->query();
        return $result;
    }
    /**
     * Delete an entry
     */
    public function delete($target, $condition = '') {
        $config = &$this->config;
        $link   = &$this->link;
        $result = &$this->result;
        $bind   = &$this->bind;
        $option = &$this->option;
        $dn     = &$this->dn;

        $option = Ldap::OPTION_DELETE;
        $result = $this->query();
        return $result;
    }
    /**
     * Modify an entry
     */
    public function update($target, $fields = '', $values = '', $condition = '') {
        $config = &$this->config;
        $link   = &$this->link;
        $result = &$this->result;
        $bind   = &$this->bind;
        $option = &$this->option;
        $dn     = &$this->dn;

        $option = Ldap::OPTION_UPDATE;
        $result = $this->query();
        return $result;
    }
    public function select($target, $fields = '', $condition = '', $group = '', $order = '', $limit = '') {
        $config = &$this->config;
        $link   = &$this->link;
        $result = &$this->result;
        $bind   = &$this->bind;
        $option = &$this->option;
        $dn     = &$this->dn;
        $attrs  = &$this->attrs;

        $qStr   = '';
        $dn     = $config[Config::KEY_STORE_LDAP_BASEDN];

        if(is_array($fields)) {
            $attrs = $this->array2Field($fields, $target);
        }
        if(is_array($condition)) {
            $qStr = $this->array2Filter($condition, $target);
        } else if(is_a($condition, 'Condtion')) {
        }

        $option = Ldap::OPTION_SELECT;
        $result = $this->query($qStr);
        return $result;
    }
    public function toArray($count = 0, $result = '') {
    }
    public function toResult() {
    }
    public function toCount($result = '') {
    }
    public function verify() {
    }
    private function bind($userdn = '',$passwd = '') {
        $config = &$this->config;
        $link   = &$this->link;
        if(!$userdn) {
            $userdn = $config[Config::KEY_STORE_LDAP_USERDN];
            $passwd = $config[Config::KEY_STORE_LDAP_PASSWORD];
        }
        return ldap_bind($link, $userdn, $passwd);
    }

    private function getClassByTarget($target) {
        $targets   = &Config::$mapping[Config::MAPPING_KEY_TABLES];
        $className = array_search($target,$targets);
        $className = (is_array($className))?$className[0]:$className;
        return $className;
    }
    private function target2DN($target) {
        $config    = &$this->config;
        $dn        = &$this->dn;
        $className = $this->getClassByTarget($target);
        $mapping   = &Config::$mapping[$className];
        $basedn    = $config[Config::KEY_STORE_LDAP_BASEDN];
        $childname = $config[Config::KWY_STORE_LDAP_CHILDNAME];
        $dn        = $childname.'='.$target.','.$basedn;
    }
    private function array2Filter($values, $target) {
        $filter    = '';
        $count     = 0;
        $className = $this->getClassByTarget($target);
        $mapping   = &Config::$mapping[$className];

        foreach($arr as $k=>$v) {
            if($count == 0) {
                $filter .= '(&';
            }
            if(!is_numeric($k) && array_search($k,$mapping)) {
                $filter .= '('.$k.'='.$v.')';
            } else if(!is_numeric($k) && array_key_exists($k,$mapping)) {
                $key     = $mapping[$k];
                $filter .= '('.$key.'='.$v.')';
                $count++;
            }
            $count++;
            if($count == count($arr)) {
                $filter .= ')';
            }
        }

        return $filter;
    }
    private function array2Field($arr, $target) {
        $attrs     = array();
        $className = $this->getClassByTarget($target);
        $mapping   = &Config::$mapping[$className];

        foreach($arr as $v) {
            if(array_search($v,$mapping)) {
                $attrs[] = $v;
            } else if(array_key_exists($v,$mapping)) {
                $attrs[] = $mapping[$v];
            }
        }

        return $attrs;
    }
    private function array2Entry($fields, $values, $target) {
        $entry     = array();
        $className = $this->getClassByTarget($target);
        $mapping   = &Config::$mapping[$className];

        if($fields && is_array($fields)) {
            foreach($fields as $k) {
                $v = $values[$k];
                if(array_search($k,$mapping)) {
                    $entry[$k] = $v;
                } else if(array_key_exists($k,$mapping)) {
                    $key         = $mapping[$k];
                    $entry[$key] = $v;
                }
            }
        } else {
            foreach($values as $k=>$v) {
                if(!is_numeric($k) && array_search($k,$mapping)) {
                    $entry[$k] = $v;
                } else if(!is_numeric($k) && array_key_exists($k,$mapping)) {
                    $key         = $mapping[$k];
                    $entry[$key] = $v;
                }
            }
        }

        return $entry;
    }
    private function condition2Filter($condition) {
    }
}
?>
