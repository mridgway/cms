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
        $author = new \Core\Form\Element\Hidden('author');

        return $author;
    }
    
    public static function getAuthorNameElement()
    {
        $author = new \Core\Form\Element\AutocompleteHidden('authorName');
        $author->setLabel('Author');
        $author->setDescription('Type a name and select from the autocomplete dropdown to select a site user. If the author is not a user delete the contents of the field and type a name.');
        $author->setJQueryParam('source', '/direct/content/author');
        $author->setJQueryParam('hiddenFieldName', 'author');

        return $author;
    }

    public static function getCreationDateElement()
    {
        $creationDate = new \Core\Form\Element\DatePicker('creationDate');
        $creationDate->setLabel('Creation Date:');
        $creationDate->setJQueryParam('dateFormat', 'mm-dd-yy');

        return $creationDate;
    }

    public static function getModificationDateElement()
    {
        $modificationDate = new \Core\Form\Element\DatePicker('modificationDate');
        $modificationDate->setLabel('Modification Date:');
        $modificationDate->setJQueryParam('dateFormat', 'mm-dd-yy');

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