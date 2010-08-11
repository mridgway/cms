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
 * @version    $Id: Upload.php 297 2010-05-12 13:34:56Z mike $
 */
class Upload extends \Modo\Form\AbstractForm
{
    public function init()
    {
        $this->setAction('/direct/asset/manager/upload/');
        $this->setName('upload');
        $this->setAttrib('enctype', 'multipart/form-data');

        $file = new \Modo\Form\Element\File('file');
        $file->setLabel('File');

        $submit = new \Modo\Form\Element\Submit('submit');
        $submit->setLabel('Upload');

        $this->addElements(array($file, $submit));
    }
}