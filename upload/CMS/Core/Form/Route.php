<?php
/**
 * Modo CMS
 */

namespace Core\Form;

/**
 * Form for Routes
 *
 * @category   Route
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Route.php 297 2010-05-12 13:34:56Z mike $
 */
class Route extends \Modo\Form\AbstractForm
{
    public function init()
    {

        $id = new \Zend_Form_Element_Hidden('id');

        $template = new \Modo\Form\Element('template');
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