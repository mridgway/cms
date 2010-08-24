<?php

namespace Blog\Form;

/**
 * Form for blog articles
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Article extends \Core\Form\AbstractForm
{
    public function init()
    {
        $id = new \Core\Form\Element\Hidden('id');
        $this->addElement($id);

        $title = new \Core\Form\Element\Text('title');
        $title->setLabel('Title:');
        $title->setAllowEmpty(false);
        $title->addValidator(new \Zend_Validate_StringLength(3, 150));
        $this->addElement($title);

        $content = new \Core\Form\Element\Textarea('content');
        $content->setLabel('Content:');
        $content->setAllowEmpty(false);
        $content->addValidator(new \Zend_Validate_StringLength(3, 10000));
        $this->addElement($content);

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setValue('Submit');
        $this->addElement($submit);
    }
}