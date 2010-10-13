<?php

abstract class CMSFunctionalTestCase extends \PHPUnit_Framework_TestCase
{

    protected $application;

    public function setUp()
    {
        parent::setUp();
        require_once 'ZendX/Application53/Application.php';
        $this->application = new \ZendX\Application53\Application(
            APPLICATION_ENV,
            APPLICATION_PATH . '/application.ini'
        );

        $this->application->bootstrap()->run();
    }

}