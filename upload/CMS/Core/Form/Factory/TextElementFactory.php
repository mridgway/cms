<?php

namespace Core\Form\Factory;

/**
 * Factory for the text form elements
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class TextElementFactory
{
    public static function getIdElement()
    {
        return new \Core\Form\Element\Hidden('id');
    }

    public static function getTitleElement()
    {
        $title = new \Core\Form\Element\Text('title');
        $title->setLabel('Title:');
        $title->setDescription('Add a title to make this a shared block');
        $title->setAllowEmpty(true);
        $title->addValidator(new \Zend_Validate_StringLength(0, 100));
        $title->addFilter(new \Zend_Filter_Null());

        return $title;
    }

    public static function getContentElement()
    {
        $content = new \Core\Form\Element\Wysiwyg('content');
        $content->setLabel('Content:');
        $content->setAllowEmpty(false);
        $content->addValidator(new \Zend_Validate_StringLength(0, 10000));

        return $content;
    }
}