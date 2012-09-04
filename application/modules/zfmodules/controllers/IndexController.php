<?php

class Zfmodules_IndexController extends Zend_Controller_Action
{    
    private $module; 
    
    public function init()
    {
        /* Initialize action controller here */
        $this->module = new Zfmodules_Model_Modules();
        
       $message = $this->_helper->_flashMessenger->getMessages();          
       $this->view->message = !empty($message) ? "<blink><font color=\"red\">$message[0]</font></blink><br>" : "";
    }

    public function indexAction()
    {
       // action body
       $this->view->listmodule = $this->module->getListModule();
       $sub = array();
       foreach($this->view->listmodule as $key=>$row)
       {
            $sub[] = $this->module->getSubModule($row['module']->module_id); 
       }
       
       echo "access type is : ".Zend_Registry::get('access_type'); 
       
       $this->view->submodule  = $sub;
    }
    
    public function removemoduleAction()
    {
        //removing file...                
        $this->module->removemodule(  APPLICATION_PATH."/modules/".$this->_getParam('module_name') );
        $this->module->removemodindb($this->_getParam('module_name'));
        $this->_helper->flashMessenger->addMessage("Module Deleted");
        $this->_helper->redirector->gotoUrl('/zfmodules/index');
    }
    
    public function installmodAction()
    {
        $msginfo = $this->module->install($this->_getParam('id'));        
        $this->_helper->flashMessenger->addMessage("Module sucessfully $msginfo");
        die;
    }
    
    public function installmodtovalidAction()
    {
        $msginfo = $this->module->installtovalid($this->_getParam('id'));        
        $this->_helper->flashMessenger->addMessage("Module sucessfully $msginfo");
        die; 
    }
}

