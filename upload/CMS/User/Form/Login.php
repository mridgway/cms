<?php

namespace User\Form;

/**
 * Form for login
 *
 * @package     CMS
 * @subpackage  User
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Login extends \Core\Form\AbstractForm
{
    public function init()
    {
        $title = new \Core\Form\Element\Text('identity');
        $title->setLabel('Identity:');
        $title->setAllowEmpty(false);
        $title->addValidator(new \Zend_Validate_StringLength(1, 150));
        $this->addElement($title);

        $content = new \Core\Form\Element\Password('passHash');
        $content->setLabel('Password:');
        $content->setAllowEmpty(false);
        $content->addValidator(new \Zend_Validate_StringLength(1, 128));
        $this->addElement($content);

        $submit = new \Core\Form\Element\Submit('submit');
        $submit->setValue('Login');
        $this->addElement($submit);
    }
}