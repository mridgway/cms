<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
require_once '../../../bootstrap.php';

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
        $block = m::Mock('Core\Model\Block');

        $inheritBlock = m::Mock('Core\Model\Block');
        $value1 = new \Core\Model\Block\Config\Value('name', 'value', $inheritBlock);

        $entity = m::Mock();
        $entity->shouldReceive('getDependentValues')->with($block)->andReturn(array($value1));

        $em = m::Mock(new \Mock\EntityManager());
        $em->shouldReceive('getRepository')->andReturn($entity);

        $blockService = new \Core\Service\Block($em);

        $this->assertEquals($inheritBlock, $value1->getInheritsFrom());

        $blockService->removeConfigDependencies($block);

        $this->assertEquals(null, $value1->getInheritsFrom());
    }

    public function testDispatchBlockAction()
    {
        $em = m::Mock(new \Mock\EntityManager());

        $block = m::Mock('Core\Model\Block');
        $request = m::Mock('Zend_Controller_Request_Http');
        $controller = m::Mock(new MockBlockController);
        $controller->shouldReceive('setEntityManager')->with($em);
        $controller->shouldReceive('setRequest')->with($request);
        
        $blockService = new MockBlockService($em);
        $blockService->setController($controller);

        $blockService->dispatchBlockAction($block, 'actionName', $request);
    }

    /**
     * 
     */
    public function testGetBlockControllerObject()
    {
        $em = m::Mock(new \Mock\EntityManager());
        $blockService = new MockBlockService2($em);
        $block = m::Mock('Core\Model\Block');
        
        $name = 'stdClass';
        $blockService->setControllerName($name);

        $this->assertEquals(new \stdClass(), $blockService->getBlockControllerObject($block));

        $blockService->setControllerName($block);

        /*
         * public function getBlockControllerObject(\Core\Model\Block $block)
            {
                $controllerName = $this->getBlockController($block);

                if(null === $controllerName) {
                    throw new Exception(get_class($block) . ' controller is not specified.  Check the module.ini file.');
                }

                return new $controllerName;
            }
         */
    }
}

class MockBlockController
{
    public function actionName()
    {
        
    }
}

class MockBlockService extends \Core\Service\Block
{
    protected $controller;
    protected $controllerName;

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function setControllerName($name)
    {
        $this->controllerName = $name;
    }

    public function getBlockController(\Core\Model\Block $block)
    {
        return $this->controllerName;
    }

    public function getBlockControllerObject(\Core\Model\Block $block)
    {
        return $this->controller;
    }
}

class MockBlockService2 extends \Core\Service\Block
{
    protected $controllerName;

    public function setControllerName($name)
    {
        $this->controllerName = $name;
    }

    public function getBlockController(\Core\Model\Block $block)
    {
        return $this->controllerName;
    }
}