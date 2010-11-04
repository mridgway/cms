<?php

namespace Core\Form\Factory;

/**
 * Factory for Address form elements
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Address
{
    public static function getIdElement()
    {
        return new \Core\Form\Element\Hidden('id');
    }

    public static function getAddressLine1Element()
    {
        $element = new \Core\Form\Element\Text('addressLine1');
        $element->setLabel('Address Line 1');
        $element->setAllowEmpty(false);
        $element->addValidator(new \Zend_Validate_StringLength(0, 100));

        return $element;
    }

    public static function getAddressLine2Element()
    {
        $element = new \Core\Form\Element\Text('addressLine2');
        $element->setLabel('Address Line 2');
        $element->setAllowEmpty(false);
        $element->addValidator(new \Zend_Validate_StringLength(0, 100));

        return $element;
    }

    public static function getCityElement()
    {
        $element = new \Core\Form\Element\Text('city');
        $element->setLabel('City');
        $element->setAllowEmpty(false);
        $element->addValidator(new \Zend_Validate_StringLength(0, 100));

        return $element;
    }

    public static function getStateElement()
    {
        $element = new \Core\Form\Element\Text('state');
        $element->setLabel('State');
        $element->setAllowEmpty(false);
        $element->addValidator(new \Zend_Validate_StringLength(0, 100));

        return $element;
    }

    public static function getZipElement()
    {
        $element = new \Core\Form\Element\Text('zip');
        $element->setLabel('Zip');
        $element->setAllowEmpty(false);
        $element->addValidator(new \Zend_Validate_StringLength(5, 5));
        $element->addValidator(new \Zend_Validate_Digits());

        return $element;
    }
}