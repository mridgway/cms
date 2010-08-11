<?php
/**
 * Modo CMS
 */

namespace User\Form;

/**
 * Form for logging in
 *
 * @category   Form
 * @package    User
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Login.php 297 2010-05-12 13:34:56Z mike $
 */
class Login extends \Modo\Form\AbstractForm
{
    public function init()
    {
        $title = new \Modo\Form\Element\Text('identity');
        $title->setLabel('Identity:');
        $title->setAllowEmpty(false);
        $title->addValidator(new \Zend_Validate_StringLength(1, 150));
        $this->addElement($title);

        $content = new \Modo\Form\Element\Password('passHash');
        $content->setLabel('Password:');
        $content->setAllowEmpty(false);
        $content->addValidator(new \Zend_Validate_StringLength(1, 128));
        $this->addElement($content);

        $submit = new \Modo\Form\Element\Submit('submit');
        $submit->setValue('Login');
        $this->addElement($submit);
    }
}