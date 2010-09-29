<?php

namespace Core\Model\Frontend;

class PageInfoTest extends \Zend_Test_PHPUnit_ControllerTestCase
{
    protected $pageInfo;
    protected $route;
    protected $params;
    protected $page;
    protected $location;
    protected $pageRoute;
    protected $block1;
    protected $application;

    public function setUp()
    {
        $this->bootstrap = new \Zend_Application(
                'testing',
                APPLICATION_PATH . '/application.ini'
        );


        $this->pageInfo = new PageInfo();
        $this->route = new \Core\Model\Route('test/:test1/:test2/:test3');
        $this->params = array(
            'test1' => 1,
            'test2' => 2,
            'test3' => 3,
        );

        $layout = new \Core\Model\Layout('test');
        $this->location = new \Core\Model\Layout\Location('test');
        $layout->addLocation($this->location);
        $this->page = new \Core\Model\Page($layout);

        $this->pageRoute = new \Core\Model\PageRoute($this->route, $this->page, $this->params);

        $this->block1 = new \Core\Model\Block\StaticBlock(new \Core\Model\Content\Text('testTitle', 'testContent'), new \Core\Model\View('Core', 'test', 'test'));
        $this->block1->setLocation($this->location);
        $this->block1->weight = 0;
        $this->page->addBlock($this->block1);

        parent::setUp();
    }
    
    public function testSuccess()
    {
        $em = \Zend_Registry::get('doctrine');
        $user = $em->getRepository('User\Model\User')->find(1);
        $session = new \User\Model\Session($user);
        $auth = \Core\Auth\Auth::getInstance();
        $auth->getStorage()->write($session);
        $acl = \Zend_Registry::get('acl');
        $acl->addRole($auth->getIdentity(), $auth->getIdentity()->getRoles());

        $frontend = $this->pageInfo->success($this->page);
        $frontend = $frontend->data[0];

        $this->assertEquals($this->page->id, $frontend->id);
        $frontend->actions;

        $frontendLocation = $frontend->locations[$this->location->sysname];
        $this->assertEquals($this->location->sysname, $frontendLocation->sysname);
        $frontendLocation->blocks;

        $frontendBlock = $frontendLocation->blocks[0];
        $this->assertEquals($this->block1->id, $frontendBlock->id);
        $this->assertEquals(\Core\Service\Manager::get('Core\Service\Block')->getVariables($this->block1), $frontendBlock->properties);
        $frontendBlock->actions;
    }

    public function testGetBlockActions()
    {
        $em = \Zend_Registry::get('doctrine');
        $user = $em->getRepository('User\Model\User')->find(1);
        $session = new \User\Model\Session($user);
        $auth = \Core\Auth\Auth::getInstance();
        $auth->getStorage()->write($session);
        $acl = \Zend_Registry::get('acl');
        $acl->addRole($auth->getIdentity(), $auth->getIdentity()->getRoles());

        $this->assertEquals(3, count($this->pageInfo->_getBlockActions($this->block1)));
    }

    public function testGetPageActions()
    {
        $em = \Zend_Registry::get('doctrine');
        $user = $em->getRepository('User\Model\User')->find(1);
        $session = new \User\Model\Session($user);
        $auth = \Core\Auth\Auth::getInstance();
        $auth->getStorage()->write($session);
        $acl = \Zend_Registry::get('acl');
        $acl->addRole($auth->getIdentity(), $auth->getIdentity()->getRoles());

        $this->assertEquals(2, count($this->pageInfo->_getPageActions($this->page)));
    }
}