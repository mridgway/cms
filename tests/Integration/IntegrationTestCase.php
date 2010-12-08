<?php

namespace Integration;

abstract class IntegrationTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \ZendX\Application53\Application
     */
    protected $application;

    /**
     * @var \sfServiceContainer
     */
    protected $_sc;

    public function setUp()
    {
        parent::setUp();
        require_once 'ZendX/Application53/Application.php';
        $this->application = new \ZendX\Application53\Application(
            APPLICATION_ENV,
            APPLICATION_PATH . '/application.ini'
        );

        $this->application->bootstrap();
        $this->_sc = $this->application->getBootstrap()->serviceContainer;

        $this->_sc->getService('doctrine')->beginTransaction();
    }

    public function tearDown()
    {
        $this->_sc->getService('doctrine')->rollback();
        \Core\Module\Registry::destroy();
        \Zend_Registry::_unsetInstance();
    }

}