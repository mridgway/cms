<?php

namespace User\Form\Factory;

/**
 * Factory for the identity form elements
 *
 * @package     CMS
 * @subpackage  User
 * @category    Form
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class IdentityElementFactory
{
    public static function getIdElement()
    {
        return new \Zend_Form_Element_Hidden('id');
    }

    public static function getIdentityElement()
    {
        $identity = new \Core\Form\Element\Text('identity');
        $identity->setLabel('Identity:');
        $identity->setAllowEmpty(false);
        $identity->addValidator(new \Zend_Validate_StringLength(1, 150));

        return $identity;
    }

    public static function getPassHashElement()
    {
        $passHash = new \Core\Form\Element\Password('passHash');
        $passHash->setLabel('Password:');
        $passHash->setAllowEmpty(false);
        $passHash->addValidator(new \Zend_Validate_StringLength(1, 128));

        return $passHash;
    }
}