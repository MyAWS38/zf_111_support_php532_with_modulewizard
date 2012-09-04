<?php

class Zfmodules_Model_GroupModules extends Zend_Db_Table
{
    protected $_primary = "groupmodule_id";
    protected $_name  = "zf_groupmodules";
    protected $_dependentTables = array('Zfmodules_Model_Modules');
       
    public function __construct() 
    {        
        parent::__construct();
    }
    
    public function savethis($post)
    {
        $check = count(
                    $this->_db->fetchAll(
                        $this->_db->select()->from($this->_name)->where("groupmodule_name = '$post[groupmodule_name]'")
                )
            );
        
        if ($check==0){
            $data = array();
            $data['groupmodule_name'] = $post['groupmodule_name'];
            $this->insert($data);
            
            return true;
        }  else {
            
            return false;
        }
    }
    
    public function updatethis($post)
    {
        $check = count(
                    $this->_db->fetchAll(
                        $this->_db->select()->from($this->_name)->where("groupmodule_name = '$post[groupmodule_name]'
                            and groupmodule_id != '$post[groupmodule_id]'                                            
                                ")
                )
            );
        
        if ($check==0){
            
            $current = $this->find($post['groupmodule_id'])->current();
            $current->groupmodule_name = $_POST['groupmodule_name'];
            $current->save();
            
            return true;
        }  else {
            
            return false;
        }
    }
}