<?php

namespace Core\Form\Factory;

/**
 * Factory for the route form elements
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class RouteElementFactory
{
    public static function getIdElement()
    {
        return new \Core\Form\Element\Hidden('id');
    }

    public static function getTemplateElement()
    {
        $template = new \Core\Form\Element\Text('template');
        $template->setLabel('Route:');
        $template->setAllowEmpty(false);
        $template->addValidator(new \Core\Validator\UniqueRoute());

        return $template;
    }

    public static function getRouteVariableElement($name, $route)
    {
        $routeVariable = new \Core\Form\Element\Text('routeVar:' . $name);
        $routeVariable->setLabel('Sysname:');
        $routeVariable->setAllowEmpty(false);
        $routeVariable->addValidator(new \Core\Validator\UniquePageRoute($name, $route));

        return $routeVariable;
    }

    public static function getIsDirectElement()
    {
        $isDirect = new \Zend_Form_Element_Checkbox('isDirect');
        $isDirect->setLabel('Direct:');

        return $isDirect;
    }
}