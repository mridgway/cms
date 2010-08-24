<?php

namespace Core\Repository;

/**
 * Repository for the view model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Repository
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class View extends \Doctrine\ORM\EntityRepository
{
    public function getView($module, $type, $sysname)
    {
        $qb = $this->createQueryBuilder('v');
        $qb->where($qb->expr()->eq('v.module', ':module'))
           ->andWhere($qb->expr()->eq('v.type', ':type'))
           ->andWhere($qb->expr()->eq('v.sysname', ':sysname'));
        $qb->setParameter('sysname', $sysname)
           ->setParameter('type', $type)
           ->setParameter('module', $module);
        return $qb->getQuery()->getSingleResult();
    }

    public function getViewsForType($module, $type)
    {
        $qb = $this->createQueryBuilder('v');
        $qb->where($qb->expr()->eq('v.module', ':module'))
           ->andWhere($qb->expr()->eq('v.type', ':type'));
        $qb->setParameter('type', $type)
           ->setParameter('module', $module);
        return $qb->getQuery()->getSingleResult();
    }
}