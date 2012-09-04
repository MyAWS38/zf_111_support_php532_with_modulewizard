<?php
/**
 * Bootstrap for Default Module
 *
 * @copyright   by Abdul Malik Ikhsan
 * @license     GPL (http://www.gnu.org/licenses/gpl.html)
 * @author      Abdul Malik Ikhsan
 * @link        http://samsonasik.wordpress.com
 *
 * @package     modules/default
 */
class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{
   
    protected function _initView()
    {                       
        $router = new Zend_Controller_Router_Rewrite();
        $request =  new Zend_Controller_Request_Http();
        $router->route($request);
        
        $module = $request->getModuleName();
        
        //get Access Control List
        $sesi = new Zend_Session_Namespace('zend_prj');               
        $acl = new Model_Aclmodel($module);        
        
        //$sesi->sesiUser        
        Zend_Registry::set('access_type',$acl->isAccessType($module,$sesi->sesiUser));
                
        if (Zend_Registry::get('access_type')=="deny"){
            echo "Access denied or module is not already installed";
            die;
        }       
		
	Zend_Registry::set('browser', ( strpos($_SERVER['HTTP_USER_AGENT'],"MSIE") === false ) ? "other" : "MSIE" ) ;
	
        $controllerName = $request->getControllerName();
        
        Zend_Registry::set('module',$module);
        Zend_Registry::set('contname',$controllerName);
                
        $mod = new Model_Modulpriv();                                
        $moduleinfo = ($module=='default') ? 'Welcome' :  $mod->getModuleAttr('module_title',$module)  ;
        $controller = ($controllerName=='index') ? '' : ' : '.ucfirst($controllerName);
        
        $view = new Zend_View();
        $view->headTitle(' : '.$moduleinfo.$controller);
        
        return $view;
    }
    
}