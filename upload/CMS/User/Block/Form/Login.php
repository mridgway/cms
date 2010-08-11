<?php
/**
 * Modo CMS
 */
namespace User\Block\Form;

/**
 * Login Block
 *
 * @category   Model
 * @package    User
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Login.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 */
class Login extends \Core\Model\Block\Dynamic\Form\AbstractForm
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
            $authAdapter = new \Modo\Auth\Adapter\Identity($this->getEntityManager());
            $authAdapter->setIdentity($data['identity'])
                        ->setPassHash($data['passHash']);

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