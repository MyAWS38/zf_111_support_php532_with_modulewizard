<?php
    class Zfusers_Model_Groupusers extends Zend_Db_Table{
        
        protected $_name = "zf_roles";
        protected $_primary = "role_id";
        protected $_dependentTables = array('Zfmodules_Model_Modules');
        
        public function __construct()
        {
            parent::__construct();
        }
        
        public function savethisrole($post)
        {
            $check = count(
                        $this->_db->fetchAll(
                            $this->_db->select()->from($this->_name)->where("role_name = '$post[role_name]'")
                    )
                );
            if ($check==0){
                
                //get role everyone for role_inherit ;)
                $everyone = $this->_db->fetchAll(
                                $this->_db->select()->from($this->_name)->where("role_name = 'Everyone'"));
                
                $data['role_inherit'] = $everyone[0]['role_id'];
                $data['role_name'] = $post['role_name'];
                $this->insert($data);
                
                return true;
            }  else {
                
                return false;
            }
        }
        
        public function updatethisrole($post)
        {
            $check = count(
                        $this->_db->fetchAll(
                            $this->_db->select()->from($this->_name)->where("role_name = '$post[role_name]'
                                and role_id != '$post[role_id]'                                            
                                    ")
                    )
                );
            
            if ($check==0){            
                $current = $this->find($post['role_id'])->current();
                            
                $current->role_name = $post['role_name'];     
               
                $current->save();
    
                return true;
            }  else {
                
                return false;
            }
        }
    }