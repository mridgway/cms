<?php
namespace Core\Model\Content;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for Placeholder.
 * Generated by PHPUnit on 2010-01-28 at 16:15:25.
 */
class PlaceholderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Placeholder
     */
    protected $placeholder;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->placeholder = new Placeholder('test', 'Core\Model\Content', 'Description');
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
        $this->assertEquals('test', $this->placeholder->getSysname());
        $this->assertEquals('Core\Model\Content', $this->placeholder->getContentType());
        $this->assertEquals('Description', $this->placeholder->getDescription());
    }

    public function testSetSysname()
    {
        $this->placeholder->setSysname('test2');
        $this->assertEquals('test2', $this->placeholder->getSysname());
    }

    public function testSetSynameNull()
    {
        $this->setExpectedException('Modo\Model\Exception');
        $this->placeholder->setSysname(null);
    }

    public function testSetContentType()
    {
        $this->placeholder->setContentType('Core\Model\Content');
        $this->assertEquals('Core\Model\Content', $this->placeholder->getContentType());
    }

    public function testSetContentTypeInvalid()
    {
        $this->setExpectedException('Modo\Model\Exception');
        $this->placeholder->setContentType('test');
    }

    public function testSetContentTypeEmpty()
    {
        $this->setExpectedException('Modo\Model\Exception');
        $this->placeholder->setContentType('');
    }

    public function testSetDescription()
    {
        $this->placeholder->setDescription('test');
        $this->assertEquals('test', $this->placeholder->getDescription());

        $this->setExpectedException('Modo\Model\Exception');
        $this->placeholder->setDescription(null);
    }
}
?>
