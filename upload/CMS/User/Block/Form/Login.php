<?php

namespace User\Block\Form;

/**
 * Form block for logging in to the CMS
 *
 * @package     CMS
 * @subpackage  User
 * @category    Block
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 */
class Login extends \Core\Model\Block\Dynamic\Form
{
    protected $_blogService;

    public function init()
    {
        if (!$this->getForm()) {
            $this->_form = new \User\Form\Login();
        }
    }

    public function process()
    {
        $data = $this->getRequest()->getPost();

        $this->_form = new \User\Form\Login();

        if ($this->_form->isValid($data)) {
            $auth  = \Zend_Auth::getInstance();
            $authAdapter = new \Core\Auth\Adapter\Identity($this->getEntityManager());
            $authAdapter->setIdentity($data['identifier'])
                        ->setPassword($data['password']);

            $authResult = $authAdapter->authenticate();
            if (!$authResult->isValid()) {
                return $this->failure($authResult->getMessages());
            }

            $session = new \User\Model\Session($authResult->getIdentity()->getUser(), $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
            $auth->getStorage()->write($session);
            
            return $this->success();
        } else {
            return $this->failure();
        }
    }

    public function configure()
    {
    }
}