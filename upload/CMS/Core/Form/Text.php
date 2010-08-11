<?php
/**
 * Modo CMS
 */

namespace Core\Form;

/**
 * Form for Tex
 *
 * @category   Form
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Text.php 297 2010-05-12 13:34:56Z mike $
 */
class Text extends \Modo\Form\AbstractForm
{
    public function init()
    {

        $id = new \Modo\Form\Element\Hidden('id');
        $this->addElement($id);

        $title = new \Modo\Form\Element\Text('title');
        $title->setLabel('Title:');
        $title->setAllowEmpty(false);
        $title->addValidator(new \Zend_Validate_StringLength(0, 100));
        $this->addElement($title);

        $content = new \Modo\Form\Element\Textarea('content');
        $content->setLabel('Content:');
        $content->setAllowEmpty(false);
        $content->addValidator(new \Zend_Validate_StringLength(0, 10000));
        $this->addElement($content);

        $submit = new \Modo\Form\Element\Submit('submit');
        $submit->setValue('Submit');
        $this->addElement($submit);
    }
}