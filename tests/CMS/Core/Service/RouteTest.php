<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Page.
 */
class RouteTest extends \PHPUnit_Framework_TestCase
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
        $em = m::mock('Doctrine\ORM\EntityManager');

        $routeService = new \Core\Service\Route($em);

        $r = new \Core\Model\Route('name');

        $this->assertEquals($r, $routeService->create('name'));
    }
}