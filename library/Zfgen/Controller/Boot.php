<?php
/** 
 * @package Zfgen
 */
class Zfgen_Controller_Boot extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request) 
    {
        // Get the view instance
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        if (null === $viewRenderer->view) {
            $viewRenderer->initView();
        }
        $view = $viewRenderer->view;
                  
        $view->assign('req', $request);
        $view->assign('sesi',new Zend_Session_Namespace('zend_prj'));
	$view->assign('modpriv', new Model_Modulpriv());
	$view->assign('modules', new Zfmodules_Model_Modules());	
    }   

    /* after routing, before posted */
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {	
    	$frontController = Zend_Controller_Front::getInstance();
        $bootstrap =  $frontController->getParam('bootstrap');
        $activeModuleName = $request->getModuleName();
        $moduleList = $bootstrap->modules;

        $moduleInitName = strtolower($activeModuleName)."Init";
        $moduleInitNameLength = strlen($moduleInitName);

        if (isset($moduleList[$activeModuleName])) {
            $activeModule = $moduleList[$activeModuleName];

            $bootstrapMethodNames = get_class_methods($bootstrap);
            foreach ($bootstrapMethodNames as $key=>$method) {
                $runMethod = false;
                $methodNameLength = strlen($method);
                if ($moduleInitNameLength < $methodNameLength &&
                    $moduleInitName == substr($method, 0, $moduleInitNameLength)) {
                    call_user_func(array($bootstrap,$method));
                }
            }
        } else {
            $activeModule = $bootstrap;
        }

        $methodNames = get_class_methods($activeModule);
        foreach ($methodNames as $key=>$method) {
            $runMethod = false;
            $methodNameLength = strlen($method);
            
            if (10 < $methodNameLength && 'activeInit' === substr($method, 0, 10)) {            	
                $runMethod = true;
            } elseif ($moduleInitNameLength < $methodNameLength &&
                    $moduleInitName == substr($method, 0, $moduleInitNameLength)) {
                $runMethod = true;
            }
            if ($runMethod) {
                call_user_func(array($activeModule,$method));
            }
        }
    }
}