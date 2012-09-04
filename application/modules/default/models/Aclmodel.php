<?php
//ADOBTED & CUSTOMIZED by Abdul Malik Ikhsan
//from http://my.opera.com/zomg/blog/2007/05/08/zend-acl-and-storing-roles-and-resources-in-a-db

class Model_Aclmodel extends Zend_Acl
{    
    private $db;    
    
    public function Model_Aclmodel($module_name)
    {
        $this->db = Zend_Registry::get('db') ;
        
        $selectmodules = $this->db->select()
                            ->from('zf_modules')
                            ->joinLeft('zf_moduleshow', 'zf_moduleshow.module_id = zf_modules.module_id')
                            ->where('parent_module = 0');
                            
        $modules  = $this->db->fetchAll($selectmodules);        
                
        $selectroles = $this->db->select()
                        ->from('zf_roles')
                        ->order(array('role_inherit ASC'));                        
        $roles = $this->db->fetchAll($selectroles);
                     
        //mengurutkan ... 
        $roletemp = array();
        foreach($roles as $key=>$r)
        {
            if ($key>0){
                $roletemp[$key] = $r;                
            }        
        }
        
        //<<include>> <-- in class diagram
        $sorter = new Zfgen_Sorter();
        $rolesorted = $sorter->sortingAssoc($roletemp, $sortby = "role_id",$sortir="asc");

        foreach($roles as $key=>$r)
        {
            if ($key>0){
                $roles[$key] = $rolesorted[$key-1]; 
            }
        }
        
        $roleArray = array();
        foreach($roles as $key=>$r)
        {
            $role = new Zend_Acl_Role($r['role_id']);
            if ($r['role_inherit']==0)
                $this->addRole($role);
            else
                $this->addRole($role, $r['role_inherit']);
                
            $roleArray[$r['role_id']] = $role;
        }
                
        $modules = $sorter->sortingAssoc($modules, $sortby = "module_name",$sortir="asc");
        
        $res  = "";
        foreach($modules as $key=>$m)
        {
            $resource = new Zend_Acl_Resource($m['module_name']);
            if (!empty($roleArray[$m['role_id']]))
                $role = $roleArray[$m['role_id']];           
            
            if ($res!=$resource && $resource!='')
                $this->add($resource);            
            $res = $resource;
            if ($resource!=''){
                if ($module_name == $m['module_name'] && null!=$m['role_id'] && 1==$m['is_active'])
                    $this->allow($role,$resource);
                else
                    $this->deny($role,$resource);
            }
        }        
    }
    
    public function getRoleId($sesiUser)
    {
        $selectroles = $this->db->select()
                ->from('zf_roles')
                ->join('zf_users','zf_roles.role_id  = zf_users.role_id')
                ->where("zf_users.user_name = '$sesiUser'");
                
        $role = $this->db->fetchAll($selectroles); 
        $roleId = (empty($role)) ? 0 : $role[0]['role_id'];
        
        if ($roleId ==0){
            $selectroles = $this->db->select()
                ->from('zf_roles')
                ->where("role_name = 'Everyone'");
            $role = $this->db->fetchAll($selectroles);
            $roleId = $role[0]['role_id'];            
        }
        
        return $roleId; 
    }
    
    
    public function getPermission($module_name,$sesiUser)
    {  
        return $this->isAllowed($this->getRoleId($sesiUser), $module_name) ? 'allowed' : 'denied';    
    }
    
    public function getOverride($module_name,$sesiUser)
    {         
        $role = $this->getRoleId($sesiUser);
        return $this->getAccessOverride($role,$module_name);
    }
    
    public function findParentPermission($module_name, $sesiUser)
    {
        $roleId = $this->getRoleId($sesiUser);
        $access_type = $this->getParent($roleId,$module_name);
        
        return $access_type;
    }
    
    public function getParent($roleId,$module)
    {
            $findParent =  $this->db->fetchAll(
                            $this->db->select()
                            ->from('zf_roles')
                            ->where("role_id = $roleId")
                            );
                
            $getAcccessType = "";
            $selectAccessType = "";
        
            $roleParent = $findParent[0]['role_inherit'];
            
            $selectmodId = $this->db->select()->from("zf_modules", array('module_id'))
                                    ->where("module_name = '$module' and parent_module = 0")->__toString();
        
            if ($roleParent!=0){            
                $selectAccessType = $this->db->select()
                                        ->from('zf_moduleshow')
                                        ->where("role_id = $roleParent and module_id = ($selectmodId)");
                                        
                $getAcccessType = $this->db->fetchAll($selectAccessType);
                if (empty($getAcccessType)){ 
                    $selectAccessType = $this->db->select()
                                            ->from('zf_moduleshow')
                                            ->where("role_id = $roleId and module_id = ($selectmodId)");
                    $getAcccessType = $this->db->fetchAll($selectAccessType); 
                }
            } else {
                $selectAccessType = $this->db->select()
                                      ->from('zf_moduleshow')
                                      ->where("role_id = $roleId and module_id = ($selectmodId)");
                $getAcccessType = $this->db->fetchAll($selectAccessType);    
            }        
            $access_type = $getAcccessType[0]['access_type'];
            
            return $access_type;
    }
    
    private function getAccessOverride($role,$module_name)
    {
        $selectoverride = $this->db->select()
             ->from(array('mo' => 'zf_moduleshow_override'),
                    array('moduleshow_id', 'access_type'))
             ->join(array('sh' => 'zf_moduleshow'),
                    'mo.moduleshow_id= sh.moduleshow_id', array())
             ->joinLeft('zf_modules','zf_modules.module_id = sh.module_id')
                        ->where("mo.role_id = $role and zf_modules.module_name = '$module_name' and zf_modules.parent_module = 0");                
                
        $getOverride = $this->db->fetchAll($selectoverride);
        $return = 0;
        if (!empty($getOverride)){
            $return = $getOverride;
        }
        
        return $return;     
    }
    
    public function isAccessType($module,$user)
    {
        $access_type = 'deny';
        if ( $this->getPermission($module,$user)=="allowed" ){ 
            //cek have override or not ...
             if (  $this->getOverride($module,$user) ==0){
                //don't have override...
                $override =   $this->findParentPermission($module,$user);
                $access_type = $override;
             } else {                
                $override =   $this->getOverride($module,$user) ;
                $access_type = $override[0]['access_type'];
             }   
        }
        return $access_type;
    }
    
    public function isAccessTypeforRole($module,$roleId)
    {
        $access_type = 'deny';
        $act = $this->isAllowed($roleId, $module) ? "allowed" : "deny";
        if ($act=="allowed"){
            //check have override or not...
             if (  $this->getAccessOverride($roleId,$module) ==0){
                //don't have override...
                $override =   $this->getParent($roleId,$module);
                $access_type = $override;
             } else {                
                $override =   $this->getAccessOverride($roleId,$module) ;
                $access_type = $override[0]['access_type'];
             }   
        }
        
        return $access_type;
    }
    
    
    public function getRoleOfAccess($type)
    {
        
    }
    
}
