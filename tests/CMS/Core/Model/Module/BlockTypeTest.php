<?php
namespace Core\Model\Module;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for Block.
 */
class BlockTypeTest extends \PHPUnit_Framework_TestCase
{
    protected $block;

    protected function setUp()
    {
        $this->block = new BlockType('title', 'discriminator', 'Core\Model\Block\StaticBlock');
    }

    protected function tearDown()
    {
    }

    public function testSetTitle()
    {
        $this->block->setTitle('newTitle');
        $this->assertEquals('newTitle', $this->block->getTitle());

        $this->setExpectedException('Core\Model\Exception');
        $n = '';
        for($i = 0; $i < 7; $i++)
        {
            $n .= '0123456789';
        }
        $this->block->setTitle($n);
    }

    public function testSetDiscriminator()
    {
        $this->block->setDiscriminator('newTitle');
        $this->assertEquals('newTitle', $this->block->getDiscriminator());

        $this->setExpectedException('Core\Model\Exception');
        $n = '';
        for($i = 0; $i < 7; $i++)
        {
            $n .= '0123456789';
        }
        $this->block->setDiscriminator($n);
    }

    public function testSetClass()
    {
        $class = 'Core\Model\Block\DynamicBlock';
        $this->block->setClass($class);
        $this->assertEquals($class, $this->block->class);

        $this->setExpectedException('Core\Model\Exception');
        $this->block->setClass('class');
    }

    public function testGetResourceId()
    {
        $this->block->module = new \Core\Model\Module('sysname');
        $this->assertEquals('sysname.Block.discriminator', $this->block->getResourceId());
    }
}