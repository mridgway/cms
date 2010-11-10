<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for AbstractService.
 */
class AbstractModelTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetDefaultClassName()
    {
        $className = 'Mock\Model\TestAbstractModel';

        $em = m::mock('Doctrine\ORM\EntityManager');

        $amService = new \Mock\Service\TestAbstractModel($em);

        $newClassName = $amService->getDefaultClassName();

        $this->assertEquals($className, $newClassName);
    }

    public function testGetClassName()
    {
        $className = 'Mock\Model\Test\TestAbstractModel';

        $em = m::mock('Doctrine\ORM\EntityManager');

        $amService = new \Mock\Service\TestAbstractModel($em);
        $amService->setClassName($className);
        $newClassName = $amService->getClassName();
        $this->assertEquals($className, $newClassName);

        $amService->setClassName(null);
        $className = 'Mock\Model\TestAbstractModel';
        $newClassName = $amService->getDefaultClassName();
        $this->assertEquals($className, $newClassName);
    }

    /*public function testCreate()
    {
        $data = array(
            'name' => 'name',
            'phone' => '1231231234'
        );

        $model = new \Mock\Model\TestAbstractModel();

        $em = m::mock('Doctrine\ORM\EntityManager');

        $amService = new TestAbstractModel($em);

        $model = $amService->create($data);
    }*/
}