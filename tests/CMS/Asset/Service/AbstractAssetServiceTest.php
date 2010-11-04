<?php
namespace Asset\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Extension Service.
 */
class AbstractAssetServiceTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testFindBySysname()
    {
        $extension = new \Asset\Model\Extension('sys');

        $repository = m::mock();
        $repository->shouldReceive('findOneBySysname')->with('sys')->andReturn($extension)->ordered();
        $repository->shouldReceive('findOneBySysname')->with('sys')->andReturn(null)->ordered();

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->with('Asset\Model\Extension')->andReturn($repository);

        $abstractAssetService = new ConcreteService();
        $abstractAssetService->setEntityManager($em);

        $newExtension = $abstractAssetService->find('Asset\Model\Extension', 'sys');

        $this->assertEquals($extension, $newExtension);

        $this->setExpectedException('Exception');
        $abstractAssetService->find('Asset\Model\Extension', new \stdClass());
    }

    public function testFindBySysnameShouldThrowExceptionIfClassNotFound()
    {
        $repository = m::mock();
        $repository->shouldReceive('findOneBySysname')->with('sys')->andReturn(null)->ordered();

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->with('Asset\Model\Extension')->andReturn($repository);

        $abstractAssetService = new ConcreteService();
        $abstractAssetService->setEntityManager($em);

        $this->setExpectedException('Exception');
        $abstractAssetService->find('Asset\Model\Extension', 'sys');
    }
}

class ConcreteService extends AbstractAssetService {
    public function find($class, $sysname)
    {
        return $this->findBySysname($class, $sysname);
    }
}