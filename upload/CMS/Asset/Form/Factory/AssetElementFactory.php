<?php

namespace Asset\Form\Factory;

/**
 * Factory for the asset form elements
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class AssetElementFactory
{
    public static function getIdElement()
    {
        return new \Core\Form\Element\Hidden('id');
    }

    public static function getNameElement()
    {
        $name = new \Core\Form\Element\Text('name');
        $name->setLabel('Name');
        $name->setAllowEmpty(false);
        $name->addValidator(new \Zend_Validate_StringLength(0, 150));

        return $name;
    }

    public static function getCaptionElement()
    {
        $caption = new \Core\Form\Element\Textarea('caption');
        $caption->setLabel('Caption');
        $caption->setAllowEmpty(false);
        $caption->addValidator(new \Zend_Validate_StringLength(0, 255));

        return $caption;
    }

    public static function getUrlElement()
    {
        $element = new \Asset\Form\Element\Asset('url');
        $element->setLabel('Url:');
        $element->setGroup('tmp');

        return $element;
    }
}