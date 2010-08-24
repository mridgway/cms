<?php

namespace Core\Auth;

/**
 * Overrides Zend_Auth to provide default Guest user
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Auth
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Auth extends \Zend_Auth
{

    const GUEST_GROUP = 1;
    const USER_GROUP = 2;
    const ADMIN_GROUP = 3;

    protected static $_guestUser = null;

    /**
     * {@inheritdoc}
     *
     * @return Auth Provides a fluent interface
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    /**
     * Overrides Zend_Auth to remove empty check
     * 
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function getIdentity()
    {
        $storage = $this->getStorage();

        $user = $storage->read();

        return ($user == null) ? self::getGuestUser() : $user;
    }

    public static function getGuestUser()
    {
        if (!isset(self::$_guestUser)) {
            self::$_guestUser = new \User\Model\User(
                    \Zend_Registry::get('doctrine')->getReference('User\Model\Group', self::GUEST_GROUP),
                    'guest@domain.com',
                    'Guest',
                    'User');
        }
        return self::$_guestUser;
    }
}