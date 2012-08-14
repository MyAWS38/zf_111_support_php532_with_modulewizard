<?php

class IndexController extends Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
       	$message = $this->_helper->_flashMessenger->getMessages();          
        $this->view->message = !empty($message) ? "<blink><font color=\"red\">$message[0]</font></blink><br>" : "";
    }

}

