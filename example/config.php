<?php
return array(
        'action' => array(
            'auto-dispatch' => true, 'dispatch-key' => 'key', 'dispatch-scope' => 0, 'dispatch-style' => 'do*'/* 会将*替换为dispatch-key的值,且首字母大写 */, 'result' => 'json'
        ),
        'actions' => array(
            'index' => array(
                'dispatch-key' => 'do', 'classname' => 'IndexAction', 'beans' => 'User,Doc', 'services' => array('UserService', 'DocService'), 'result' => 'json'
            ),
            'test.test' => array(
                'dispatch-key' => 'do', 'classname' => 'TestAction', 'beans' => 'Test', 'services' => array('TestService', 'TestService-mysql'), 'result' => 'json'
            ),
            'test.test.test' => array(
                'dispatch-key' => 'do', 'classname' => 'TestAction', 'beans' => 'Test', 'services' => array('TestService', 'TestService-mysql'), 'result' => 'json'
            ),
            'lay' => array(
                'classname' => 'IndexAction', 'beans' => 'User', 'services' => array('UserService'), 'result' => 'text'
            )
        ),
        'bean' => array(
            'scope' => 0, 'auto-build' => true
        ),
       'beans' => array(
            'Test' => array(
                'classname' => 'Test', 'scope' => 1, 'properties' => array('userid' => 1, 'username' => 'username')
            ),
            'User' => array(
                'classname' => 'User', 'scope' => 1, 'properties' => array('userid' => 1, 'username' => 'username', 'password' => '')
            ),
            'Doc' => array(
                'classname' => 'Doc', 'scope' => 0
            )
        ),
        'service' => array(
            'auto-init' => true
        ),
        'services' => array(
            'TestService-mysql' => array(
                'classname' => 'TestService', 'store' => 'mysql-win'
            ),
            'TestService' => array(
                'classname' => 'TestService', 'store' => 'memory-win'
            ),
            'UserService' => array(
                'classname' => 'UserService', 'store' => 'memory-win'
            ),
            'DocService' => array(
                'classname' => 'DocService', 'store' => 'json-win'
            )
        ),
        'store' => array(
            'encoding' => 'UTF8', 'show-sql' => false, 'auto-connect' => true
        ),
        'stores' => array(
            'mysql' => array(
                'type' => 'database', 'classname' => 'Mysql', 'host' => 'localhost', 'name' => 'root', 'password' => 'dcuxpasswd', 'database' => 'test'
            ),
            'mysql-win' => array(
                'type' => 'database', 'classname' => 'Mysql', 'host' => 'localhost', 'name' => 'root', 'password' => 'dcuxpasswd', 'database' => 'laywork', 'show-sql' => true
            ),
            'memory-win' => array(
                'type' => 'memory', 'classname' => 'Memory', 'host' => 'data/memory', 'dir' => 'test', 'filename' => array('user','doc'), 'prefix' => '', 'suffix' => ''
            ),
            'json-win' => array(
                'type' => 'json', 'classname' => 'Json', 'host' => 'data/json', 'dir' => 'test', 'filename' => array('user', 'doc'), 'prefix' => '', 'suffix' => ''
            ),
            'ldap' => array(
                'type' => 'ldap', 'classname' => 'Ldap', 'version' => '3', 'userdn' => 'cn=admin,dc=ldap,dc=lixin,dc=edu,dc=cn','basedn' => 'dc=ldap,dc=lixin,dc=edu,dc=cn', 'childname' => 'ou'
            )
        ),
        'result' => array(
        ),
        'results' => array(
        )
    );
?>
