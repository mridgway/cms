<?php

namespace Core\Form;

/**
 * Form for route model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Route extends \Core\Form\AbstractForm
{
    public function init()
    {

        $id = new \Zend_Form_Element_Hidden('id');

        $template = new \Core\Form\Element('template');
        $template->setLabel('Route:');
        $template->setAllowEmpty(false);
        $template->addValidator(new \Core\Validator\UniqueRoute($id->getName()));

        $isDirect = new \Zend_Form_Element_Checkbox('isDirect');
        $isDirect->setLabel('Direct:');

        $submit = new \Zend_Form_Element_Submit('submit');
        $submit->setValue('Submit');

        $this->addElements(array($id, $template, $isDirect, $submit));
    }
}