<?php

class Zfmodules_Model_Moduleshowoverride extends Zend_Db_Table
{
    protected $_primary = "moduleshow_override_id";
    protected $_name  = "zf_moduleshow_override";
    protected $_referenceMap    = array(
			'Zfmodules_Model_Moduleshow' => array(
				'columns'           => array('moduleshow_id'),
				'refTableClass'     => 'Zfmodules_Model_Moduleshow',
				'refColumns'        => array('moduleshow_id') , 
				'onDelete'          => self::CASCADE,
				'onUpdate'          => self::CASCADE			
			)
                    );
    
    public function __construct() 
    {
        parent::__construct();
    }    
}