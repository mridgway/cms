<?php

namespace Core\Repository;

/**
 * Repository for the page model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Repository
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Page extends \Doctrine\ORM\EntityRepository
{
    public function getPageForRender($id)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->select('p, b, l, v, ll, cv');
        $qb->innerJoin('p.layout', 'l');
        $qb->leftJoin('l.locations', 'll');
        $qb->leftJoin('p.blocks', 'b');
        $qb->leftJoin('b.configValues', 'cv');
        $qb->leftJoin('b.view', 'v');
        $qb->where('p.id = :id');
        $qb->setParameter('id', $id);
        
        return $qb->getQuery()->getSingleResult();
    }
    
    /**
     * Get a template by its sysname
     *
     * @param string $sysname
     * @return Core\Model\Template
     */
    public function getTemplateBySysname($sysname)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t')
           ->from('Core\Model\Template', 't')
           ->where($qb->expr()->eq('t.sysname'), ':sysname')
           ->setParameter('sysname', $sysname);
        return $qb->getQuery()->getSinglResult();
    }
}