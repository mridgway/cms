<?php

namespace Blog\Form\Factory;

/**
 * Factory for the blog article form elements
 *
 * @package     CMS
 * @subpackage  Blog
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class ArticleElementFactory
{
    public static function getIdElement()
    {
        return new \Core\Form\Element\Hidden('id');
    }

    public static function getTitleElement()
    {
        $title = new \Core\Form\Element\Text('title');
        $title->setLabel('Title:');
        $title->setAllowEmpty(false);
        $title->addValidator(new \Zend_Validate_StringLength(3, 150));

        return $title;
    }

    public static function getContentElement()
    {
        $content = new \Core\Form\Element\Textarea('content');
        $content->setLabel('Content:');
        $content->setAllowEmpty(false);
        $content->addValidator(new \Zend_Validate_StringLength(3, 10000));

        return $content;
    }

    public static function getCategoryElement()
    {
        $category = \Taxonomy\Form\Factory\TermElementFactory::termSelectElement('blog');
        $category->setLabel('Category:');
        $category->setAllowEmpty(false);

        return $category;
    }

    public static function getAssetElement()
    {
        $asset = new \Asset\Form\Element\Asset('asset');
        $asset->setLabel('Asset:');
        $asset->setGroup('tmp');

        return $asset;
    }
}