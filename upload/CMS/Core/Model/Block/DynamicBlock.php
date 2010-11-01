<?php

namespace Core\Model\Block;

/**
 * A block that acts like a controller
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
abstract class DynamicBlock extends \Core\Model\Block
{
    /**
     * The current request object
     *
     * @var Zend_Controller_Request_Http
     */
    protected $_request = null;

    /**
     * Entity Manager
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em = null;

    /**
     * Gets called when a block is loaded in the page.
     */
    abstract public function init();

    /**
     * Gets called if the block's id is submitted in a form
     */
    public function process() {}

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
     * Gets the entity manager
     *
     * @todo remove this
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->getServiceContainer()->getService('doctrine');
    }

    public function canEdit($role)
    {
        return false;
    }

    public function canConfigure($role)
    {
        if (!empty($this->configProperties)) {
            return parent::canConfigure($role);
        }
        return false;
    }

}