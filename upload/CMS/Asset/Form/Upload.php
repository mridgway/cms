<?php

namespace Asset\Form;

/**
 * Form for uploading a file
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Upload extends \Core\Form\AbstractForm
{
    public function init()
    {
        $this->setAction('/direct/asset/manager/upload/');
        $this->setName('upload');
        $this->setAttrib('enctype', 'multipart/form-data');

        $file = new \Core\Form\Element\File('file');
        $file->setLabel('File');

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setLabel('Upload');

        $this->addElements(array($file, $submit));
    }
}