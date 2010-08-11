<?php
/**
 * Modo CMS
 */

namespace Modo\Service\Query;

use \Doctrine\ORM;

/**
 * @category   Modo
 * @package    Module
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: ConstraintBuilder.php 88 2010-01-13 18:15:08Z court $
 */
class ConstraintBuilder implements QueryModifierInterface
{
    /**
     * Default hydration mode
     * 
     * @var integer
     */
    protected $_defaultHydrationMode = ORM\Query::HYDRATE_OBJECT;

    /**
     * The default order direction
     * 
     * @var string
     */
    protected $_defaultOrder = 'ASC';

    /**
     * Hydration mode
     * 
     * @var integer
     */
    protected $_hydrationMode = null;

    /**
     * 'Limit' part of the query
     *
     * @var integer|null
     */
    protected $_limit = null;

    /**
     * 'Offset' part of the query
     *
     * @var integer|null
     */
    protected $_offset = null;

    /**
     * 'Order by' part of the query
     *
     * @var array
     */
    protected $_orderBy = array('sort' => null, 'order' => null);

    /**
     * Return a collection of results?
     *
     * Defaults to true: an array of results will be returned.
     *
     * @var boolean
     */
    protected $_returnCollection = true;

    
    /**
     * Apply appropriate DQL logic given currnet parameters
     * 
     * @param  ORM\QueryBuilder $qb
     * @return ORM\QueryBuilder
     */
    public function applyToQueryBuilder(ORM\QueryBuilder $qb)
    {
        $this->_applyConstraints($qb);
        
        $limit   = $this->getLimit();
        $offset  = $this->getOffset();
        $orderBy = $this->getOrderBy();
        
        if (null !== $limit) {
            $qb->setMaxResults($limit);
        }

        if (null !== $offset) {
            $qb->setFirstResult($offset);
        }
        
        if (!empty($orderBy['sort'])) {
            $qb->orderBy($orderBy['sort'], $orderBy['order']);
        }

        return $qb;
    }

    /**
     * Get the default hydration mode
     *
     * @return integer
     */
    public function getDefaultHydrationMode()
    {
        return $this->_defaultHydrationMode;
    }

    /**
     * Get the default order direction
     * 
     * @return string
     */
    public function getDefaultOrder()
    {
        return $this->_defaultOrder;
    }

    /**
     * {@inheritdoc}
     *
     * @return integer
     */
    public function getHydrationMode()
    {
        if (null === $this->_hydrationMode) {
            $this->_hydrationMode = $this->getDefaultHydrationMode();
        }

        return $this->_hydrationMode;
    }

    /**
     * Get the 'limit' part of the query
     *
     * @return string|null
     */
    public function getLimit()
    {
        return $this->_limit;
    }

    /**
     * Get the 'offset' part of the query
     *
     * @return string|null
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * Get the 'order by' part of the query
     *
     * @return array
     */
    public function getOrderBy()
    {
        return $this->_orderBy;
    }

    /**
     * {@inheritdoc}
     *
     * @return boolean
     */
    public function getReturnCollection()
    {
        return $this->_returnCollection;
    }

    /**
     * Set the default hydration mode
     *
     * @param  integer $hydrationMode
     * @return ConstraintBuilder *Provides fluid interface*
     */
    public function setDefaultHydrationMode($hydrationMode)
    {
        $this->_defaultHydrationMode = (int)$hydrationMode;

        return $this;
    }

    /**
     * Set the default order direction
     * 
     * @param  string $order
     * @return ConstraintBuilder *Provides fluid interface*
     */
    public function setDefaultOrder($order)
    {
        $this->_defaultOrder = (string)$order;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param  integer $hydrationMode
     * @return ConstraintBuilder *Provides fluid interface*
     */
    public function setHydrationMode($hydrationMode)
    {
        $this->_hydrationMode = (int)$hydrationMode;

        return $this;
    }

    /**
     * Set the 'limit' part of the query
     *
     * @param  string $limit
     * @return ConstraintBuilder *Provides fluid interface*
     */
    public function setLimit($limit = null)
    {
        if (null !== $limit) {
            $limit = (int)$limit;
        }

        $this->_limit = $limit;

        return $this;
    }

    /**
     * Set the 'offset' part of the query
     *
     * @param  string $offset
     * @return ConstraintBuilder *Provides fluid interface*
     */
    public function setOffset($offset = null)
    {
        if (null !== $offset) {
            $offset = (int)$offset;
        }

        $this->_offset = $offset;

        return $this;
    }

    /**
     * Set the 'order by' part of the query
     *
     * @param  string $order
     * @return ConstraintBuilder *Provides fluid interface*
     */
    public function setOrderBy($sort = null, $order = null)
    {
        if (null === $sort) {
            $order = null;
        } else {
            $sort  = (string)$sort;
            $order = ($order === null) ? null : (string)$order;
        }

        $this->_orderBy['sort']  = $sort;
        $this->_orderBy['order'] = $order;

        return $this;
    }

    /**
     * {@inheritdoc}
     * 
     * @param  boolean $flag
     * @return ConstraintBuilder *Provides fluid interface*
     */
    public function setReturnCollection($flag = true)
    {
        $this->_returnCollection = $flag;

        return $this;
    }


    /**
     * Apply constraints to the query builder
     * 
     * @param ORM\QueryBuilder $qb
     */
    protected function _applyConstraints(ORM\QueryBuilder $qb)
    {}
}