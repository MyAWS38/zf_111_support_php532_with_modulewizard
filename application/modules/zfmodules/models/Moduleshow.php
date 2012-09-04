<?php

class Zfmodules_Model_Moduleshow extends Zend_Db_Table
{
    protected $_primary = "moduleshow_id";
    protected $_name  = "zf_moduleshow";
    protected $_dependentTables = array('Zfmodules_Model_Moduleshowoverride');
    protected $_referenceMap    = array(
			'Zfmodules_Model_Modules' => array(
				'columns'           => array('module_id'),
				'refTableClass'     => 'Zfmodules_Model_Modules',
				'refColumns'        => array('module_id') , 
				'onDelete'          => self::CASCADE,
				'onUpdate'          => self::CASCADE			
			)
                );
    
    public function __construct() 
    {
        parent::__construct();
    }
    
    public function lastId()
    {
	$last = $this->_db->fetchAll( $this->_db->select()->from($this->_name, array("MAX(moduleshow_id) as max")));
	return $last[0]['max'];
    }
    
}