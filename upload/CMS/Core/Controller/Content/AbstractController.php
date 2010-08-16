<?php
/**
 * Modo CMS
 */

namespace Core\Controller\Content;

/**
 * A base class for controllers
 *
 * @category   Content
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: AbstractController.php 125 2010-01-27 16:20:51Z mike $
 */
abstract class AbstractController implements ControllerInterface
{

    /**
     *
     * @var \Zend_Controller_Request_Http
     */
    protected $_request;

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * Sets the request object
     *
     * @param Zend_Controller_Request_Http $request
     */
    public function setRequest(\Zend_Controller_Request_Http $request)
    {
        $this->_request = $request;
    }

    /**
     * Gets the request object
     *
     * @return Zend_Controller_Request_Http
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Sets the entity manager
     *
     * @param EntityManager $em
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
        $this->_em = $em;
    }

    /**
     * Gets the entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->_em;
    }
}