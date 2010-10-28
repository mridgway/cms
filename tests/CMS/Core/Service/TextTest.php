<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Text Service.
 */
class TextTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetShared()
    {
        $mock = m::mock();
        $mock->shouldReceive('findSharedText');

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->with('Core\Model\Content\Text')->andReturn($mock);

        $textService = new \Core\Service\Text($em);

        $textService->getShared();
    }
}