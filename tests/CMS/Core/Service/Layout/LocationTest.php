<?php
namespace Core\Service\Layout;

require_once 'PHPUnit/Framework.php';
//require_once '../../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Location Service.
 */
class LocationTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetLocation()
    {
        $repository = m::mock();
        $repository->shouldReceive('findOneBySysname')->andReturn(true);

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->with('Core\Model\Layout\Location')->andReturn($repository);

        $ls = new \Core\Service\Layout\Location($em);
        $ls->getLocation('left');

        $this->setExpectedException('Exception');
        $ls->getLocation(false);
    }
}