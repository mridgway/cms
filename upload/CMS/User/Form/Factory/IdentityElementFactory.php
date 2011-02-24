<?php

namespace User\Form\Factory;

/**
 * Factory for the identity form elements
 *
 * @package     CMS
 * @subpackage  User
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class IdentityElementFactory
{
    public static function getIdElement()
    {
        return new \Core\Form\Element\Hidden('id');
    }

    public static function getIdentifierElement()
    {
        $identity = new \Core\Form\Element\Text('identifier');
        $identity->setLabel('Identifier:');
        $identity->setAllowEmpty(false);
        $identity->addValidator(new \Zend_Validate_StringLength(1, 150));

        return $identity;
    }

    public static function getPasswordElement()
    {
        $passHash = new \Core\Form\Element\Password('password');
        $passHash->setLabel('Password:');
        $passHash->setAllowEmpty(false);
        $passHash->addValidator(new \Zend_Validate_StringLength(7, 128));

        return $passHash;
    }

    public static function getPasswordConfirmElement()
    {
        $passHash = new \Core\Form\Element\Password('passwordConfirm');
        $passHash->setLabel('Confirm Password:');
        $passHash->setAllowEmpty(false);
        $passHash->addValidator(new \User\Validator\PasswordConfirm('password'));

        return $passHash;
    }
}