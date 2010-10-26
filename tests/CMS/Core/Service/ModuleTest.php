<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Module Service.
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }
    
    public function testCreateModuleFromConfig()
    {
        $em = m::mock('Doctrine\ORM\EntityManager');

        $config = new \Zend_Config(array(
            'sysname' => 'moduleName',
            'title' => 'moduleTitle',
            'blockTypes' => array(
                'TestMockBlock' => array('name' => 'TestMockBlock', 'class' => 'Core\Service\TestMockBlock')
            ),
            'contentTypes' => array(
                'TestMockContent' => array('name' => 'TestMockContent', 'class' => 'Core\Service\TestMockContent', 'controller' => 'Core\Service\TestMockController')
            ),
            'activityTypes' => array(
                'TestMockActivity' => array('name' => 'TestMockActivity', 'class' => 'Core\Service\TestMockActivity')
            )
        ), true);

        $module = new \Core\Model\Module($config->sysname, $config->title);
        $module->addResource(new \Core\Model\Module\BlockType('TestMockBlock', 'TestMockBlock', 'Core\Service\TestMockBlock'));
        $module->addResource(new \Core\Model\Module\ContentType('TestMockContent', 'TestMockContent', 'Core\Service\TestMockContent', 'Core\Service\TestMockController'));
        $module->addResource(new \Core\Model\Module\ActivityType('TestMockActivity', 'TestMockActivity', 'Core\Service\TestMockActivity'));

        $moduleService = new \Core\Service\Module($em);
        $newModule = $moduleService->createModuleFromConfig($config);

        $this->assertEquals($module, $newModule);
    }
}

class TestMockBlock {}
class TestMockContent {}
class TestMockController {}
class TestMockActivity {}