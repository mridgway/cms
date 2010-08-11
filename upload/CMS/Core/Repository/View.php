<?php
/**
 * Modo CMS
 */

namespace Core\Repository;

/**
 * Service for Pages
 *
 * @category   Repository
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: View.php 297 2010-05-12 13:34:56Z mike $
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