<?php

namespace Core\Form\Factory;

/**
 * Factory for the page form elements
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class PageElementFactory
{

    public static function getIdElement()
    {
        return new \Zend_Form_Element_Hidden('id');
    }

    public static function getTitleElement()
    {
        $title = new \Core\Form\Element\Text('title');
        $title->setLabel('Title:');
        $title->setAllowEmpty(false);
        $title->addValidator(new \Zend_Validate_StringLength(0, 255));

        return $title;
    }

    public static function getDescriptionElement()
    {
        $description = new \Core\Form\Element\Textarea('description');
        $description->setLabel('Description:');
        $description->setAllowEmpty(true);
        $description->addValidator(new \Zend_Validate_StringLength(0, 500));

        return $description;
    }

    public static function getLayoutElement()
    {
        $layoutSelect = new \Core\Form\Element\Radio('layout');
        $layouts = \Zend_Registry::get('doctrine')->getRepository('Core\Model\Layout')->findAll();
        foreach ($layouts AS $layout) {
            $layoutSelect->addMultiOption($layout->getSysname(), $layout->getTitle());
        }

        return $layoutSelect;
    }

    public static function getPageRouteElement()
    {
        $pageRoute = new \Core\Form\Element\Text('pageRoute');
        $pageRoute->setLabel('Url:');
        $pageRoute->setAllowEmpty(true);
        $pageRoute->addValidator(new \Zend_Validate_StringLength(0, 255));
        $pageRoute->setAttrib('disabled', true);

        return $pageRoute;
    }
}