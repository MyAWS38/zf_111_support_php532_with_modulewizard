<?php

class Zfmodules_Model_Modules extends Zend_Db_Table 
{
    protected $_primary = "module_id";
    protected $_name  = "zf_modules";
    protected $_dependentTables = array('Zfmodules_Model_Moduleshow');
    protected $_referenceMap    = array(
			'Zfmodules_Model_GroupModules' => array(
				'columns'           => array('groupmodule_id'),
				'refTableClass'     => 'Zfmodules_Model_GroupModules',
				'refColumns'        => array('groupmodule_id') , 
				'onDelete'          => self::CASCADE,
				'onUpdate'          => self::CASCADE			
			)
                );    
    private $groupmodules ;    
    
    public function __construct() 
    {
        $this->groupmodules = new Zfmodules_Model_GroupModules();
	$this->moddir = APPLICATION_PATH."/modules";
	$this->bootstraptemplate = new Zfmodules_Model_Bootstraptemplate($this->moddir);
	
        parent::__construct();
    }
    
    public function getListModule()
    {
	
	
        //get not have group ...
	$getgroup =  $this->_db->fetchAll(
			 $this->_db->select()
				->from('zf_groupmodules')->where("groupmodule_name = 'NOTHAVEGROUP'")
			);
	
        $listadddir = $this->listmoduleaddedDirectory();
        
	foreach($listadddir as $key=>$row)
	{
	    //check if exist in db
	    $check = count($this->_db->fetchAll(
			    $this->_db->select()->from($this->_name)
			    ->where("module_name = '$row'")));
	    if ($check==0){
		//insert into db...
		$data = array('module_name' => $row,
				'module_title' => '-',
				'groupmodule_id' =>$getgroup[0]['groupmodule_id'],
				'is_active' => 0
			);						
		$this->insert($data);
	    }
	}
	
	
	
	$select = $this->select();
        $select->from($this->_name)
                ->where("parent_module = 0 and module_name !='default'")
		->order(array('groupmodule_id ASC')) ;
        
        $data = $this->fetchAll($select);
        
	
	        
        $datatemp = array();        
        foreach($data as $key=>$row)
        {
                if (!in_array($row->module_name,$listadddir)){
                    $current = $this->find($row->module_id)->current();
		    if ($current!=null)
			$current->delete();                    
                } else {
                    $datatemp[] = $row;
                }
        }
	
	
	
        //replace ...
        $data = $datatemp ;
        
        $groupandmodules = array(); 
	foreach($data as $key=>$row)
        {
                $groupandmodules[$key]['groupmodule'] = $this->groupmodules
							    ->find($row->groupmodule_id);
                $groupandmodules[$key]['module'] = $row;
                
                $modname = $row->module_name;
                //cek validity...
                $groupandmodules[$key]['validitymoduleOninstall'] = 1;
                $groupandmodules[$key]['validitymodule'] = 1;
                
                $len = strlen($modname);
                for($i=0;$i<$len;$i++){
                    if($modname{$i} === strtoupper($modname{$i}) && !is_numeric($modname{$i})){
                        
                        $groupandmodules[$key]['validitymodule'] = 0;
                        break;
                    }
                }
		
                
                if (is_numeric($modname{0})) {
                    $groupandmodules[$key]['validitymoduleOninstall'] = 0;
                    $groupandmodules[$key]['validitymodule'] = 0;
                }
                
                //cek have space...
                $pos = strpos($modname, " ");
                if ($pos === true){
                    $groupandmodules[$key]['validitymodule'] = 0;
                    $groupandmodules[$key]['validitymoduleOninstall'] = 0;
                }                
               
                $this->bootstraptemplate->setDir($row->module_name);
                
                $filecontroller = array();
                if ($handle = opendir($this->moddir."/".$row->module_name."/controllers")) {
                    while (false !== ($file = readdir($handle))) {
                        if ($file!="." && $file!=".."){
                            //cut php and controller ...
                            $explodesub  = explode("Controller.php",$file);
                            if (count($explodesub)>1 && $explodesub[0]!='Index'){
                                //insert to db with check before...
                                $this->checkInsertSubModule($row->module_id,$explodesub[0]);
                                $filecontroller[] = strtolower($explodesub[0]);  
                            }                                                          
                        }
                    }
                }
		
		
                
                //check exist in db but not exist in hard drive...
                $sub = $this->getSubModule($row->module_id);
                $submod = array();
                foreach($sub as $key=>$row)
                {
                    if (!in_array($row['module_name'],$filecontroller)){
                        $current = $this->find($row['module_id'])->current();
			if ($current!=null)
			    $current->delete();
                    } 
                }		
        }
	
	
	
        return $groupandmodules;
    }
    
    private function checkInsertSubModule($module_parentid,$sub)
    {
	//chek...
	$sub = strtolower($sub);
	
	$countcheck = count($this->fetchAll("parent_module = $module_parentid and module_name = '$sub'"));
		
	if ($countcheck==0){
	    //insert ...
	    //get groupmodule id
	    
	    $select = $this->select();
	    $select->from($this->_name)
                ->where("module_id = $module_parentid ");
        
	    $modparent = $this->fetchAll($select);
	    
	    $data = array('module_name'=>strtolower($sub),
			  'parent_module'=>$module_parentid,
			  'module_title'=>ucfirst($sub),
			  'groupmodule_id' => $modparent[0]['groupmodule_id'],
			  'is_active'=>1);
	    	    
	    $this->insert($data);
	}
    }
    
    //not generated module ...
    private function listmoduleaddedDirectory()
    {	
	$data = array();
	if ($handle = opendir(APPLICATION_PATH."/modules")) {
	    while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {
		   if (is_dir(APPLICATION_PATH."/modules/".$file))     
			$data[] = $file;    		
		}
	    }
	    closedir($handle);
	}	
	return $data; 	
    }
    
    public function getSubModule($module_id)
    {
	$select = $this->_db->select()
		    ->from($this->_name)
		    ->where("parent_module = $module_id")
		    ->order(array("sorted_number asc"));
		    
	//echo $select->__toString(); //die;
		    
	$fetchsub  = $this->_db->fetchAll($select);
	return $fetchsub; 
    }
    
    public function removemodule($directory, $empty=FALSE)
    {
	// if the path has a slash at the end we remove it here
	if(substr($directory,-1) == '/'){
	    $directory = substr($directory,0,-1);
	}
  
	// if the path is not valid or is not a directory ...
	if(!file_exists($directory) || !is_dir($directory))
	{
	    // ... we return false and exit the function
	    return FALSE;
  
	    // ... if the path is not readable
	} elseif(!is_readable($directory)) {
         // ... we return false and exit the function
         return FALSE;
  
        // ... else if the path is readable
	}else{  
         // we open the directory
         $handle = opendir($directory);
  
         // and scan through the items inside
         while (FALSE !== ($item = readdir($handle)))
         {
             // if the filepointer is not the current directory
             // or the parent directory
             if($item != '.' && $item != '..')
             {
                 // we build the new path to delete
                 $path = $directory.'/'.$item;
  
                 // if the new path is a directory
                 if(is_dir($path)) 
                 {
                     // we call this function with the new path
                     $this->removemodule($path);
  
                 // if the new path is a file
                 }else{
                     // we remove the file
                     unlink($path);
                 }
             }
         }
         // close the directory
         closedir($handle);
  
         // if the option to empty is not set to true
         if($empty == FALSE)
         {
             // try to delete the now empty directory
             if(!rmdir($directory))
             {
                 // return false if not possible
                 return FALSE;
             }
         }
         // return success
         return TRUE;
     }
    }
    
    public function removemodindb($module_name)
    {
	$select = $this->_db->select()
			->from($this->_name)
			->where("module_name = '$module_name' and parent_module = 0");
	$getmodule = $this->_db->fetchAll($select);
	
        $modid = $getmodule[0]['module_id'];
        
	if (!empty($getmodule)){		
	    //delete child module :P
            $child = $this->_db->fetchAll( $this->_db->select()->from($this->_name)->where("parent_module = '$modid'"));
            foreach($child as $key=>$row)
            {
                $findchild = $this->find($row['module_id'])->current();
                if ($findchild!=null)
                    $findchild->delete();
            }
            
            $current = $this->find($getmodule[0]['module_id'])->current();
	    if ($current!=null)
		$current->delete();
        }
    }
    
    private function releaseControllAndView($module)
    {
         if (!is_file($this->moddir."/".$module."/controllers/IndexController.php")){
            $filename = APPLICATION_PATH."/modules/zfmodules/views/helpers/controller.tmpl";
            $handle = fopen($filename, "r");
            $contents = str_replace("Modz",ucfirst($module), str_replace("&lt;","<", fread($handle, filesize($filename))));
            
            //write file controller..
            $fp = fopen($this->moddir."/".$module."/controllers/IndexController.php", 'w+');
            fwrite($fp, $contents);
         }
         
         if (!is_file($this->moddir."/".$module."/views/scripts/index/index.phtml")){
            @mkdir($this->moddir."/".$module."/views/scripts/index");
            $filename = APPLICATION_PATH."/modules/zfmodules/views/helpers/view.tmpl";
            $handle = fopen($filename, "r");
            $contents = str_replace("Modz",ucfirst($module), str_replace("&lt;","<", fread($handle, filesize($filename))));
                        
            $contents = $contents." <br> Access Type is <?=Zend_Registry::get('access_type'); ?>";
            
            //write file index view..
            $fp = fopen($this->moddir."/".$module."/views/scripts/index/index.phtml", 'w+');
            fwrite($fp, $contents);
         }         
    }
        
    public function install($id)
    {
	$info = "";
	$find = $this->find($id)->current();
        $this->releaseControllAndView($find->module_name);
	$info = ($find->is_active==1) ? "Uninstalled" : "Installed";
	$find->is_active  = ($find->is_active==1) ? 0 : 1 ;
	$find->save();
	
	return $info;
    }
    
    public function installtovalid($id)
    {
        $info = "";
        $find = $this->find($id)->current();
        $mod = $find->module_name;
        if (strpos($mod," ")===false){  
            $lower = strtolower($mod);
            
            $find->module_name = $lower;
            $find->is_active = 1; 
            //rename ...
            rename(APPLICATION_PATH."/modules/".$mod,APPLICATION_PATH."/modules/".$lower);
            
            $info = "Installed";
            $find->save();
        } else { 
                $find->delete();                
                $this->removemodule(APPLICATION_PATH."/modules/".$mod);

                $info = "Deleted";
        }
        
        
        return $info; 
    }
    
    public function generate($module)
    {
        $this->bootstraptemplate->setDir($module);
        $this->releaseControllAndView($module);
    }
}