<?php

namespace Core\Form;

/**
 * Form for the abstract page model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class AbstractPage extends \Core\Form\AbstractForm
{
    public function init()
    {
        $this->setName('abstractPageForm');

        $id = new \Zend_Form_Element_Hidden('id');
        $this->addElement($id);

        $title = new \Core\Form\Element\Text('title');
        $title->setLabel('Title:');
        $title->setAllowEmpty(false);
        $title->addValidator(new \Zend_Validate_StringLength(0, 255));
        $this->addElement($title);

        $description = new \Core\Form\Element\Textarea('description');
        $description->setLabel('Description:');
        $description->setAllowEmpty(true);
        $description->addValidator(new \Zend_Validate_StringLength(0, 500));
        $this->addElement($description);

        $layoutSelect = new \Core\Form\Element\Radio('layout');
        $layouts = \Zend_Registry::get('doctrine')->getRepository('Core\Model\Layout')->findAll();
        foreach ($layouts AS $layout) {
            $layoutSelect->addMultiOption($layout->getSysname(), $layout->getTitle());
        }
        $this->addElement($layoutSelect);

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setValue('Submit');
        $this->addElement($submit);
    }

    /**
     * {@inheritdoc}
     *
     * @param object $object
     */
    public function setObject($object)
    {
        parent::setObject($object);
        $this->getElement('layout')->setValue($object->getLayout()->getSysname());
    }
}