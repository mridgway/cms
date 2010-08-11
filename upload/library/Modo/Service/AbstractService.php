<?php
/**
 * Modo CMS
 */

namespace Modo\Service;

use \Modo\Orm;

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
     * Query constraint builder class suffix
     * 
     * @var string
     */
    protected $_builderSuffix = 'Builder';
    
    /**
     * Entity manager
     * 
     * @var Orm\VersionedEntityManager
     */
    protected $_em = null;

    /**
     * Namespace for query constraint builder objects
     * 
     * @var string
     */
    protected $_queryNamespace = '\Modo\Service\Query\\';


    /**
     * Constructor
     *
     * Optionally set the entity manager
     *
     * @param Orm\VersionedEntityManager $em
     */
    public function __construct(\Doctrine\ORM\EntityManager $em = null)
    {
        if (null !== $em) {
            $this->setEntityManager($em);
        }

        $this->init();
    }

    /**
     * __call - magic method
     *
     * Attempt to conveniently load query modifier objects and load data
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (0 === strpos($name, 'load')) {
            return $this->_loadConveniently(substr($name, 4), $arguments);
        }

        trigger_error("Method '$name' does not exist", \E_USER_ERROR);
    }

    /**
     * Get the entity manager
     *
     * @return Orm\VersionedEntityManager
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
     * Get the query constraint builder class suffix
     *
     * @return string
     */
    public function getBuilderSuffix()
    {
        return $this->_builderSuffix;
    }

    /**
     * Get the namespace for query constraint builder objects with leading
     * namespace separator
     *
     * @return string
     */
    public function getQueryNamespace()
    {
        return $this->_queryNamespace;
    }

    /**
     * Initialization functionality
     *
     * Called at the end of construction
     */
    public function init()
    {}

    /**
     * Load the data and return the results
     * 
     * @param  QueryModifierInterface $builder
     * @return mixed
     */
    public function load(Query\QueryModifierInterface $builder)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $builder->applyToQueryBuilder($qb);

        $hydration = $builder->getHydrationMode();
        $function  = 'getSingleResult';
        
        if ($builder->getReturnCollection()) {
            $function = 'getResult';
        }

        return $qb->getQuery()->$function($hydration);
    }

    /**
     * Set the entity manager
     *
     * @param  Orm\VersionedEntityManager $em
     * @return AbstractService *Provides fluid interface*
     */
    public function setEntityManager(\Doctrine\ORM\EntityManager $em)
    {
        $this->_em = $em;

        return $this;
    }

    /**
     * Set the namespace for query constraint builder objects
     *
     * Apply the leading namespace separator.
     *
     * @param  string
     * @return AbstractService *Provides fluid interface*
     */
    public function setQueryNamespace($namespace)
    {
        $this->_queryNamespace = rtrim($namespace, '\\') . '\\';

        return $this;
    }

    /**
     * Set the query constraint builder class suffix
     *
     * @param  string
     * @return AbstractService *Provides fluid interface*
     */
    public function setBuilderSuffix($suffix)
    {
        $this->_builderSuffix = (string)$suffix;

        return $this;
    }


    /**
     * Instantiate the appropriate builder class and pass it to load()
     *
     * @param  string $name
     * @param  array  $arguments
     * @return mixed
     */
    protected function _loadConveniently($name, array $arguments)
    {
        $class = $this->getQueryNamespace() . $name . $this->getBuilderSuffix();

        $reflection = new \ReflectionClass($class);
        $builder = $reflection->newInstanceArgs($arguments);

        return $this->load($builder);
    }
}