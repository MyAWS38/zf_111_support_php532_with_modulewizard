<?php

class Zfmodwizard_IndexController extends Zend_Controller_Action
{
    
    public function init()
    {
        /* Initialize action controller here */
        $this->gmodule = new Zfmodules_Model_GroupModules();
        $this->module = new Zfmodules_Model_Modules();
    }

    public function indexAction()
    {
        // action body  
        $mod = new Zfmodwizard_Model_Modwizardmodel(); 
        $this->view->zfmodwizardinfo = $mod->getVersion();
        
        $this->view->gmodule = $this->gmodule->fetchAll();        
    }
    
    public function generateAction()
    {
        $data = array();
        $data['module_name'] = $this->_getParam('module_name');
        $data['module_title'] = $this->_getParam('module_title');
        $data['developer'] = $this->_getParam('developer');
        $data['groupmodule_id'] =  $this->_getParam('groupmodule_id');
       
        //check in db
        $listmodule = $this->module->getListModule();
        $found = false;
        foreach($listmodule as $key=>$row)
        {
            if ($row['module']->module_name==$data['module_name']){
                $found = true;
                break;
            }
        }        
        
        $msg = "";        
        if ($found){
            //failed
            $msg =  "Module is not available, please try other module name ";
        } else {
            //check is_numeric and space...
            $modname = $data['module_name'];
            $len = strlen($modname);
            $isvalid = true;
            for($i=0;$i<$len;$i++){
                if($modname{$i} === strtoupper($modname{$i}) && !is_numeric($modname{$i})){
                    $msg .= "Module name must in lower case<br>";
                    $isvalid = false;
                    break;
                }
            }
            
            if (is_numeric($modname{0})) {
                $msg .= "First character Must Alphabetic<br>";
                $isvalid = false;
            }
            
            $pos = strpos($modname, " ");
            if ($pos === true){
               $msg .= "Module Name mayn't containt space<br>";
               $isvalid = false;
            }
            
            if ($isvalid){
                //saving...
                $this->module->insert($data);
                
                //generate...
                $this->module->generate($modname);
                
                $msg = "ok";
            }
            
        }
        
        echo $msg; 
        die;
    }
    

}