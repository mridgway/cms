<?php

namespace Core\Controller;

class PageControllerTest extends \Zend_Test_PHPUnit_ControllerTestCase
{
    public function setUp()
    {
        $this->bootstrap = new \Zend_Application(
                'testing',
                APPLICATION_PATH . '/application.ini'
        );

        parent::setUp();
    }

    public function testTruth()
    {
        $this->assertEquals(true, true);
    }
}