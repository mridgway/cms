<?php

namespace Asset\Form;

/**
 * Form for Assets from a URL
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Asset extends \Core\Form\AbstractForm
{
    public function init()
    {
        $this->setAction('/direct/asset/asset/edit/');
        $this->setName('asset');

        $id = new \Core\Form\Element\Hidden('id');
        $this->addElement($id);

        $name = new \Core\Form\Element\Text('name');
        $name->setLabel('Name');
        $name->setAllowEmpty(false);
        $name->addValidator(new \Zend_Validate_StringLength(0, 150));
        $this->addElement($name);

        $caption = new \Core\Form\Element\Textarea('caption');
        $caption->setLabel('Caption');
        $caption->setAllowEmpty(false);
        $caption->addValidator(new \Zend_Validate_StringLength(0, 255));
        $this->addElement($caption);

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setLabel('Save');
        $this->addElement($submit);

    }
}