<?php
/**
 * Modo CMS
 */

namespace Core\Service;

/**
 * Base service class
 *
 * @category   Base
 * @package    Modo
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: AbstractService.php 88 2010-01-13 18:15:08Z court $
 */
abstract class AbstractService
{

    /**
     * Entity manager
     *
     * @var Orm\VersionedEntityManager
     */
    protected $_em = null;


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
}