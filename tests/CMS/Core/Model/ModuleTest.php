<?php
namespace Core\Model;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for Module.
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Core\Model\Module
     */
    protected $module;

    protected function setUp()
    {
        $this->module = new Module('default');
    }

    protected function tearDown()
    {
    }

    public function testSetSysname()
    {
        $this->module->setSysname('newName');
        $this->assertEquals('newName', $this->module->getSysname());

        $this->setExpectedException('Core\Model\Exception');
        $n = '';
        for($i = 0; $i < 11; $i++)
        {
            $n .= 'words';
        }
        $this->module->setSysname($n);
    }

    public function testSetTitle()
    {
        $this->module->setTitle('newTitle');
        $this->assertEquals('newTitle', $this->module->getTitle());

        $this->setExpectedException('Core\Model\Exception');
        $n = '';
        for($i = 0; $i < 21; $i++)
        {
            $n .= 'words';
        }
        $this->module->setTitle($n);
    }

    public function testAddBlock()
    {
        $block = new Module\Block('title', 'discriminator', 'Core\Model\Block\StaticBlock');
        $this->module->addBlock($block);
        $this->assertEquals($block, $this->module->getBlockType('discriminator'));
    }

    public function testAddContent()
    {
        $content = new Module\Content('title', 'discriminator', 'Core\Model\Content\Text');
        $this->module->addContent($content);
        $this->assertEquals($content, $this->module->getContentType('discriminator'));
    }

    public function testGetBlockDiscriminatorMap()
    {
        $block1 = new Module\Block('title', 'discriminator1', 'Core\Model\Block\StaticBlock');
        $block2 = new Module\Block('title', 'discriminator2', 'Core\Model\Block\StaticBlock');
        $a = array($block1->discriminator => $block1->class, $block2->discriminator => $block2->class);

        $this->module->addBlock($block1);
        $this->module->addBlock($block2);

        $this->assertEquals($a, $this->module->getBlockDiscriminatorMap());
    }

    public function testGetContentDiscriminatorMap()
    {
        $content1 = new Module\Content('title', 'discriminator1', 'Core\Model\Content\Text');
        $content2 = new Module\Content('title', 'discriminator2', 'Core\Model\Content\Text');
        $a = array($content1->discriminator => $content1->class, $content2->discriminator => $content2->class);

        $this->module->addContent($content1);
        $this->module->addContent($content2);

        $this->assertEquals($a, $this->module->getContentDiscriminatorMap());
    }

    public function testGetResourceId()
    {
        $this->assertEquals('default', $this->module->getResourceId());
    }
}