<?php
/**
 * Modo CMS
 */

namespace Asset\Repository;

/**
 * Service for Blocks
 *
 * @category   Repository
 * @package    Asset
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Asset.php 297 2010-05-12 13:34:56Z mike $
 */
class Asset extends \Doctrine\ORM\EntityRepository
{
    public function getAssetByGroupNameAndHash($groupName, $hash)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->innerJoin('a.group' , 'ag');
        $qb->where('a.sysname = :hash');
        $qb->andWhere('ag.sysname = :group_name');
        $qb->setParameter('group_name', $groupName);
        $qb->setParameter('hash', $hash);

        return $qb->getQuery()->getSingleResult();
    }

    public function getLibraryAssetList($searchTerm = '',
                                        $typeName = 'all',
                                        $sortField = 'uploadDate',
                                        $sortOrder = 'DESC',
                                        $offset = 0,
                                        $limit = null)
    {
        $qb = $this->createQueryBuilder('a');
        if ($typeName != 'all') {
            $qb->andWhere('a.mimeType.type.sysname = :type');
            $qb->setParameter('type', $typeName);
        }
        if ($searchTerm != '') {
            $qb->andWhere('a.name LIKE \'%'.$searchTerm.'%\'');
        }
        $qb->orderBy('a.'.$sortField, $sortOrder);

        $query = $qb->getQuery();
        $query->setFirstResult($offset);
        $query->setMaxResults($limit);

        return $query->getResult();
    }

    public function getLibraryAssetCount($searchTerm = '',
                                         $typeName = 'all')
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select('count(a.id)');
        if ($typeName != 'all') {
            $qb->andWhere('a.mimeType.type.sysname = :type');
            $qb->setParameter('type', $typeName);
        }
        if ($searchTerm != '') {
            $qb->andWhere('a.name LIKE \'%'. $searchTerm .'%\'');
        }
        
        return $qb->getQuery()->getSingleScalarResult();
    }
}