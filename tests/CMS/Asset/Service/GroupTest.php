<?php
namespace Asset\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Group Service.
 */
class GroupTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetGroup()
    {
        $group = new \Asset\Model\Group('sys', 'title');

        $groupService = m::mock(new \Asset\Service\Group(), array(m::BLOCKS => array('getGroup')));
        $groupService->shouldReceive('findBySysname')->with('Asset\Model\Group', 'sys')->andReturn($group);

        $newGroup = $groupService->getGroup('sys');

        $this->assertEquals($group, $newGroup);
    }
}