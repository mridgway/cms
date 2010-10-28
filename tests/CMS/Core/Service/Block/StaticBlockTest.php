<?php
namespace Core\Service\Block;

require_once 'PHPUnit/Framework.php';
require_once '../../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for StaticBlock Service.
 */
class StaticBlockTest extends \PHPUnit_Framework_TestCase
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
        
        $view = new \Mock\View();
        $content = new \Core\Model\Content\Text(null, 'put content here', false);
        $block = new \Core\Model\Block\StaticBlock($content, $view);

        $moduleService = m::mock('Core\Service\Module');
        $moduleService->shouldReceive('getView')->andReturn($view);

        $sbService = new \Core\Service\Block\StaticBlock($em);
        $sbService->setModuleService($moduleService);

        $newBlock = $sbService->create();
        $this->assertEquals($block, $newBlock);

        $newBlock = $sbService->create($content, null);
        $this->assertEquals($block, $newBlock);

        $newBlock = $sbService->create(null, $view);
        $this->assertEquals($block, $newBlock);

        $newBlock = $sbService->create($content, $view);
        $this->assertEquals($block, $newBlock);
    }
}