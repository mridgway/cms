<?php

namespace Core\Model\Frontend;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../../bootstrap.php';

use \Mockery as m;

class BlockInfoTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
        m::close();
    }

    public function testSuccess()
    {
        $this->markTestIncomplete('Dependency is ridikuhlus');
        $data = new \stdClass();
        $data->id = 1;
        $data->properties = array('property1' => 'value1');
        $data->actions = array();
        $data->location = 'sysname';
        $data->weight = 1;

        $blockService = m::mock('Core\Service\Block');
        $blockService->shouldReceive('getVariables')->andReturn($data->properties);

        $auth = m::mock('Core\Auth\Auth');
        $auth->shouldReceive('getIdentity')->andReturn(true);

        $location = m::Mock('Core\Model\Layout\Location');
        $location->shouldReceive('getSysname')->andReturn('sysname');

        $block = m::mock('Core\Model\Block');
        $block->shouldReceive('canView')->with(true)->andReturn(true);
        $block->shouldReceive('getId')->andReturn(1);
        $block->shouldReceive('getLocation')->andReturn($location);
        $block->shouldReceive('getWeight')->andReturn(1);
        $block->shouldReceive('canMove')->andReturn(false);
        $block->shouldReceive('canEdit')->andReturn(false);
        $block->shouldReceive('canConfigure')->andReturn(false);
        $block->shouldReceive('canDelete')->andReturn(false);

        $blockInfo = new \Core\Model\Frontend\BlockInfo();
        $blockInfo->setBlockService($blockService);
        $blockInfo->setAuth($auth);

        $newInfo = $blockInfo->success($block);

        $this->assertEquals(array($data), $newInfo->data);
    }

    public function testGetBlockActions()
    {
        $block = m::mock('Core\Model\Block');
        $block->shouldReceive('getId')->andReturn(1);
        $block->shouldReceive('canMove')->andReturn(true);
        $block->shouldReceive('canEdit')->andReturn(true);
        $block->shouldReceive('canConfigure')->andReturn(true);
        $block->shouldReceive('canDelete')->andReturn(true);

        $move = new Action('block-move');
        $move->plugin = 'BlockMove';

        $edit = new Action('block-edit', '/direct/block/edit/?id=' . $block->getId());
        $edit->plugin = 'BlockEdit';

        $configure = new Action('block-configure', '/direct/block/configure/?id=' . $block->getId());
        $configure->plugin = 'BlockConfigure';

        $delete = new Action('block-delete', '/direct/block/delete/?id=' . $block->getId());
        $delete->plugin = 'BlockDelete';

        $actions = array(
            'block-move' => $move,
            'block-edit' => $edit,
            'block-configure' => $configure,
            'block-delete' => $delete
        );

        $auth = m::mock('Core\Auth\Auth');
        $auth->shouldReceive('getIdentity')->andReturn(true);

        $blockInfo = new \Core\Model\Frontend\BlockInfo();
        $blockInfo->setAuth($auth);

        $returnedActions = $blockInfo->_getBlockActions($block);

        $this->assertEquals($actions, $returnedActions);
    }
}