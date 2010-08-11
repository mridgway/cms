<?php

namespace User\Controller;

/**
 * Modo CMS
 *
 * Controls login actions for guest users
 *
 * @category   Controller
 * @package    User
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: LoginController.php 297 2010-05-12 13:34:56Z mike $
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
    }
}