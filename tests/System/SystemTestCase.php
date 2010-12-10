<?php

namespace System;

use \Mockery as m;

abstract class SystemTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \ZendX\Application53\Application
     */
    protected $application;

    /**
     * @var \sfServiceContainer
     */
    protected $_sc;

    /**
     * @var \sfServiceContainer
     */
    protected $_em;

    /**
     * @var \Zend_Controller_Front
     */
    protected $_frontController;

    public function setUp()
    {
        parent::setUp();

        // make sure previous tests have cleaned up singletons
        \Core\Module\Registry::destroy();
        \Zend_Registry::_unsetInstance();
        \Zend_Controller_Front::getInstance()->resetInstance();

        $_SERVER['HTTP_HOST'] = 'doesnotmatter';
        $_SERVER['SERVER_PROTOCOL'] = 'doesnotmatter';
        $_SERVER['REMOTE_ADDR'] = 'doesnotmatter';
        $_SERVER['HTTP_USER_AGENT'] = 'doesnotmatter';

        require_once 'ZendX/Application53/Application.php';
        $this->application = new \ZendX\Application53\Application(
            APPLICATION_ENV,
            APPLICATION_PATH . '/application.ini'
        );

        $redirector = new \Zend_Controller_Action_Helper_Redirector();
        $mockRedirector = m::mock($redirector, array(m::BLOCKS => array('gotoUrl')));
        $mockRedirector->shouldReceive('getExit')->andReturn(false);
        \Zend_Controller_Action_HelperBroker::addHelper($mockRedirector);

        $this->application->bootstrap();
        $this->_sc = $this->application->getBootstrap()->serviceContainer;

        $this->_em = $this->_sc->getService('doctrine');

        $this->_frontController = $this->application->getBootstrap()->getResource('frontController');

        $this->_frontController->returnResponse(true);

        $this->_sc->getService('doctrine')->beginTransaction();
    }

    public function tearDown()
    {
        $this->_sc->getService('doctrine')->rollback(true);
        \Core\Module\Registry::destroy();
        \Zend_Registry::_unsetInstance();
        \Zend_Controller_Front::getInstance()->resetInstance();
        m::close();
    }

}