<?php
/**
 * Modo CMS
 */

namespace Core\Auth\Storage;

/**
 * Description
 *
 * @category   Model
 * @package    User
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Doctrine.php 239 2010-03-26 12:54:52Z mike $
 */

class Doctrine extends \Zend_Auth_Storage_Session
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * The doctrine class that holds the session data
     * 
     * @var string
     */
    protected $_sessionClass = 'User\Model\Session';

    /**
     * @var \User\Model\Session
     */
    protected $_sessionObject = null;

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        parent::__construct();
        $this->_em = $em;
    }

    /**
     * {@inheritdoc}
     *
     * return boolean
     */
    public function isEmpty()
    {
        if (parent::isEmpty() || null == $this->_sessionObject) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function read()
    {
        $sessionId = parent::read();
        
        // Only load once for logged in users
        if (null != $sessionId && null == $this->_sessionObject) {
            $this->_sessionObject = $this->_em->find($this->_sessionClass, $sessionId);
        }

        return (null == $this->_sessionObject) ? null : $this->_sessionObject->getUser();
    }

    /**
     * {@interitdoc}
     *
     * @param Session $sessionObject
     * @return void
     */
    public function write($sessionObject)
    {
        // Incorrect type of session object
        if (!($sessionObject instanceof $this->_sessionClass)) {
            throw new \Exception('Session object passed for persistence is of incorrect type.');
        }

        // Can't write guest sessions to database.
        if (null == $sessionObject->getUser()) {
            throw new \Exception('Cannot write guest session.');
        }

        // Persist this session into the database
        $this->_em->persist($sessionObject);
        $this->_em->flush();
        parent::write($sessionObject->getId());
        $this->_sessionObject = $sessionObject;
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        // Remove database session info
        if (null != $this->read()) {
            $this->_em->remove($this->_sessionObject);
            $this->_em->flush();
            $this->_sessionObject = null;
        }
        parent::clear();
    }

    /**
     * @param string $class
     */
    public function setSessionClass($class)
    {
        $this->_sessionClass = $class;
    }

    /**
     * @return string
     */
    public function getSessionClass()
    {
        return $this->_sessionClass;
    }
}