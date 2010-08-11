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
 * @version    $Id: Asset.php 297 2010-05-12 13:34:56Z mike $
 */
class Asset extends \Modo\Form\AbstractForm
{
    public function init()
    {
        $this->setAction('/direct/asset/asset/edit/');
        $this->setName('asset');

        $id = new \Modo\Form\Element\Hidden('id');
        $this->addElement($id);

        $name = new \Modo\Form\Element\Text('name');
        $name->setLabel('Name');
        $name->setAllowEmpty(false);
        $name->addValidator(new \Zend_Validate_StringLength(0, 150));
        $this->addElement($name);

        $caption = new \Modo\Form\Element\Textarea('caption');
        $caption->setLabel('Caption');
        $caption->setAllowEmpty(false);
        $caption->addValidator(new \Zend_Validate_StringLength(0, 255));
        $this->addElement($caption);

        $submit = new \Modo\Form\Element\Submit('submit');
        $submit->setLabel('Save');
        $this->addElement($submit);

    }
}