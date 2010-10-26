<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for AbstractService.
 */
class BlockTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetVariables()
    {
        $data = array('title' => 'metadata', 'content' => 'metadata');

        $meta = m::mock();
        $meta->shouldReceive('getReflectionProperties')->andReturn($data);

        $em = m::mock(new \Mock\EntityManager());

        // test with inputs
        $propertyOne = 'one';
        $propertyTwo = 'two';

        $view = new \Mock\View();
        $block = new \Mock\Block\DynamicBlock($view);
        $block->addConfigProperties(array(
            new \Core\Model\Block\Config\Property($propertyOne, 'value'),
            new \Core\Model\Block\Config\Property($propertyTwo, 'value')
        ));
        
        $blockService = new \Core\Service\Block($em);
        $vars = $blockService->getVariables($block);

        $this->assertTrue(in_array($propertyOne, $vars));
        $this->assertTrue(in_array($propertyTwo, $vars));

        // test another case
        $type = 'Core\Model\Content\Text';
        $content = new \Core\Model\Content\Placeholder('sysname', $type, 'text placeholder');
        $staticBlock = new \Core\Model\Block\StaticBlock($content, $view);
        $em->shouldReceive('getClassMetadata')->with($type)->andReturn($meta);

        $vars = $blockService->getVariables($staticBlock);

        $this->assertEquals($vars, array_keys($data));
        
        // test another case
        $content = new \Core\Model\Content\Text('title', 'content', false);
        $staticBlock = new \Core\Model\Block\StaticBlock($content, $view);
        $em->shouldReceive('getClassMetadata')->with(\get_class($content))->andReturn($meta);

        $vars = $blockService->getVariables($staticBlock);
    }

    public function testRemoveConfigDependencies()
    {
        $block = m::mock('Core\Model\Block');

        $inheritBlock = m::mock('Core\Model\Block');
        $value1 = new \Core\Model\Block\Config\Value('name', 'value', $inheritBlock);

        $entity = m::mock();
        $entity->shouldReceive('getDependentValues')->with($block)->andReturn(array($value1));

        $em = m::mock(new \Mock\EntityManager());
        $em->shouldReceive('getRepository')->andReturn($entity);

        $blockService = new \Core\Service\Block($em);

        $this->assertEquals($inheritBlock, $value1->getInheritsFrom());

        $blockService->removeConfigDependencies($block);

        $this->assertEquals(null, $value1->getInheritsFrom());
    }

    public function testDispatchBlockAction()
    {
        $em = m::mock(new \Mock\EntityManager());
        $block = m::mock('Core\Model\Block');
        $action = 'actionName';
        $request = m::mock('Zend_Controller_Request_Http');
        $controller = m::mock(new MockBlockController);
        $controller->shouldReceive('setEntityManager')->with($em);
        $controller->shouldReceive('setRequest')->with($request);

        $blockService = m::mock(new \Core\Service\Block($em), array(m::BLOCKS => array('dispatchBlockAction')));
        $blockService->shouldReceive('getBlockControllerObject')->andReturn($controller);

        $blockService->dispatchBlockAction($block, $action, $request);

        $this->setExpectedException('Exception');
        $blockService->dispatchBlockAction($block, 'thisActionDoesNotExist', $request);
    }
    
    public function testGetBlockControllerObject()
    {
        $em = m::mock(new \Mock\EntityManager());
        $block = m::mock('Core\Model\Block');

        $blockService = m::mock(new \Core\Service\Block($em), array(m::BLOCKS => array('getBlockControllerObject')));
        $blockService->shouldReceive('getBlockController')->with($block)->andReturn('stdClass');

        $this->assertEquals(new \stdClass(), $blockService->getBlockControllerObject($block));

        $this->setExpectedException('Exception');
        $blockService = m::mock(new \Core\Service\Block($em), array(m::BLOCKS => array('getBlockControllerObject')));
        $blockService->shouldReceive('getBlockController')->with($block)->andReturn(null);
        $blockService->getBlockControllerObject($block);
    }

    public function testGetBlockController()
    {
        $em = m::mock(new \Mock\EntityManager());

        $module = new \Core\Model\Module('sysname', 'title');
        $module->addResource(new \Core\Model\Module\ContentType('title', 'discriminator', 'Core\Service\MockContent', 'Core\Service\MockContentController'));

        $storage = m::mock();
        $storage->shouldReceive('getModules')->andReturn(array($module));
        
        $registry = m::mock('Core\Module\Registry');
        $registry->shouldReceive('getDatabaseStorage')->andReturn($storage);

        $content = new MockContent();
        $view = new \Mock\View();
        $block = new \Core\Model\Block\StaticBlock($content, $view);

        $blockService = new \Core\Service\Block($em);
        $blockService->setModuleRegistry($registry);

        $this->assertEquals('Core\Service\MockContentController', $blockService->getBlockController($block));
    }
    
    public function testDeleteBlock()
    {
        $block = m::mock('Core\Model\Block');
        
        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('remove')->with($block)->ordered();
        $em->shouldReceive('flush')->ordered();


        $blockService = m::mock(new \Core\Service\Block($em), array(m::BLOCKS => array('deleteBlock')));
        $blockService->shouldReceive('removeConfigDependencies')->with($block);

        $blockService->deleteBlock($block);
    }
}

class MockBlockController
{
    public function actionName()
    {
        
    }
}

class MockContent extends \Core\Model\Content {}

class MockContentController {}