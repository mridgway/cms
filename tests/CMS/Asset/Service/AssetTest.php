<?php
namespace Asset\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for AbstractService.
 */
class AssetTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testCreate()
    {
        $type = new \Asset\Model\Type('sysname', 'title');
        $mimeType = new \Asset\Model\MimeType('sysname', $type);
        $group = new \Asset\Model\Group('sysname', 'title');
        $extension = new \Asset\Model\Extension('sys');
        $asset = new \Asset\Model\Asset('sysname', 'name', $extension, $group, $mimeType);

        $em = m::mock('Doctrine\ORM\EntityManager');

        $extensionService = m::mock();
        $extensionService->shouldReceive('getExtension')->with('extension')->andReturn($extension);

        $groupService = m::mock();
        $groupService->shouldReceive('getGroup')->with('group')->andReturn($group);

        $mimeTypeService = m::mock();
        $mimeTypeService->shouldReceive('getMimeType')->with('mimeType')->andReturn($mimeType);

        $assetService = m::mock(new \Asset\Service\Asset(), array(m::BLOCKS => array('create')));
        $assetService->shouldReceive('getExtensionService')->andReturn($extensionService);
        $assetService->shouldReceive('getGroupService')->andReturn($groupService);
        $assetService->shouldReceive('getMimeTypeService')->andReturn($mimeTypeService);
        $assetService->setEntityManager($em);

        $newAsset = $assetService->create('sysname', 'name', 'extension', 'group', 'mimeType');
        $this->assertEquals($asset->getSysname(), $newAsset->getSysname());
        $this->assertEquals($asset->getName(), $newAsset->getName());
        $this->assertEquals($asset->getExtension(), $newAsset->getExtension());
        $this->assertEquals($asset->getGroup(), $newAsset->getGroup());
        $this->assertEquals($asset->getMimeType(), $newAsset->getMimeType());
    }
}