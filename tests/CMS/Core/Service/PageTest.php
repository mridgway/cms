<?php
namespace Core\Service;

require_once 'PHPUnit/Framework.php';
require_once '../../../bootstrap.php';

use \Mockery as m;

/**
 * Test class for Page Service.
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

        $repo = $this->getMock('Mock\EntityRepository');
        $repo  ->expects($this->any())
                ->method('getPageForRender')
                ->will($this->returnValue($page));

        $em = $this->getMock('Mock\EntityManager');
        $em ->expects($this->any())
                ->method('getRepository')
                ->with($this->equalTo('Core\Model\Page'))
                ->will($this->returnValue($repo));

        $pageService = new Page($em);

        $this->assertEquals($pageService->getPage(1), $page);

        $this->setExpectedException('Exception');
        $pageService->getPage(false);
    }

    public function testIsAllowed()
    {
        $em = m::mock('Doctrine\ORM\EntityManager');

        $identity = m::mock();
        $identity->shouldReceive('isAllowed')->with('AllPages', 'view')->once()->andReturn(true);
        $identity->shouldReceive('isAllowed')->with('AllPages', 'view')->once()->andReturn(false);

        $auth = m::mock();
        $auth->shouldReceive('getIdentity')->andReturn($identity);

        $pageService = m::mock(new \Core\Service\Page($em), array(m::BLOCKS => array('isAllowed')));
        $pageService->shouldReceive('getAuth')->andReturn($auth);

        $this->assertEquals(true, $pageService->isAllowed('AllPages', 'view'));

        $this->setExpectedException('Exception');
        
        $pageService->isAllowed('AllPages', 'view');
    }

    public function testGetPageIfAllowed()
    {
        $em = m::mock('Doctrine\ORM\EntityManager');

        $page = m::mock();

        $pageService = m::mock(new \Core\Service\Page($em), array(m::BLOCKS => array('getPageIfAllowed')));
        $pageService->shouldReceive('getPage')->with(1)->andReturn($page);
        $pageService->shouldReceive('isAllowed')->with($page, 'view');

        $this->assertEquals($page, $pageService->getPageIfAllowed(1, 'view'));
    }

    public function testCreatePageFromTemplate()
    {
        $em = $this->getMock('Mock\EntityManager');
        $pageService = new Page($em);

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

        $blockService = $this->getMock('Core\Service\Block');
        $blockService   ->expects($this->any())
                            ->method('getVariables')
                            ->will($this->returnValue($vars));

        $em = $this->getMock('Mock\EntityManager');

        $pageService = new Page($em);
        $pageService->setBlockService($blockService);

        $this->assertEquals($vars, $pageService->getPageVariables($page));
    }

    /**
     * @dataProvider addProvider
     */
    public function testAddPage($data, $layout, $form, $route, $page, $pageRoute)
    {
        $repo = m::mock();
        $repo->shouldReceive('findOneBy')->with(array('sysname' => $data['layout']))->once()->andReturn($layout);

        $routeService = m::mock();
        $routeService->shouldReceive('create')->with($data['pageRoute'])->once()->andReturn($route);

        $em = $this->getMock('Mock\EntityManager');
        $em ->expects($this->any())
                ->method('getRepository')
                ->with($this->equalTo('Core\Model\Layout'))
                ->will($this->returnValue($repo));
        
        $pageService = new Page($em);
        $pageService->setDefaultForm($form);
        $pageService->setRouteService($routeService);

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
    public function testAddPage_EntityManagerCalls($data, $layout, $form, $route, $page, $pageRoute)
    {
        $repo = m::mock();
        $repo->shouldReceive('findOneBy')->with(array('sysname' => $data['layout']))->once()->andReturn($layout);

        $routeService = m::mock();
        $routeService->shouldReceive('create')->with($data['pageRoute'])->once()->andReturn($route);

        $em = m::mock('Mock\EntityManager');
        $em->shouldReceive('getRepository')->with('Core\Model\Layout')->once()->andReturn($repo)->ordered(1);
        $em->shouldReceive('persist')->with($route)->once()->ordered(1);
        $em->shouldReceive('persist')->with(m::type('Core\Model\Page'))->once()->ordered(1);
        $em->shouldReceive('persist')->with(m::type('Core\Model\PageRoute'))->once()->ordered(1);
        $em->shouldReceive('flush')->ordered();

        $pageService = new Page($em);
        $pageService->setDefaultForm($form);
        $pageService->setRouteService($routeService);

        $newPage = $pageService->addPage($data);
    }

    /**
     * @expectedException Core\Exception\FormException
     */
    public function testAddPageException()
    {
        $em = m::mock('Mock\EntityManager');

        $form = m::mock('Core\Form\Page');
        $form->shouldReceive(array('populate' => $form, 'isValid' => false));

        $pageService = new Page($em);
        $pageService->setDefaultForm($form);
        $pageService->addPage(array());
    }

    /**
     * @dataProvider addProvider
     */
    public function testEditPage($data, $layout, $form, $route, $page, $pageRoute)
    {
        $newData = array(
            'title' => 'newTitle',
            'pageRoute' => 'newRoute',
            'layout' => '1col',
            'description' => 'newDescription',
        );

        $newLayout = new \Core\Model\Layout($data['layout']);
        $newRoute = new \Core\Model\Route($data['pageRoute']);

        $routeService = m::mock();
        $routeService->shouldReceive('create')->andReturn($newRoute);

        $form = m::mock();
        $form->shouldReceive('populate')->andReturn($form);
        $form->shouldReceive('isValid')->andReturn(true);
        $form->shouldReceive('getValues')->andReturn($newData);

        $em = m::mock('Mock\EntityManager');
        $em->shouldReceive('remove');
        $em->shouldReceive('getReference')->andReturn($newLayout);
        $em->shouldReceive('persist')->with(m::type('Core\Model\Route'));
        $em->shouldReceive('persist')->with(m::type('Core\Model\PageRoute'));
        $em->shouldReceive('remove')->with($route);
        $em->shouldReceive('remove')->with($pageRoute);
        $em->shouldReceive('flush')->once();

        $pageService = new Page($em);
        $pageService->setDefaultForm($form);
        $pageService->setRouteService($routeService);

        $editedPage = $pageService->editPage($page, $newData);

        $this->assertEquals($newData['title'], $editedPage->title);
        $this->assertEquals($newData['description'], $editedPage->description);
        $this->assertEquals($newLayout, $editedPage->layout);
        $this->assertEquals($newRoute, $editedPage->pageRoute->route);
        $this->assertNotSame($pageRoute, $editedPage->pageRoute);
    }

    /**
     * @dataProvider addProvider
     * @expectedException Core\Exception\FormException
     */
    public function testEditPageException($data, $layout, $form, $route, $page, $pageRoute)
    {
        $em = m::mock('Mock\EntityManager');

        $form = m::mock('Core\Form\Page');
        $form->shouldReceive(array('populate' => $form, 'isValid' => false));

        $pageService = new Page($em);
        $pageService->setDefaultForm($form);
        $pageService->editPage($page, $data);
    }

    /**
     * @dataProvider addProvider
     */
    public function testDeletePage($data, $layout, $form, $route, $page, $pageRoute)
    {
        $l1 = new \Core\Model\Layout\Location('location');
        $v1 = new \Mock\View();
        $d1 = new \Core\Model\Content\Text('title', 'content', false);
        $b1 = new \Core\Model\Block\StaticBlock($d1, $v1);
        $page->setDependentContent($d1);
        $page->addBlock($b1, $l1);

        $em = m::mock('Mock\EntityManager');
        $em->shouldReceive('remove')->with($route)->once();
        $em->shouldReceive('remove')->with($d1);
        $em->shouldReceive('remove')->with($page);
        $em->shouldReceive('getRepository')->andReturn(array($b1));
        $em->shouldReceive('flush');

        $pageService = new Page($em);
        $pageService->deletePage($page, $data);

        $route->setSysname('sysname');
        $pageService->deletePage($page, $data);
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

        $form = new \Mock\Form\Page($data, true);

        $route = new \Core\Model\Route($data['pageRoute']);

        $page = new \Core\Model\Page($layout);
        $pageRoute = $route->routeTo($page);
        $page->setData(array('title' => $data['title'], 'description' => $data['description']));
        $page->setPageRoute($pageRoute);

        return array(
            array($data, $layout, $form, $route, $page, $pageRoute)
        );
    }

    /**
     * @expectedException Exception
     */
    public function testGetDefaultForm()
    {
        $em = m::mock('Mock\EntityManager');
        
        $pageService = new Page($em);

        $pageService->getDefaultForm();
    }

    public function testAddBlock()
    {
        $em = m::mock('Doctrine\ORM\EntityManager');

        $pageService = new \Core\Service\Page($em);

        $layout = new \Core\Model\Layout('1col');
        $page = new \Core\Model\Page($layout);

        $view = new \Mock\View();
        $content = new \Core\Model\Content\Text('title', 'content', false);
        $block = new \Core\Model\Block\StaticBlock($content, $view);

        $location = new \Core\Model\Layout\Location('main');

        $em->shouldReceive('persist')->with($block)->ordered();
        $em->shouldReceive('flush')->ordered();
        
        $pageService->addBlock($page, $block, $location);
    }

    public function testUpdate()
    {
        $em = m::mock('Doctrine\ORM\EntityManager');
        $em->shouldReceive('flush')->once();

        $layout = new \Core\Model\Layout('1col');
        $page = m::mock(new \Core\Model\Page($layout));
        $page->shouldReceive('getBlock')->with(1)->once()->andReturn('one');
        $page->shouldReceive('getBlock')->with(2)->once()->andReturn('two');
        $page->shouldReceive('getBlock')->with(3)->once()->andReturn('three');

        $b1 = new \stdClass();
        $b1->id = 1;
        $b1->location = 'main';
        $b1->weight = 2;

        $b2 = new \stdClass();
        $b2->id = 2;
        $b2->location = 'left';
        $b2->weight = 1;

        $b3 = new \stdClass();
        $b3->id = 3;
        $b3->location = 'main';
        $b3->weight = 1;

        $pageObject = new \stdClass();
        $pageObject->layout = new \stdClass();
        $pageObject->layout->locations = array(array($b1, $b2, $b3));

        $blockService = m::mock();
        $blockService->shouldReceive('update')->with('one', $b1);
        $blockService->shouldReceive('update')->with('two', $b2);
        $blockService->shouldReceive('update')->with('three', $b3);

        $pageService = m::mock(new \Core\Service\Page($em), array(m::BLOCKS => array('update', 'setEntityManager')));
        $pageService->shouldReceive('getBlockService')->andReturn($blockService);
        $pageService->setEntityManager($em);

        $pageService->update($page, $pageObject);
    }
}