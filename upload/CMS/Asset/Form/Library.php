<?php
/**
 * Modo CMS
 */

namespace Asset\Form;

/**
 * Form for Assets from a URL
 *
 * @category   Form
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Library.php 297 2010-05-12 13:34:56Z mike $
 */
class Library extends \Core\Form\AbstractForm
{
    public static $sorts = array (
            0 => array(
                'text'  => 'Alphabetical: A to Z',
                'field' => 'name',
                'order' => 'ASC'
            ),
            1 => array(
                'text'  => 'Alphabetical: Z to A',
                'field' => 'name',
                'order' => 'DESC'
            ),
            2 => array(
                'text'  => 'Date: Newest to Oldest',
                'field' => 'uploadDate',
                'order' => 'DESC'
            ),
            3 => array(
                'text'  => 'Date: Oldest to Newest',
                'field' => 'uploadDate',
                'order' => 'ASC'
            )
        );

    public function init()
    {
        $this->setAction('/direct/asset/manager/list/');
        $this->setName('filter');

        $search = new \Core\Form\Element\Text('search');
        $search->setLabel('Search');

        $type = new \Core\Form\Element\Select('type');
        $type->setLabel('Type');
        $type->addMultiOption('all', 'All Types');
        $type->setValue('all');

        $sort = new \Core\Form\Element\Select('sort');
        $sort->setLabel('Sort');
        foreach(self::$sorts AS $key => $sortType) {
            $sort->addMultiOption($key, $sortType['text']);
        }
        $sort->setValue(2);

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setLabel('Search');
        
        $page = new \Core\Form\Element\Hidden('page');
        $page->setValue(1);

        $this->addElements(array($search, $type, $sort, $submit, $page));
    }

    public function setTypes(array $types)
    {
        $element = $this->getElement('type');
        foreach ($types AS $type) {
            $element->addMultiOption($type->sysname, $type->title);
        }
    }
}