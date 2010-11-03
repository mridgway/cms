<?php

namespace Core\Controller;

class SeedController extends \Zend_Controller_Action
{
    protected $_modules = array(
        // declare modules here
    );

    public function seedAction()
    {
        foreach($this->_modules as $module)
        {
            $this->_helper->actionStack('seed', 'seed', $module);
            \ob_flush();
        }
    }
}