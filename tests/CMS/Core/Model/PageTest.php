<?php
namespace Core\Model;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for Page.
 * Generated by PHPUnit on 2010-01-28 at 12:04:18.
 */
class PageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Page
     */
    protected $page;

    protected $params;
    protected $route;
    protected $pageRoute;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->route = new Route('test/:test1/:test2/:test3');
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

    public function testGetURL()
    {
        $this->assertEquals('/test/1/2/3/', $this->page->getURL());

        $this->page->primaryPageRoute = null;
        $this->assertNull($this->page->getURL());
    }

    public function testSetPrimaryPageRoute()
    {
        $this->page->setPrimaryPageRoute($this->pageRoute);
        $this->assertEquals($this->pageRoute, $this->page->getPrimaryPageRoute());
    }

    public function testSetTemplate()
    {
        $template = new Template('template', $this->page->getLayout());
        $this->page->setTemplate($template);
        $this->assertEquals($template, $this->page->getTemplate());
    }
}
?>
