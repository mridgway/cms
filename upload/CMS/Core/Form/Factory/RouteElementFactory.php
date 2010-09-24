<?php

namespace Core\Form\Factory;

/**
 * Factory for the route form elements
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class RouteElementFactory
{
    public static function getIdElement()
    {
        return new \Zend_Form_Element_Hidden('id');
    }

    public static function getTemplateElement()
    {
        $template = new \Core\Form\Element('template');
        $template->setLabel('Route:');
        $template->setAllowEmpty(false);
        $template->addValidator(new \Core\Validator\UniqueRoute($id->getName()));

        return $template;
    }

    public static function getIsDirectElement()
    {
        $isDirect = new \Zend_Form_Element_Checkbox('isDirect');
        $isDirect->setLabel('Direct:');

        return $isDirect;
    }
}