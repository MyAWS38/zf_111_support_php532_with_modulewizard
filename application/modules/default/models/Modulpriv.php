<?php

/**
 * @author     : Abdul Malik Ikhsan<samsonasik@gmail.com>
 * @package    : Default
 * @filesource : modules/default/models/Modulpriv.php
*/

class Model_Modulpriv 
{
    
    private $role;
    private $db;
    
    public function __construct()
    {
        $this->db = Zend_Registry::get('db') ;
    }
    
    public function emptyShowModule($fetch,$sesiUser)
    {
        foreach($fetch as $key=>$row)
        {            
            $acl = new Model_Aclmodel($row['module_name']);
            if ( $acl->getPermission($row['module_name'],$sesiUser) == "denied")
                unset($fetch[$key]);          
        }
	
	return $fetch;
    }
    
    public function getParentModGroups($sesiUser)
    {
        //initialize and get role
        $getgroupmodule  = $this->db->select()
                    ->from('zf_groupmodules')
                    ->join('zf_modules','zf_modules.groupmodule_id = zf_groupmodules.groupmodule_id')
                    ->where("zf_modules.is_active = 1 and module_name != 'default' and zf_groupmodules.groupmodule_name !='NOTHAVEGROUP'")
		    ->order('groupmodule_name','asc')
		    ;		    
	
	$fetchgroupmodule = $this->emptyShowModule($this->db->fetchAll($getgroupmodule),$sesiUser);
	  
        return $fetchgroupmodule;
    }
    
    public function getParentModNoGroups($sesiUser)
    {
        //initialize and get role
        $getgroupmodule  = $this->db->select()
                    ->from('zf_groupmodules')
                    ->join('zf_modules','zf_modules.groupmodule_id = zf_groupmodules.groupmodule_id')
                    ->where("zf_modules.is_active = 1 and module_name != 'default' and zf_groupmodules.groupmodule_name ='NOTHAVEGROUP'");		    
	

	$fetchgroupmodule = $this->emptyShowModule($this->db->fetchAll($getgroupmodule),$sesiUser);
	  
        return $fetchgroupmodule;
    }
    
    public function getChildOne($groupmodule_id, $sesiUser)
    {
        //initialize and get role
        $getgroupmodule  = $this->db->select()
                    ->from('zf_groupmodules')
                    ->join('zf_modules','zf_modules.groupmodule_id = zf_groupmodules.groupmodule_id')
                    ->where("zf_modules.is_active = 1 and module_name != 'default'
                            and zf_groupmodules.groupmodule_id = $groupmodule_id and groupmodule_name !='NOTHAVEGROUP' ")
                    ->order(array('zf_modules.sorted_number ASC'))
            ;
        $fetchgroupmodule = $this->db->fetchAll($getgroupmodule);
        
        foreach($fetchgroupmodule as $key=>$row)
        {
            $acl = new Model_Aclmodel($row['module_name']);
            if ( $acl->getPermission($row['module_name'],$sesiUser) == "denied")
                unset($fetchgroupmodule[$key]);
            
            if ($acl->isAccessType($row['module_name'],$sesiUser)  == 'deny')
                   unset($fetchgroupmodule[$key]);  
        }
        
        return $fetchgroupmodule;
    }
    
    public function getChildTwo($parent_module,$sesiUser)
    {
        $module = $this->db->select()->from('zf_modules')
                            ->where("is_active = 1 and module_name !='default' and parent_module = $parent_module");
        $fetchmodule = $this->db->fetchAll($module);
        
        return $fetchmodule ;        
    }
    
    
    public function getModuleAttr($attr,$module)
    {	
   	$select =  $this->db->select();
	$select->from('zf_modules' ,array($attr))
                 ->where("module_name = '$module'");
               
	$dataattr = $this->db->fetchAll($select);
	
	return (empty($dataattr[0][$attr]) ? "" : $dataattr[0][$attr] )  ;  
    }
    
    private function getPrevEntryAccess($role = 0,$module)
    {
        $and = ($role!=0) ? " and role_id = '$role' " : "";  
        $fetchentry = $this->db->fetchAll( $this->db->select()->from('zf_modules')
                        ->join('zf_moduleshow'," zf_modules.module_id = zf_moduleshow.module_id ")
                        ->where("module_name = '$module' $and and parent_module = 0 "));        
        
        return $fetchentry; 
    }
    
    private function getPrevEntryAccessForEveryOne($module)
    {  
        $fetchentry = $this->db->fetchAll( $this->db->select()->from('zf_modules')
                        ->join('zf_moduleshow'," zf_modules.module_id = zf_moduleshow.module_id ")
                        ->where("module_name = '$module' and parent_module = 0"));        
        
        return $fetchentry; 
    }
    
        
    public function setPriv($access_type,$role,$module,$modid,$access_typebefore)
    {
	//get role name if role name is Everyone
        $guser = new Zfusers_Model_Groupusers();
        $rolecurrent = $guser->find($role)->current();	
	if ($rolecurrent!=null)
	    $rolename = $rolecurrent->role_name;
	        
        $modshow = new Zfmodules_Model_Moduleshow();
        $modshowoverride = new Zfmodules_Model_Moduleshowoverride();
        
        $parent = $this->db->select()
                        ->from("zf_roles", array("role_inherit"))
                        ->where("role_id = $role");
        $parentfetch = $this->db->fetchAll($parent);
        $parentid = $parentfetch[0]['role_inherit'];
                
        if ($rolename=='Everyone'){                        
           if ( count($this->getPrevEntryAccess($role,$module))==0 ){                           
                //check if have duplicate access_type in other record...
                $check = $this->getPrevEntryAccess(0,$module);
                
                //insert ...
                $data = array();
                $data['role_id'] = $role;
                $data['access_type'] = $access_type;
                $data['module_id'] = $modid;                
                $modshow->insert($data);
                                
                if ( count($check)>0){                        
                        $data = array();
                        $data['role_id'] = $check[0]['role_id'];
                        $data['access_type'] = $check[0]['access_type'];
                        $data['moduleshow_id'] = $modshow->lastId();                        
                        $modshowoverride->insert($data);
                        
                        //remove current Id...
                        $current = $modshow->find($check[0]['moduleshow_id'])->current();
                        $current->delete();                    
                }
           } else {                
                //insert or update if ...
                $entry = $this->getPrevEntryAccessForEveryOne($module);
                if (!empty($entry)){
                    foreach($entry as $key=>$row)
                    {
                        $current = $modshow->find($row['moduleshow_id'])->current();                                                
                        $current->access_type = $access_type;
                        $current->save();
                    }
                } else {                    
                    $entry = $this->getPrevEntryAccess($role,$module);
                    $modshowentry = $modshow->find($entry[0]['moduleshow_id'])->current();
                    $modshowentry->access_type = $access_type;
                    $modshowentry->save();                    
                }
           }
        } else {
            if ($access_typebefore=='deny'){                
                //maybe insert, maybe update...
                $find = $modshow->fetchAll(" role_id =  $role and module_id = $modid ")->toArray();
		
                if (!empty($find[0]['module_id'])){ //not have parent structure...                    
                    $currentmod = $modshow->find($find[0]['moduleshow_id'])->current();
                    $currentmod->access_type = $access_type;
                    
                    $currentmod->save();
                }else{
                     $find = $modshow->fetchAll(" module_id = $modid ")->toArray();
                     
                     //if has access...
                     if (!empty($find[0]['module_id'])){
                                                    
                            $modshowid = $find[0]['moduleshow_id'];                                 
                            $access  = $find[0]['access_type'];
                            $roleofthis = $find[0]['role_id'];
                            
                            if ($parentid == 0) { 
                                
                                $find = $modshow->find($modshowid)->current();
                                $find->role_id = $parentid;
                                $find->save();                        
                                
                                //check if record already exist...
                                $checkthis = $this->db->fetchAll (   $this->db->select()->from("zf_moduleshow_override")
                                                                    ->where(" moduleshow_id = $modshowid and role_id = $role "));
                                if (count ($checkthis)==0){
                                    $data = array();
                                    $data['moduleshow_id'] = $modshowid;
                                    $data['role_id'] = $role;
                                    $data['access_type'] = $access_type;
                                    
                                    $modshowoverride->insert($data);
                                } else {
                                    $findthisover = $modshowoverride->find($checkthis[0]['moduleshow_id'])->current();
                                    $findthisover->access_type = $access_type;
                                    $modshowoverride->save();
                                }
                            } else {                                
                                //fill the row with the row of every one...
                                $getrole = $this->db->fetchAll ( $this->db->select()->from("zf_roles")->where("role_name = 'Everyone'"));
                               
                                $findup = $modshow->find($modshowid)->current();
                                $findup->access_type = 'deny';
                                $findup->role_id = $getrole[0]['role_id'];
                                $findup->save();
                                
                                //insert into zf_moduleshow_override...
                                //1. move !!!                                
                                $getroleofthis = $this->db->fetchAll ( $this->db->select()->from("zf_roles")->where("role_id = $roleofthis"));
                                
                                if ($getroleofthis[0]['role_name']!='Everyone'){
                                    $data = array();
                                    $data['moduleshow_id'] = $modshowid;
                                    $data['role_id'] = $roleofthis ;
                                    $data['access_type'] = $access;
                                    $modshowoverride->insert($data);
                                    
                                    //2. insert for current change !!!
                                    $data = array();
                                    $data['moduleshow_id'] = $modshowid;
                                    $data['role_id'] = $role;
                                    $data['access_type'] = $access_type;                                
                                    $modshowoverride->insert($data);                                    
                                } else {
                                    //just change it !!!
                                       //find parent id...
                                        $parent = $this->db->select()
                                                        ->from("zf_roles", array("role_inherit"))
                                                        ->where("role_id = $role")->__toString();
                                                        
                                        //subquery get moduleshow_id
                                        $sub = $this->db->select()->from("zf_moduleshow",array('moduleshow_id'))
                                                        ->where("role_id = ( $parent  ) and module_id = $modid ")->__toString();
                                        
                                       $fetchover = $this->db->fetchAll(
                                                $this->db->select()->from("zf_moduleshow_override")
                                                    ->where("role_id = '$role' and  moduleshow_id = ( $sub )  ")
                                            );                   
                                       
                                        $bottommod = $modshowoverride->find( $fetchover[0]['moduleshow_override_id'] )->current();
                                        if ($bottommod!=null){
                                            $bottommod->access_type = $access_type;
                                            $bottommod->save();
                                        } else {
                                            //find mod for zf_moduleshow for Everyone                        
                                            $fetchzfmodules = $this->db->fetchAll(
                                                $this->db->select()->from("zf_moduleshow")
                                                    ->where("role_id = ($parent)   and   moduleshow_id = ( $sub )  ")                                
                                            );
                                            
                                            $data = array();
                                            $data['moduleshow_id'] = $fetchzfmodules[0]['moduleshow_id'];
                                            $data['role_id'] = $role;
                                            $data['access_type'] = $access_type;
                                            
                                            $modshowoverride->insert($data);  
                                        }
                                    
                                }                                
                            }
                     } else {
                        
                            $data = array();
                            $data['module_id'] = $modid;
                            $data['role_id'] = $role;
                            $data['access_type'] = $access_type;
                            
                            $modshow->insert($data);
                     }
                }
            } else {
		
                //must update...
                //find if in zf_moduleshow...
		//die;
                $find = $modshow->fetchAll(" role_id =  $role and module_id = $modid ")->toArray();
		

                if (!empty($find[0]['moduleshow_id'])){ 
                    $upmod = $modshow->find($find[0]['moduleshow_id'])->current();
                    $upmod->access_type = $access_type;
                    $upmod->save();
                }else{
		    
                    //find parent id...
                    $parent = $this->db->select()
                                    ->from("zf_roles", array("role_inherit"))
                                    ->where("role_id = $role")->__toString();
                    
		//    echo $parent; 
		                    
                    //subquery get moduleshow_id
                    $sub = $this->db->select()->from("zf_moduleshow",array('moduleshow_id'))
                                    ->where("role_id = ( $parent  ) and module_id = $modid")->__toString();
                    
		  //  echo $sub; 
                   $fetchover = $this->db->fetchAll(
                            $this->db->select()->from("zf_moduleshow_override")
                                ->where("role_id = $role and  moduleshow_id = ( $sub )  ")
                        );		   
		 		   
		    if (!empty($fetchover)){
			$bottommod = $modshowoverride->find( $fetchover[0]['moduleshow_override_id'] )
						     ->current();
						     
			
			if ($bottommod!=null){
			    $bottommod->access_type = $access_type;
			    $bottommod->save();
			}
		    }else {
			    //find mod for zf_moduleshow for Everyone                        
			    $fetchzfmodules = $this->db->fetchAll(
				$this->db->select()->from("zf_moduleshow")
				    ->where("role_id = ($parent)   and   moduleshow_id = ( $sub )  ")                                
			    );
			    
			    $data = array();
			    $data['moduleshow_id'] = $fetchzfmodules[0]['moduleshow_id'];
			    $data['role_id'] = $role;
			    $data['access_type'] = $access_type;
			    
			    $modshowoverride->insert($data);                        
			    
			//}
		    }
                }
            }
        }
    }
    
    public function getRole($usr)
    {
       $role = $this->db->fetchAll($this->db->select()
                  ->from("zf_users")
                  ->join("zf_roles","zf_users.role_id = zf_roles.role_id")
		  		 ->where("zf_users.user_name = '$usr'"));
       return $role[0]['role_id'];
    }
}