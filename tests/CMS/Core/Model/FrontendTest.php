<?php

namespace Core\Model;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for Frontend.
 */
class FrontendTest extends \PHPUnit_Framework_TestCase
{
    protected $frontend;

    protected function setUp()
    {
        $this->frontend = new \Mock\NonAbstractFrontend();
    }

    protected function tearDown()
    {
    }

    public function testToString()
    {
        $this->assertEquals((string) $this->frontend, \Zend_Json::encode($this->frontend, \Zend_Json::TYPE_OBJECT));
    }

    public function testSetCode()
    {
        $this->frontend->setCode(2, 'hello');
        $this->assertEquals(new \Core\Model\Frontend\Code(2, 'hello'), $this->frontend->code);
    }
}