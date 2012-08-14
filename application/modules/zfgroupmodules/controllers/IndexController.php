<?php

class Zfgroupmodules_IndexController extends Zend_Controller_Action
{
    private $gmodule;
    
    public function init()
    {
        $this->gmodule = new Zfmodules_Model_GroupModules();
        $this->module = new Zfmodules_Model_Modules();
        
        $message = $this->_helper->_flashMessenger->getMessages();          
        $this->view->message = !empty($message) ? "<blink><font color=\"red\">$message[0]</font></blink><br>" : "";
    }
    
    public function indexAction()
    {
        $this->view->groupmodule = $this->gmodule->fetchAll();
    }
    
    public function updateAction()
    {
        if (strlen(trim($_POST['groupmodule_name']))>0){  
            if ($this->gmodule->updatethis($_POST)){
                $message = "Data updated";
            } else {
             $message = "Group Module already exist";
            }
        }else{
            $message = "Please Fill the blank field";
        } 
       
       $this->_helper->flashMessenger->addMessage($message);
       $this->_helper->redirector->gotoUrl('/zfgroupmodules/index');
    }
    
    public function saveAction()
    {
      if (strlen(trim($_POST['groupmodule_name']))>0){  
        if ($this->gmodule->savethis($_POST)){
           $message = "Data Saved";
        } else {
           $message = "Group Module already exist";
        }
      }else{
        $message = "Please Fill the blank field";
      }
       
       $this->_helper->flashMessenger->addMessage($message);
       $this->_helper->redirector->gotoUrl('/zfgroupmodules/index');
    }
    
    public function removeAction()
    {        
        //fetch module with this param...
        $modules = $this->module->fetchAll(" groupmodule_id = ".$this->_getParam('id'));
        foreach($modules as $key=>$row)
        {           
            $this->module->removemodule(APPLICATION_PATH."/modules/".$row->module_name);
        }
        
        $current = $this->gmodule->find($this->_getParam('id'))->current();
        $current->delete();        
        
        $message = "Data Deleted Successfully";
        
        $this->_helper->flashMessenger->addMessage($message);
        die;
    }
}