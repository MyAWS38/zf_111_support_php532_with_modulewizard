<?php
    class Zfusers_Model_Users extends Zend_Db_Table
    {        
        protected $_name = "zf_users";
        protected $_primary = "user_id";
        
        public function __construct()
        {
            parent::__construct();
        }
        
        public function savethis($post)
        {
           $check = count(
                        $this->_db->fetchAll(
                            $this->_db->select()->from($this->_name)->where("user_name = '$post[user_name]'")
                    )
                );
            if ($check==0){            
            
                $data['user_name'] = $post['user_name'];
                $data['passwd'] = (!empty($post['passwd'])) ? md5($post['passwd']) :  $data['user_name'] ;
                $data['information'] = $post['information'];
                $data['is_active'] = $post['is_active'];            
                $data['role_id'] = $post['role_id'];
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
                            $this->_db->select()->from($this->_name)->where("user_name = '$post[user_name]'
                                and user_id != '$post[user_id]'                                            
                                    ")
                    )
                );
            
            if ($check==0){            
                $current = $this->find($post['user_id'])->current();
                            
                $current->user_name = $post['user_name'];
                $current->passwd = (!empty($post['passwd'])) ? md5($post['passwd']) :  $current->passwd ;
                $current->information = $post['information'];
                $current->is_active = $post['is_active'];            
               
                $current->save();
    
                return true;
            }  else {
                
                return false;
            }
        }
        
    }