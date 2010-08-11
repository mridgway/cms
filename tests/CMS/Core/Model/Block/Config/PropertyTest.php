<?php
namespace Core\Model\Block\Config;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for Property.
 * Generated by PHPUnit on 2010-01-28 at 17:04:45.
 */
class PropertyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Property
     */
    protected $property;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->property = new Property('test');
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
        $this->assertEquals('test', $this->property->getName());
        $this->assertNull($this->property->getDefault());
        $this->assertFalse($this->property->getRequired());
        $this->assertFalse($this->property->getInheritable());
        $this->assertEquals('Core\Model\Block', $this->property->getInheritableFrom());
    }

    public function testSetName()
    {
        $this->setExpectedException('Modo\Model\Exception');
        $this->property->setName('');
    }

    public function testSetRequired()
    {
        $this->setExpectedException('Modo\Model\Exception');
        $this->property->setRequired('test');
    }

    public function testSetInheritable()
    {
        $this->setExpectedException('Modo\Model\Exception');
        $this->property->setInheritable('test');
    }

    public function testSetInheritableFrom()
    {
        $this->property->setInheritableFrom('Core\Model\Content');
        $this->assertEquals('Core\Model\Content', $this->property->getInheritableFrom());

        $this->setExpectedException('Modo\Model\Exception');
        $this->property->setInheritableFrom('test');
    }
}
?>
