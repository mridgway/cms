<?php

namespace Blog\Block;

/**
 * Block for listing the latest articles
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity
 */
class LatestArticles extends \Core\Model\Block\DynamicBlock
{

    public function init()
    {
        $qb = $this->getEntityManager()
                   ->getRepository('Blog\Model\Article')
                   ->createQueryBuilder('a')
                   ->select('a, p, pr')
                   ->innerJoin('a.dependentPage', 'p')
                   ->innerJoin('p.primaryPageRoute', 'pr')
                   ->orderBy('a.id', 'DESC');

        $adapter = new \ZendX\Doctrine2\Paginator($qb);
        if ($this->getConfigValue('paginate')) {
            $paginator = new \Zend_Paginator($adapter);
            $page = $this->getRequest()->getParam('page', 1);
            $paginator->setCurrentPageNumber($page);
            $offset = ($page-1)*$this->getConfigValue('count');
            
            $this->getView()->assign('paginator', $paginator);
            $this->getView()->assign('articles', $paginator->getCurrentItems($offset, $this->getConfigValue('count')));
        } else {
            $this->getView()->assign('articles', $adapter->getItems(0, $this->getConfigValue('count')));
        }
        $this->getView()->assign('id', $this->getConfigValue('id'));
    }

    public function configure()
    {
        $count = new \Core\Model\Block\Config\Property\Text('count', 5, true);
        $id = new \Core\Model\Block\Config\Property('id', 0, false, true, 'Core\Model\Content');
        $paginate = new \Core\Model\Block\Config\Property('paginate', false);
        
        $this->addConfigProperties(array($count, $id, $paginate));
    }
}