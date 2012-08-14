<?php

class Model_TableManipulator
{
    public function __construct()
    {
        $this->db = Zend_Registry::get('db');
    }
    
    public function NextId($tablename)
    {
        $lastId = $this->db
                    ->query("SHOW TABLE STATUS WHERE Name = '$tablename'")
                    ->fetchAll();
        $lastId = $lastId[0]['Auto_increment'];
        
        return $lastId;        
    }
}