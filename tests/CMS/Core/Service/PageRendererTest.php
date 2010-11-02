<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
//require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Module Service.
 */
class PageRendererTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {

    }

    public function tearDown()
    {
        m::close();
    }

    public function testRenderPage()
    {
        $em = m::mock('Doctrine\ORM\EntityManager');
        
        $request = m::mock('\Zend_Controller_Request_Http');
        $request->shouldIgnoreMissing();
        
        $location = m::mock('Core\Model\Layout\Location');
        $location->shouldReceive('addContent');

        $block1 = m::mock('Core\Model\Block\DynamicBlock');
        $block1->shouldIgnoreMissing();
        $block1->shouldReceive('render');
        $block1->shouldReceive('getLocation')->andReturn($location);

        $block2 = m::mock('Core\Model\Block\StaticBlock');
        $block2->shouldIgnoreMissing();
        $block2->shouldReceive('render');
        $block2->shouldReceive('getLocation')->andReturn($location);

        $layout = m::mock('Core\Model\Layout');
        $layout->shouldReceive('render');
        
        $page = new \Core\Model\Page($layout);
        $page->addBlock($block1, $location);
        $page->addBlock($block2, $location);

        $layout->shouldReceive('assign')->with('page', $page);

        $blockService = m::mock('Core\Service\Block');
        $blockService->shouldReceive('initBlock')->with($block1, $request);
        $blockService->shouldReceive('initBlock')->with($block2, $request);
        $blockService->shouldReceive('canView')->with($block1)->andReturn(true);
        $blockService->shouldReceive('canView')->with($block2)->andReturn(true);

        $view1 = m::mock('Zend_View');
        $view1->shouldIgnoreMissing();
        $view1->shouldReceive('render');
        
        $view2 = m::mock('Zend_View');
        $view2->shouldIgnoreMissing();
        $view2->shouldReceive('render');

        $pageRendererService = m::mock(new \Core\Service\PageRenderer($em), array(m::BLOCKS => array('renderPage')));
        $pageRendererService->shouldReceive('getNewView')->andReturn($view1)->ordered();
        $pageRendererService->shouldReceive('getNewView')->andReturn($view2)->ordered();
        $pageRendererService->setBlockService($blockService);

        $pageRendererService->renderPage($page, $request);
    }
}