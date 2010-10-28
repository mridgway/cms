<?php
namespace Core\Service\Block;

require_once 'PHPUnit/Framework.php';
//require_once '../../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for DynamicBlock Service.
 */
class DynamicBlockTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetAddableBlockTypes()
    {
        $mock = m::mock();
        $mock->shouldReceive('findAddableBlockTypes')->andReturn(array(1,2));

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->with('Core\Model\Module\BlockType')->andReturn($mock);

        $dynamicBlockService = new \Core\Service\Block\DynamicBlock($em);

        $array = $dynamicBlockService->getAddableBlockTypes();

        $this->assertEquals(array(1,2), $array);
    }

    public function testCreate()
    {
        $view = m::mock();

        $mock = m::mock();
        $mock->shouldReceive('getView')->with('default')->andReturn($view);
        $mock->shouldReceive('createInstance')->with(array($view));

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('find')->with('Core\Model\Module\BlockType', 1)->andReturn($mock);

        $dynamicBlockService = new \Core\Service\Block\DynamicBlock($em);

        $block = $dynamicBlockService->create(1);
    }
}