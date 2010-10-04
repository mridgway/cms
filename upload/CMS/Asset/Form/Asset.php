<?php

namespace Asset\Form;

/**
 * Form for Assets from a URL
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Asset extends \Core\Form\AbstractForm
{
    public function init()
    {
        $this->setAction('/direct/asset/asset/edit/');
        $this->setName('asset');

        $this->addElements(array(
            Factory\AssetElementFactory::getIdElement(),
            Factory\AssetElementFactory::getNameElement(),
            Factory\AssetElementFactory::getCaptionElement()
        ));

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setLabel('Save');
        $this->addElement($submit);

    }
}