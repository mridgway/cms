<?php
namespace Core\Model\Block\Dynamic\Form;

require_once 'PHPUnit/Framework.php';

/**
 * Test class for AbstractForm.
 */
class AbstractFormTest extends \PHPUnit_Framework_TestCase
{
    protected $zendForm;
    protected $form;

    protected function setUp()
    {
        $view = new \Core\Model\View('module', 'type', 'sysname');
        $this->form = new \Mock\Block\Dynamic\Form\NonAbstractForm($view);
        $this->zendForm = new \Zend_Form();
        $this->form->setForm($this->zendForm);
    }

    protected function tearDown()
    {
    }

    public function testSuccess()
    {
        // phpunit can not test for headers
    }

    public function testFailure()
    {
        $this->form->failure('message1');
        $errors = $this->form->_form->getErrorMessages();
        $this->assertEquals('message1', $errors[0]);

        $a = array('message2', 'message3');
        $this->form->failure($a);
        \array_unshift($a, 'message1');
        $errors = $this->form->_form->getErrorMessages();
        $this->assertEquals($a, $errors);
    }

    public function testGetForm()
    {
        $this->assertEquals($this->zendForm, $this->form->getForm());
    }

    public function testRender()
    {
        // testing a render() function does not work
    }
}