<?php
namespace Asset\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Extension Service.
 */
class ExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetExtension()
    {
        $extension = new \Asset\Model\Extension('sys');

        $extensionService = m::mock(new \Asset\Service\Extension(), array(m::BLOCKS => array('getExtension')));
        $extensionService->shouldReceive('findBySysname')->with('Asset\Model\Extension', 'sys')->andReturn($extension);

        $newExtension = $extensionService->getExtension('sys');

        $this->assertEquals($extension, $newExtension);
    }
}