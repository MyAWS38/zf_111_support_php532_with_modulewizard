<?php

class Zfpriv_IndexController extends Zend_Controller_Action
{
    private $module; 
    private $access = array('deny','view','add','modify','delete','admin');
    private $modpriv;
    
    public function init()
    {
        /* Initialize action controller here */
       $this->module = new Zfmodules_Model_Modules();
       $this->guser = new Zfusers_Model_Groupusers();
       $this->modpriv = new Model_Modulpriv();
        
       $message = $this->_helper->_flashMessenger->getMessages();          
       $this->view->message = !empty($message) ? "<blink><font color=\"red\">$message[0]</font></blink><br>" : "";
    }

    public function indexAction()
    {
       // action body
       $this->view->guser = $this->guser->fetchAll();
    }
    
    public function loaderAction()
    {
       $this->getHelper('layout')->disableLayout();
       $this->view->listmodule = $this->module->getListModule();
       $access_type = array();
       foreach($this->view->listmodule as $key=>$row)
       {
            $acl = new Model_Aclmodel($row['module']->module_name);
            $access_type[$key] = $acl->isAccessTypeforRole($row['module']->module_name,$this->_getParam('role_id'));
       }
       
       $this->view->access = $this->access;
       $this->view->role = $this->_getParam('role_id') ;        
       
       $sesi = new Zend_Session_Namespace('zend_prj');
       $this->view->rolesession = $this->modpriv->getRole($sesi->sesiUser);              
              
       $this->view->access_type  = $access_type;
       $this->view->guser = $this->guser->fetchAll();
    }
    
    public function updateprivAction()
    {
        $this->getHelper('layout')->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender(true);
        
        $role_id = $_POST['role_id'];
        $exmod = explode(",",$_POST['listmodules']);
        $expriv = explode(",",$_POST['listaccess']);
        $exmodid = explode(",",$_POST['listmodid']);
        
        
      //  print_r($exmodid);
      //  die;
                            
        foreach($exmod as $key=>$row)
        {
            if (trim($row)!=''){          
                $acl = new Model_Aclmodel($row);
                //check access, module, and role...
                
                $access_type = $acl->isAccessTypeforRole($row,$role_id);
                if ($access_type!=$expriv[$key] && !empty($exmodid[$key])){
                    $this->modpriv->setPriv($expriv[$key], $role_id, $row,$exmodid[$key],$access_type);   
                }                
            }            
        }
        
        $this->_helper->flashMessenger->addMessage('<font color=red>Privilege Updated Successfully</font>');
    }
}