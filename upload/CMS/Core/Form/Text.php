<?php

namespace Core\Form;

/**
 * Form for text content type
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Text extends \Core\Form\AbstractForm
{
    public function init()
    {
        $this->addElements(array(
            Factory\TextElementFactory::getIdElement(),
            Factory\TextElementFactory::getTitleElement(),
            Factory\TextElementFactory::getContentElement()
        ));

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setValue('Submit');
        $this->addElement($submit);
    }
}