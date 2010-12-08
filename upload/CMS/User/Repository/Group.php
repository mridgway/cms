<?php

namespace User\Repository;

/**
 * Repository for the group model
 *
 * @package     CMS
 * @subpackage  User
 * @category    Repository
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Group extends \Doctrine\ORM\EntityRepository
{
    public function findAllSelectableGroups()
    {
        $qb = $this->createQueryBuilder('g');
        $qb->where('g.sysname != :rootSysname');
        $qb->setParameter('rootSysname', 'root');

        return $qb->getQuery()->getResult();
    }
}