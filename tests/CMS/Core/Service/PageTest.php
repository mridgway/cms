<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Page.
 */
class PageTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        
    }

    public function tearDown()
    {
        m::close();
    }

    public function testGetPage()
    {
        $layout = new \Core\Model\Layout('default');
        $page = new \Core\Model\Page($layout);

        $rStub = $this->getMock('Mock\EntityRepository');
        $rStub  ->expects($this->any())
                ->method('getPageForRender')
                ->will($this->returnValue($page));

        $emStub = $this->getMock('Mock\EntityManager');
        $emStub ->expects($this->any())
                ->method('getRepository')
                ->with($this->equalTo('Core\Model\Page'))
                ->will($this->returnValue($rStub));

        $pageService = new Page($emStub);

        $this->assertEquals($pageService->getPage(1), $page);
    }

    public function testCreatePageFromTemplate()
    {
        $emStub = $this->getMock('Mock\EntityManager');
        $pageService = new Page($emStub);

        $content = new \Core\Model\Content\Text('title', 'content', false);
        $view = new \Mock\View();
        $view->sysname = 'view';
        $block = new \Core\Model\Block\StaticBlock($content, $view);
        $block->setWeight(2);

        $holder = new \Core\Model\Content\Placeholder('placeHolder', 'Core\Model\Content\Text', 'description');
        $placeholder = new \Mock\Block\StaticBlock($holder, $view);

        $dynamicBlock = new \Mock\Block\DynamicBlock($view);
        $dynamicBlock->setConfigValue('configPropertyName', 'value1', null);

        $dynamicBlock2 = new \Mock\Block\DynamicBlock($view);
        $dynamicBlock2->setConfigValue('id', 'value', $placeholder);

        $layout = new \Core\Model\Layout('default');
        $template = new \Core\Model\Template('testTemplate', $layout);
        $location = new \Core\Model\Layout\Location('main');
        $template->addBlock($block, $location);
        $template->addBlock($dynamicBlock, $location);
        $template->addBlock($placeholder, $location);
        $template->addBlock($dynamicBlock2, $location);
        

        $newContent = new \Mock\Content\Text('newTitle', 'newContent', false);
        $newContent->setContentId(2);
        $newView = new \Mock\View();
        $newView->sysname = 'newView';
        $page = $pageService->createPageFromTemplate($template, array('placeHolder' => array(
                                                                                    'content' => $newContent,
                                                                                    'view' => $newView
                                                                                    )
                                                                      ));
        
        $this->assertEquals($template->layout, $page->layout);

        $pageBlock = $page->blocks[0];
        $this->assertEquals($block->content, $pageBlock->content);
        $this->assertEquals($block->weight, $pageBlock->weight);
        $this->assertEquals($block->location, $pageBlock->location);
        $this->assertEquals($block, $pageBlock->inheritedFrom);

        $pageDynamic = $page->blocks[1];
        $this->assertEquals(get_class($dynamicBlock), get_class($pageDynamic));
        $this->assertEquals($dynamicBlock->location, $pageDynamic->location);
        $this->assertEquals('value1', $pageDynamic->getConfigValue('configPropertyName'));

        $pageholder = $page->blocks[2];
        $this->assertEquals($newContent, $pageholder->content);
        $this->assertEquals($newView, $pageholder->getView(false));

        $pageDynamic2 = $page->blocks[3];
        $this->assertEquals(2, $pageDynamic2->getConfigValue('id'));
    }

    public function testGetPageVariables()
    {
        $content = new \Core\Model\Content\Text('title', 'content', false);
        $view = new \Mock\View();
        $block = new \Core\Model\Block\StaticBlock($content, $view);

        $location = new \Core\Model\Layout\Location('main');

        $layout = new \Core\Model\Layout('default');
        $page = new \Core\Model\Page($layout);
        $page->addBlock($block, $location);
        $page->addBlock($block, $location);

        $vars = array('property1' => 'value1', 'property2' => 'value2');

        $blockServiceStub = $this->getMock('Core\Service\Block');
        $blockServiceStub   ->expects($this->any())
                            ->method('getVariables')
                            ->will($this->returnValue($vars));

        $emStub = $this->getMock('Mock\EntityManager');

        $pageService = new Page($emStub);
        $pageService->setBlockService($blockServiceStub);

        $this->assertEquals($vars, $pageService->getPageVariables($page));
    }

    /**
     * @dataProvider addProvider
     */
    public function testAddPage($data, $layout, $formStub, $route, $page, $pageRoute)
    {
        $rStub = m::mock();
        $rStub->shouldReceive('findOneBy')->with(array('sysname' => $data['layout']))->once()->andReturn($layout);

        $routeServiceStub = m::mock();
        $routeServiceStub->shouldReceive('create')->with($data['pageRoute'])->once()->andReturn($route);

        $emStub = $this->getMock('Mock\EntityManager');
        $emStub ->expects($this->any())
                ->method('getRepository')
                ->with($this->equalTo('Core\Model\Layout'))
                ->will($this->returnValue($rStub));
        
        $pageService = new Page($emStub);
        $pageService->setDefaultForm($formStub);
        $pageService->setRouteService($routeServiceStub);

        $newPage = $pageService->addPage($data);

        $this->assertEquals($page->layout, $newPage->layout);
        $this->assertEquals($page->title, $newPage->title);
        $this->assertEquals($page->description, $newPage->description);
        $this->assertEquals($pageRoute, $newPage->pageRoute);
        $this->assertEquals($route, $newPage->pageRoute->route);
    }

    /**
     * @dataProvider addProvider
     */
    public function testAddPage_EntityManagerCalls($data, $layout, $formStub, $route, $page, $pageRoute)
    {
        $rStub = m::mock();
        $rStub->shouldReceive('findOneBy')->with(array('sysname' => $data['layout']))->once()->andReturn($layout);

        $routeServiceStub = m::mock();
        $routeServiceStub->shouldReceive('create')->with($data['pageRoute'])->once()->andReturn($route);

        $emStub = m::mock('Mock\EntityManager');
        $emStub->shouldReceive('getRepository')->with('Core\Model\Layout')->once()->andReturn($rStub)->ordered(1);
        $emStub->shouldReceive('persist')->with($route)->once()->ordered(1);
        $emStub->shouldReceive('persist')->with(m::type('Core\Model\Page'))->once()->ordered(1);
        $emStub->shouldReceive('persist')->with(m::type('Core\Model\PageRoute'))->once()->ordered(1);
        $emStub->shouldReceive('flush')->ordered();

        $pageService = new Page($emStub);
        $pageService->setDefaultForm($formStub);
        $pageService->setRouteService($routeServiceStub);

        $newPage = $pageService->addPage($data);
    }

    /**
     * @expectedException Core\Exception\FormException
     */
    public function testAddPageException()
    {
        $emStub = m::mock('Mock\EntityManager');

        $formStub = m::mock('Core\Form\Page');
        $formStub->shouldReceive(array('populate' => $formStub, 'isValid' => false));

        $pageService = new Page($emStub);
        $pageService->setDefaultForm($formStub);
        $pageService->addPage(array());
    }

    /**
     * @dataProvider addProvider
     */
    public function testEditPage($data, $layout, $formStub, $route, $page, $pageRoute)
    {
        $newData = array(
            'title' => 'newTitle',
            'pageRoute' => 'newRoute',
            'layout' => '1col',
            'description' => 'newDescription',
        );

        $newLayout = new \Core\Model\Layout($data['layout']);
        $newRoute = new \Core\Model\Route($data['pageRoute']);

        $routeServiceStub = m::mock();
        $routeServiceStub->shouldReceive('edit')->with($page->pageRoute->route, $newData['pageRoute'])->once()->andReturn($newRoute);

        $formStub = m::mock();
        $formStub->shouldReceive('populate')->with($newData)->andReturn($formStub);
        $formStub->shouldReceive('isValid')->andReturn(true);
        $formStub->shouldReceive('getValues')->andReturn($newData);

        $emStub = m::mock('Mock\EntityManager');
        $emStub->shouldReceive('getReference')->with('Core\Model\Layout', $newData['layout'])->andReturn($newLayout);
        $emStub->shouldReceive('persist')->with($newRoute)->once();
        $emStub->shouldReceive('flush')->once();

        $pageService = new Page($emStub);
        $pageService->setDefaultForm($formStub);
        $pageService->setRouteService($routeServiceStub);

        $editedPage = $pageService->editPage($page, $newData);

        $this->assertEquals($newData['title'], $editedPage->title);
        $this->assertEquals($newData['description'], $editedPage->description);
        $this->assertEquals($newLayout, $editedPage->layout);
        $this->assertEquals($newRoute->template, $editedPage->pageRoute->route->template);
    }

    /**
     * @dataProvider addProvider
     * @expectedException Core\Exception\FormException
     */
    public function testEditPageException($data, $layout, $formStub, $route, $page, $pageRoute)
    {
        $emStub = m::mock('Mock\EntityManager');

        $formStub = m::mock('Core\Form\Page');
        $formStub->shouldReceive(array('populate' => $formStub, 'isValid' => false));

        $pageService = new Page($emStub);
        $pageService->setDefaultForm($formStub);
        $pageService->editPage($page, $data);
    }

    public function addProvider()
    {
        $data = array(
            'title' => 'title',
            'pageRoute' => 'new/page',
            'layout' => 'main',
            'description' => 'description'
        );

        $layout = new \Core\Model\Layout('main');

        $formStub = new \Mock\Form\Page($data, true);

        $route = new \Core\Model\Route($data['pageRoute']);

        $page = new \Core\Model\Page($layout);
        $pageRoute = $route->routeTo($page);
        $page->setData(array('title' => $data['title'], 'description' => $data['description']));
        $page->setPageRoute($pageRoute);

        return array(
            array($data, $layout, $formStub, $route, $page, $pageRoute)
        );
    }
}