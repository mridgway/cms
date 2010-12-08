<?php

namespace Core\Service;

/**
 * Base class for services that require the entity manager
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
abstract class AbstractService
{
    /**
     * Entity manager
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em = null;

    /**
     * @var Zend_Auth
     */
    protected $_auth = null;

    /**
     * @var Zend_Acl
     */
    protected $_acl = null;

    /**
     * @var Zend_Mail_Transport_Abstract
     */
    protected $_mailTransport = null;

    /**
     * Constructor
     *
     * Optionally set the entity manager
     *
     * @param Doctrine\ORM\EntityManager $em
     */
    public function __construct(\Doctrine\ORM\EntityManager $em = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }
    }

    /**
     * Get the entity manager
     *
     * @return Doctrine\ORM\EntityManager
     * @throws \Modo\ConfigException If no entity manager could be found
     */
    public function getEntityManager()
    {
        if (null === !$this->_em) {
            if (\Zend_Registry::isRegistered('doctrine')) {
                $this->setEntityManager(\Zend_Registry::get('doctrine'));
            } else {
                throw new \Modo\ConfigException('No entity manager was set');
            }
        }

        return $this->_em;
    }

    /**
     * Set the entity manager
     *
     * @param  Doctrine\ORM\EntityManager $em
     * @return AbstractService *Provides fluid interface*
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
        $this->_em = $em;

        return $this;
    }

    /**
     * @param \Zend_Auth $auth
     */
    public function setAuth(\Zend_Auth $auth)
    {
        $this->_auth = $auth;
    }

    /**
     * @return Zend_Auth
     */
    public function getAuth()
    {
        return $this->_auth;
    }

    /**
     * @param \Zend_Acl $acl
     */
    public function setAcl(\Zend_Acl $acl)
    {
        $this->_acl = $acl;
    }

    /**
     * @return Zend_Acl
     */
    public function getAcl()
    {
        return $this->_acl;
    }

    /**
     * @param \Zend_Mail_Transport_Abstract $mailTransport
     */
    public function setMailTransport(\Zend_Mail_Transport_Abstract $mailTransport)
    {
        $this->_mailTransport = $mailTransport;
    }

    /**
     * @return Zend_Mail_Transport_Abstract
     */
    public function getMailTransport()
    {
        return $this->_mailTransport;
    }
}