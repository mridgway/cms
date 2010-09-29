<?php
namespace Core\Model\Module;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for Content.
 */
class ContentTest extends \PHPUnit_Framework_TestCase
{
    protected $content;

    protected function setUp()
    {
        $this->content = new Content('title', 'discriminator', 'Core\Model\Content\Text');
    }

    protected function tearDown()
    {
    }

    public function testSetTitle()
    {
        $this->content->setTitle('newTitle');
        $this->assertEquals('newTitle', $this->content->getTitle());

        $this->setExpectedException('Core\Model\Exception');
        $n = '';
        for($i = 0; $i < 7; $i++)
        {
            $n .= '0123456789';
        }
        $this->content->setTitle($n);
    }

    public function testSetDiscriminator()
    {
        $this->content->setDiscriminator('newTitle');
        $this->assertEquals('newTitle', $this->content->getDiscriminator());

        $this->setExpectedException('Core\Model\Exception');
        $n = '';
        for($i = 0; $i < 7; $i++)
        {
            $n .= '0123456789';
        }
        $this->content->setDiscriminator($n);
    }

    public function testSetClass()
    {
        $class = 'Core\Model\Content\Text';
        $this->content->setClass($class);
        $this->assertEquals($class, $this->content->class);

        $this->setExpectedException('Core\Model\Exception');
        $this->content->setClass('class');
    }

    public function testSetController()
    {
        $class = 'Core\Controller\Content\Text';
        $this->content->setController($class);
        $this->assertEquals($class, $this->content->controller);

        $this->setExpectedException('Core\Model\Exception');
        $this->content->setController('class');
    }

    public function testGetResourceId()
    {
        $this->content->module = new \Core\Model\Module('sysname');
        $this->assertEquals('sysname.Content.discriminator', $this->content->getResourceId());
    }
}