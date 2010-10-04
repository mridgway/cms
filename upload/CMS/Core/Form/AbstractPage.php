<?php

namespace Core\Form;

/**
 * Form for the abstract page model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class AbstractPage extends \Core\Form\AbstractForm
{
    public function init()
    {
        $this->setName('abstractPageForm');

        $this->addElements(array(
            Factory\PageElementFactory::getIdElement(),
            Factory\PageElementFactory::getTitleElement(),
            Factory\PageElementFactory::getDescriptionElement(),
            Factory\PageElementFactory::getLayoutElement()
        ));

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setValue('Submit');
        $this->addElement($submit);
    }

    /**
     * {@inheritdoc}
     *
     * @param object $object
     */
    public function setObject($object)
    {
        parent::setObject($object);
        $this->getElement('layout')->setValue($object->getLayout()->getSysname());
    }
}