<?php

namespace Core\Service\Validation;

/**
 * Form for address model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Address extends \Zend_Form_SubForm
{
    public function init()
    {
       $this->addElements(array(
           \Core\Form\Factory\Address::getIdElement(),
           \Core\Form\Factory\Address::getAddressLine1Element(),
           \Core\Form\Factory\Address::getAddressLine2Element(),
           \Core\Form\Factory\Address::getCityElement(),
           \Core\Form\Factory\Address::getStateElement(),
           \Core\Form\Factory\Address::getZipElement()
        ));
    }
}