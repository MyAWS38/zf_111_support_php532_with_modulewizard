<?php

class AuthController extends Zend_Controller_Action
{
    
    private $sesi,$auth;
    
    public function init()
    {
        /* Initialize action controller here */
        $this->auth = new Model_Auth();
        $this->sesi = new Zend_Session_Namespace('zend_prj');
        $this->db = Zend_Registry::get('db');
    }

    public function indexAction()
    {
	$message = $this->_helper->_flashMessenger->getMessages();          
        $this->view->message = !empty($message) ? "<blink><font color=\"red\">$message[0]</font></blink><br>" : "";
	
        if ($this->sesi->sesiUser!=null)	    
		$this->_helper->redirector->gotoUrl('/index');
    }

    
    public function authenticateAction()
    {
	   // action body
	if ($this->sesi->sesiGroupUser!=null)	    
		$this->_helper->redirector->gotoUrl('/index');   
	   
        $username = $this->_getParam('username');
        $password = $this->_getParam('password');
        $authenticated = null;
        
        if ($username && $password) {          
            $authAdapter = new Zend_Auth_Adapter_DbTable($this->db);
            $authAdapter->setTableName('zf_users')
                ->setIdentityColumn('user_name')
                ->setCredentialColumn('passwd');
            
            $authAdapter
                ->setIdentity($username)
                ->setCredential(md5($password));                
            $authenticated = $authAdapter->authenticate();        
            $message = $authenticated->getMessages();
            
            if ($message[0]=="Authentication successful.") {                               
		$act = $this->auth->getActivated($username,$password);
		$act = $act[0]['is_active'];
		
		if ($act==1) {
		
                        //cek apakah user sedang login...
                            
                            //sesi user ...
			    $this->sesi->sesiUser = $username;			    
			    $sesiuser = $this->sesi->sesiUser;                            
                           
                            $finderId = $this->auth->getId($sesiuser);
			    
			    //sesi userid
			    $this->sesi->sesiId = $finderId[0]['user_id'] ;
			    
			  
                            $this->_helper->redirector->gotoUrl('/index');
                            
		
                } else {
		    $message[0] = "User ID is not activated";
		}
	    } 

           
            
        } else {
            $ispost = $this->_request->isPost('username') ;
            if (!empty($ispost))    
                $message[0] = "User ID and password must be entry";        
        }
	
	$this->_helper->flashMessenger->addMessage($message[0]);
	$this->_helper->redirector->gotoUrl('/auth/');
	
    }
    
   public function logoutAction()
    { 
        $this->sesi->sesiUser = null;
	$this->_helper->flashMessenger->addMessage("You have sucessfully logout");
        $this->_helper->redirector->gotoUrl('/auth');
    }
    
}

