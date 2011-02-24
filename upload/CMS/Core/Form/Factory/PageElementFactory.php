<?php

namespace Core\Form\Factory;

/**
 * Factory for the page form elements
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class PageElementFactory
{

    public static function getIdElement()
    {
        return new \Core\Form\Element\Hidden('id');
    }

    public static function getTitleElement()
    {
        $title = new \Core\Form\Element\Text('title');
        $title->setLabel('Title:');
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
        $layoutSelect->setRequired(true);

        return $layoutSelect;
    }

    public static function getPageRouteElement()
    {
        $pageRoute = new \Core\Form\Element\Text('pageRoute');
        $pageRoute->setLabel('Url:');
        $pageRoute->setAllowEmpty(false);
        $pageRoute->setFilters(array(new \Core\Filter\Url));
        $pageRoute->addValidator(new \Zend_Validate_StringLength(0, 255));
        $pageRoute->addValidator(new \Core\Validator\Route());

        return $pageRoute;
    }
}