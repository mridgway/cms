<?php

namespace User\Controller;

/**
 * Controls login actions
 *
 * @package     CMS
 * @subpackage  User
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class LoginController extends \Zend_Controller_Action
{
    /**
     * @var \Modo\Orm\VersionedEntityManager
     */
    protected $_em;

    public function init()
    {
        $this->_em = \Zend_Registry::get('doctrine');
    }

    public function loginAction()
    {
        
    }

    public function forgotPasswordAction()
    {

    }

    public function logoutAction()
    {
        \Zend_Auth::getInstance()->clearIdentity();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}