<?php
namespace Core\Service\IntegrationTests;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../../../bootstrap.php';

/**
 * Integration Test for Page Route Service.
 */
class PageRouteTest extends \CMS\CMSAbstractIntegrationTestCase
{
    protected $_uniqueName;
    protected $_page;
    protected $_route;

    public function setUp()
    {
        parent::setUp();

        $this->_sc->getService('doctrine')->beginTransaction();

        $em = $this->_sc->getService('doctrine');
        $sysname = \uniqid();
        $this->_uniqueName = $sysname;

        $layout = new \Core\Model\Layout($sysname);
        $em->persist($layout);

        $page = new \Core\Model\Page($layout);
        $em->persist($page);
        $this->_page = $page;

        $route = new \Core\Model\Route($sysname . '/:param', $sysname);
        $em->persist($route);
        $this->_route = $route;

        $pageRoute = $route->routeTo($page, array('param' => 1));
        $em->persist($pageRoute);

        $em->flush();
    }

    protected function tearDown()
    {
        $this->_sc->getService('doctrine')->rollback();
        \Core\Module\Registry::destroy();
    }

    public function testDoNotCreateIfNotUnique()
    {
        $data = array(
            'route' => array(
                'sysname' => $this->_uniqueName
            ),
            'page' => $this->_page->getId(),
            'params' => array(
                'param' => 1
            )
        );

        $this->setExpectedException('Core\Exception\ValidationException');
        $newPageRoute = $this->_sc->getService('pageRouteService')->create($data);
    }

    public function testCreate()
    {
        $data = array(
            'route' => array(
                'sysname' => $this->_uniqueName
            ),
            'page' => $this->_page->getId(),
            'params' => array(
                'param' => 2
            )
        );

        $newPageRoute = $this->_sc->getService('pageRouteService')->create($data);

        $this->assertEquals($this->_page, $newPageRoute->getPage());
        $this->assertEquals($this->_route, $newPageRoute->getRoute());
        $this->assertEquals($data['params'], $newPageRoute->getParams());
    }
}