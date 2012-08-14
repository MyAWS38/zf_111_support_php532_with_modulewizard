<?php
 /**
  * Extender for Zend_Controller_Action
  * @package Zfgen
  */
class Zfgen_Controller_Action extends Zend_Controller_Action
{
    public function getParamsWithoutMod()
    {
	$params =  $this->_getAllParams();
        
        $arrdef = array('module','controller','action');
        foreach($params as $key=>$row)
        {
            foreach($arrdef as $keydef=>$rowdef){
                if ($rowdef == $key)
                    unset($params[$key]);
            }    
        }
        return $params;
    }
}