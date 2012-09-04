<?php
class Zfusers_IndexController extends Zend_Controller_Action
{
    public function init()
    {
       $this->guser = new Zfusers_Model_Groupusers();
       $this->user = new Zfusers_Model_Users();
       $this->modpriv = new Model_Modulpriv();
        
       $message = $this->_helper->_flashMessenger->getMessages();          
       $this->view->message = !empty($message) ? "<blink><font color=\"red\">$message[0]</font></blink><br>" : "";
       
       $this->view->sesi = new Zend_Session_Namespace('zend_prj');
    }
    
    public function indexAction()
    {
       $this->view->guser = $this->guser->fetchAll("role_name !='Everyone'");
    }
    
    public function guserAction()
    {
        $sesi = new Zend_Session_Namespace('zend_prj');
        $this->view->role = $this->modpriv->getRole($sesi->sesiUser);
        $this->view->guser = $this->guser->fetchAll("role_name !='Everyone'");
    }
    
    public function saveroleAction()
    {
        if (strlen(trim($_POST['role_name']))>0){
	    
	    if ($this->guser->savethisrole($_POST)){		
		
	       $message = "Data Saved";
	    } else {
	       $message = "User already exist";
	    }
      }else{
        $message = "Please Fill the blank field";
      }
      
      $this->_helper->flashMessenger->addMessage($message);
      $this->_helper->redirector->gotoUrl('/zfusers/index/guser');
    }
    
    public function updateroleAction()
    {
        if (strlen(trim($_POST['role_name']))>0){  
        if ($this->guser->updatethisrole($_POST)){
           $message = "Data Updated";
        } else {
           $message = "User already exist";
        }
      }else{
        $message = "Please Fill the blank field";
      }
        $this->_helper->flashMessenger->addMessage($message);
       $this->_helper->redirector->gotoUrl('/zfusers/index/guser');
    }
    
    public function removeroleAction()
    {        
        $current = $this->guser->find($this->_getParam('id'))->current();
        $current->delete();        
        
        $message = "Data Deleted Successfully";
        
        $this->_helper->flashMessenger->addMessage($message);
        die;    
    }
    
    public function loaderAction()
    {
        $this->getHelper('layout')->disableLayout();
        $role=$this->_getParam('role_id');
        
        $this->view->role = $role;
        
        $viewusers = Zend_Paginator::factory($this->user->fetchAll( " role_id = '$role'" ));
	$viewusers->setCurrentPageNumber($this->_getParam('page'));
	$viewusers->setItemCountPerPage(8); 
	$this->view->viewusers = $viewusers;        
    }
    
    public function updateAction()
    {
        if (strlen(trim($_POST['user_name']))>0){  
            if ($this->user->updatethis($_POST)){
                $message = "Data updated";
            } else {
             $message = "User already exist";
            }
        }else{
            $message = "Please Fill the blank field";
        } 
       
       $this->_helper->flashMessenger->addMessage($message);
       die;
    }
    
    public function saveAction()
    {
        
      if (strlen(trim($_POST['user_name']))>0){  
        if ($this->user->savethis($_POST)){
           $message = "Data Saved";
        } else {
           $message = "User already exist";
        }
      }else{
        $message = "Please Fill the blank field";
      }
       
       $this->_helper->flashMessenger->addMessage($message);
       die;
    }
    
    public function removeAction()
    {        
        $current = $this->user->find($this->_getParam('id'))->current();
        $current->delete();        
        
        $message = "Data Deleted Successfully";
        
        $this->_helper->flashMessenger->addMessage($message);
        die;
    }
}