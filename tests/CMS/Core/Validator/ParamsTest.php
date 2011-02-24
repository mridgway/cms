<?php
namespace Core\Validator;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Params Validator.
 */
class ParamsTest extends \CMSTestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testValidator()
    {
        $route = m::mock();
        $route->shouldReceive('getId')->andReturn('id');

        $pageRouteById = m::mock();
        $pageRouteById->shouldReceive('getRoute')->andReturn($route);

        $repository = m::mock();
        $repository->shouldReceive('findOneBy')->andReturn($pageRouteById);

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->andReturn($repository);
        $em->shouldReceive('getReference')->andReturn($pageRouteById);

        $validator = new \Core\Validator\Params();
        $validator->setEntityManager($em);

        $string = 'param1-_';
        $this->assertEquals(true, $validator->isValid($string));

        $string = 'param1 ';
        $this->assertEquals(false, $validator->isValid($string));
    }
}