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
        $this->addElements(array(
            Factory\RouteElementFactory::getIdElement(),
            Factory\RouteElementFactory::getTemplateElement(),
            Factory\RouteElementFactory::getIsDirectElement()
        ));

        $submit = new \Zend_Form_Element_Submit('submit');
        $submit->setValue('Submit');
        $this->addElement($submit);
    }
}