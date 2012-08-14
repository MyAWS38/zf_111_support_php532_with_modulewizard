<?php
/**
 * Auth Models
 *
 * @copyright   by Abdul Malik Ikhsan
 * @license     GPL (http://www.gnu.org/licenses/gpl.html)
 * @author      Abdul Malik Ikhsan
 * @link        http://samsonasik.wordpress.com
 *
 * @package     Models
 */

class Model_Auth
{
    
    public function __construct() 
    {
        $this->db = Zend_Registry::get('db') ;
	$this->_name = 'zf_users';
    }
    
    public function getActivated($username,$password)
    {
        $act =  $this->db->select()
                    ->from($this->_name,array("is_active"))
                    ->where("user_name = '$username' and passwd = md5('$password')");
        return $this->db->fetchAll($act);             
    }
    
    public function getId($sesiuser)
    {
        $getid =   $this->db->select()
                      ->from($this->_name)
                      ->where("user_name = '$sesiuser'");
        return $this->db->fetchAll($getid); 
    }
    
    private function getroleId($username)
    {
        $gid =  $this->db->select()
                    ->from($this->_name,array('role_id'))
                    ->where("user_name = '$username'");
        return $this->db->fetchAll($gid);
    }
    
    public function getrole($username)
    {
        $gid = $this->getroleId($username);
        $gid = $gid[0]['role_id'] ;
        $fetchrole =  $this->db->select()
                    ->from("zf_roles")
                    ->where("role_id = '$gid'");
        return $this->db->fetchAll($fetchrole);
    } 
    
    
}