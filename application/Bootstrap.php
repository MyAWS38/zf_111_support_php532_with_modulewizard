<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {        
        $autoloader = new Zend_Application_Module_Autoloader(
                        array(
                            'namespace' => '',
                            'basePath'  =>APPLICATION_PATH.'/modules/default' 
                        )
                );
                
        return $autoloader;
    }
    
    public function _initModule()
    {
    	$this->frontController = Zend_Controller_Front::getInstance();
    	$this->router = $this->frontController->getRouter();
    	
        $this->frontController->registerPlugin(new Zfgen_Controller_Boot()); 
    }
       
    protected function _initView()
    {               
        $view = new Zend_View();
        $sesi = new Zend_Session_Namespace('zend_prj');
        
        $view->headTitle('Zend Project');
        
        if (!Zend_Registry::isRegistered('theme'))
            Zend_Registry::set('theme','zend_prj');
        
        $theme = Zend_Registry::get('theme');
        
        Zend_Layout::startMvc(array(
                'layoutPath' => PUBLIC_PATH."/themes/$theme/layout/scripts",
                'layout' => 'layout'
        ));
                
                        
        return $view;
    }
}