<?php
namespace Asset\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Extension Service.
 */
class MimeTypeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetMimeType()
    {
        $type = new \Asset\Model\Type('sys', 'title');
        $mimeType = new \Asset\Model\MimeType('sysname', $type);

        $mimeTypeService = m::mock(new \Asset\Service\MimeType(), array(m::BLOCKS => array('getMimeType')));
        $mimeTypeService->shouldReceive('findBySysname')->with('Asset\Model\MimeType', 'sys')->andReturn($mimeType);

        $newMimeType = $mimeTypeService->getMimeType('sys');

        $this->assertEquals($mimeType, $newMimeType);
    }
}