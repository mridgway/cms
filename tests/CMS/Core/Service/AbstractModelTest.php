<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Abstract Model Service.
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

    public function testGetDefaultValidationClassName()
    {
        $className = 'Mock\Service\Validation\TestAbstractModel';
        $em = m::mock('Doctrine\ORM\EntityManager');
        $amService = new \Mock\Service\TestAbstractModel($em);
        $newClassName = $amService->getDefaultValidationClassName();
        $this->assertEquals($className, $newClassName);
    }

    public function testGetValidationClassName()
    {
        $className = 'Mock\Service\Validation\Test\TestAbstractModel';

        $em = m::mock('Doctrine\ORM\EntityManager');

        $amService = new \Mock\Service\TestAbstractModel($em);
        $amService->setValidationClassName($className);
        $newClassName = $amService->getValidationClassName();
        $this->assertEquals($className, $newClassName);

        $amService->setClassName(null);
        $className = 'Mock\Service\Validation\TestAbstractModel';
        $newClassName = $amService->getDefaultValidationClassName();
        $this->assertEquals($className, $newClassName);
    }

    public function testCreate()
    {
        $data = array(
            'name' => 'name',
            'phone' => '1231231234'
        );

        $model = new \Mock\Model\TestAbstractModel();
        $model->fromArray($data);
        $em = m::mock('Doctrine\ORM\EntityManager');
        $amService = new \Mock\Service\TestAbstractModel($em);
        $newModel = $amService->create($data);
        $this->assertEquals($model, $newModel);

        $this->setExpectedException('Core\Exception\ValidationException');
        $data['phone'] = '123';
        $newModel = $amService->create($data);
    }

    public function testRetrieveArray()
    {
        $object = new \Mock\Model\TestAbstractModel();
        $object->name = 'name';
        $object->phone = '1231231234';

        $repo = m::mock();
        $repo->shouldReceive('find')->andReturn($object);

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->andReturn($repo);

        $amService = new \Mock\Service\TestAbstractModel($em);

        $newData = $amService->retrieveArray(1);

        $this->assertEquals($object->toArray(), $newData);
    }

    public function testUpdateIntegrationTest()
    {
        $data = array(
            'name' => 'name',
            'phone' => '1231231234'
        );

        $model = new \Mock\Model\TestAbstractModel();
        $model->fromArray($data);
        
        $repo = m::mock();
        $repo->shouldReceive('find')->andReturn($model);

        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('getRepository')->andReturn($repo);
        $amService = new \Mock\Service\TestAbstractModel($em);

        $data = array(
            'id' => '1',
            'name' => 'newName',
            'phone' => '1111111111'
        );

        $newModel = $amService->update($data);
        $this->assertEquals('newName', $newModel->name);
        $this->assertEquals('1111111111', $newModel->phone);

        $this->setExpectedException('Exception');
        unset($data['id']);
        $amService->update($data);
    }
}