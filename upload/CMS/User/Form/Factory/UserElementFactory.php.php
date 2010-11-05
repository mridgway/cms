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
class UserElementFactory
{
    public static function getIdElement()
    {
        return new \Core\Form\Element\Hidden('id');
    }

    public static function getEmailElement()
    {
        $email = new \Core\Form\Element\Text('email');
        $email->setLabel('Email:');
        $email->setAllowEmpty(false);
        $email->addValidator(new \Zend_Validate_EmailAddress());

        return $email;
    }

    public static function getFirstNameElement()
    {
        $firstName = new \Core\Form\Element\Password('firstName');
        $firstName->setLabel('First Name:');
        $firstName->setAllowEmpty(false);
        $firstName->addValidator(new \Zend_Validate_StringLength(0, 100));

        return $firstName;
    }

    public static function getLastNameElement()
    {
        $lastName = new \Core\Form\Element\Password('lastName');
        $lastName->setLabel('Last Name:');
        $lastName->setAllowEmpty(false);
        $lastName->addValidator(new \Zend_Validate_StringLength(0, 100));

        return $lastName;
    }

    public static function getGroupElement()
    {
        $element = new \Core\Form\Element\Select('group');
        $element->setLabel('Group:');
        $element->setAllowEmpty(false);

        $groups = \Zend_Registry::get('doctrine')->getRepository('User\Model\Group')->findAll();
        $options = array();
        foreach ($groups AS $group) {
            $options[$group->getId()] = $group->getName();
        }
        $element->setMultiOptions($options);

        return $element;
    }
}