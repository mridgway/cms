<?php
/**
 * Modo CMS
 */

namespace Core\Model\Block;

/**
 * Description of DynamicBlock
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: DynamicBlock.php 297 2010-05-12 13:34:56Z mike $
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