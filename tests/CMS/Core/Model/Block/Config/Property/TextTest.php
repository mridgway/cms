<?php
namespace Core\Model\Block\Config\Property;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for Text.
 */
class TextTest extends \PHPUnit_Framework_TestCase
{
    protected $text;

    protected function setUp()
    {
        $this->text = new Text('name', 'default');
    }

    protected function tearDown()
    {
    }

    public function testGetConfigurationField()
    {
        $field = new \Core\Form\Element\Text('name');
        $field->setLabel('name');
        $field->setValue('default');
        $field->setRequired(true);
        $this->assertEquals($field, $this->text->getConfigurationField());
    }
}