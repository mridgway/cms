<?php
/**
 * Modo CMS
 */

namespace Mock\Doctrine\ORM;

use \Doctrine\ORM;

/**
 * Description of QueryBuilderMock
 *
 * @category   Modo
 * @package    Package
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: QueryBuilderMock.php 136 2010-01-28 16:19:46Z mike $
 */
class QueryBuilderMock extends ORM\QueryBuilder
{
    protected $_firstResult = null;

    protected $_maxResults  = null;

    protected $_orderBy = array('sort' => null, 'order' => null);

    
    public function getFirstResult()
    {
        return $this->_firstResult;
    }

    public function getMaxResults()
    {
        return $this->_maxResults;
    }

    public function orderBy($sort, $order = null)
    {
        $this->_orderBy['sort'] = $sort;
        $this->_orderBy['order'] = $order;

        return $this;
    }

    public function setFirstResult($firstResult)
    {
        $this->_firstResult = $firstResult;
        return $this;
    }

    public function setMaxResults($maxResults)
    {
        $this->_maxResults = $maxResults;
        return $this;
    }


    public function testGetOrder()
    {
        return $this->_orderBy['order'];
    }

    public function testGetSort()
    {
        return $this->_orderBy['sort'];
    }
}