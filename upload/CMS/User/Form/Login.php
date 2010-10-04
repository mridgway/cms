<?php

namespace User\Form;

/**
 * Form for login
 *
 * @package     CMS
 * @subpackage  User
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Login extends \Core\Form\AbstractForm
{
    public function init()
    {
        $this->addElements(array(
            Factory\IdentityElementFactory::getIdentityElement(),
            Factory\IdentityElementFactory::getPassHashElement()
        ));

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setValue('Login');
        $this->addElement($submit);
    }
}