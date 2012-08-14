<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
    
defined('PUBLIC_PATH')
    || define('PUBLIC_PATH', realpath(dirname(__FILE__)));    

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
    
// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Zfgen_');

$fileconfig = APPLICATION_PATH.'/configs/application.ini';       
     
//needed for query...
$config = new Zend_Config_Ini($fileconfig, APPLICATION_ENV);
$db = Zend_Db::factory($config->resources->db->adapter, $config->resources->db->params);
Zend_Registry::set('db',$db);
    
// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    $fileconfig
);
    
$application->bootstrap()
                ->run();  
