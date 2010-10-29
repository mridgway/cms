<?php

namespace Core\Form\Factory;

/**
 * Factory for content form elements
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class ContentElementFactory
{
    public static function getIdElement()
    {
        return new \Core\Form\Element\Hidden('id');
    }
    
    public static function getAuthorElement()
    {
        $author = new \Core\Form\Element\Autocomplete('author');

        return $author;
    }
    
    public static function getAuthorNameElement()
    {
        $authorName = new \Core\Form\Element\Text('authorName');
        $authorName->setLabel('Author Name:');

        return $authorName;
    }

    public static function getCreationDateElement()
    {
        $creationDate = new \ZendX_JQuery_Form_Element_DatePicker('creationDate');
        $creationDate->setLabel('Creation Date:');

        return $creationDate;
    }

    public static function getModificationDateElement()
    {
        $modificationDate = new \ZendX_JQuery_Form_Element_DatePicker('modificationDate');
        $modificationDate->setLabel('Modification Date:');

        return $modificationDate;
    }

    public static function getTagsElement()
    {
        $tags = \Taxonomy\Form\Factory\TermElementFactory::termMultiTagElement('contentTags');
        $tags->setLabel('Tags:');

        return $tags;
    }

    /**
     * @todo implement this
     */
    public static function getStatusElement($workflow)
    {
    }
}