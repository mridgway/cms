<?php
namespace Core\Model;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for PageRoute.
 * Generated by PHPUnit on 2010-01-14 at 11:38:41.
 */
class PageRouteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PageRoute
     */
    protected $pageRoute;

    protected $params;
    protected $route;
    protected $page;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->route = new Route('test/:test1/:test2/:test3/');
        $this->params = array(
            'test1' => 1,
            'test2' => 2,
            'test3' => 3,
        );
        $this->page = new Page(new Layout('test'));
        $this->pageRoute = new PageRoute($this->route, $this->page, $this->params);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testConstructor()
    {
        $this->assertEquals($this->route, $this->pageRoute->getRoute());
        $this->assertEquals($this->params, $this->pageRoute->getParams());
        $this->assertEquals($this->page, $this->pageRoute->getPage());
    }

    public function testSetParam()
    {
        $this->pageRoute->setParam('test1', 3);
        $this->assertEquals(3, $this->pageRoute->getParam('test1'));

        $this->setExpectedException('Core\Model\Exception');
        $this->pageRoute->setParam('test4', 4);
    }

    public function testSetParams()
    {
        $this->pageRoute->setParams();
        $params = $this->pageRoute->getParams();
        $this->assertTrue(empty($params));
    }

    public function testGetURL()
    {
        $this->pageRoute->setRoute(new Route('blog/add/'));
        $this->assertEquals('/blog/add/', $this->pageRoute->getURL());

        $route = new Route('blog/article/:id/');
        $this->pageRoute->setRoute($route);
        $this->pageRoute->setParam('id', 1);
        $this->assertEquals('/blog/article/1/', $this->pageRoute->getURL());
    }

    public function testSetRedirect()
    {
        $this->pageRoute->setRedirect(true);
        $this->assertTrue($this->pageRoute->getRedirect());

        $this->setExpectedException('Core\Model\Exception');
        $this->pageRoute->setRedirect('awesome');
    }

    public function testSetPage()
    {
        $page = new Page(new Layout('test2'));
        $this->pageRoute->setPage($page);
        $this->assertEquals($page, $this->pageRoute->getPage());
        $this->assertEquals($this->pageRoute, $page->getPageRoute());

        $newPageRoute = new PageRoute(new Route('test2'), $page);
        $this->assertFalse($newPageRoute->getRedirect());
    }

    public function testGetURLOnEncodedParams()
    {
        $url = $this->pageRoute->getURL();
        $this->assertEquals('/test/1/2/3/', $this->pageRoute->getURL());
    }
}
?>
