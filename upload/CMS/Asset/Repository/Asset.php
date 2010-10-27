<?php

namespace Asset\Repository;

/**
 * Repository for assets
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Repository
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Asset extends \Doctrine\ORM\EntityRepository
{
    /**
     * Retrieves an asset by group and hash
     *
     * @param string $groupName
     * @param string $hash
     * @return Core\Model\Asset
     */
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

    /**
     * Searches the asset library by term
     *
     * @todo Use a paginator
     *
     * @param string $searchTerm
     * @param string $typeName
     * @param string $sortField
     * @param string $sortOrder
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    public function getLibraryAssetList($searchTerm = '',
                                        $typeName = 'all',
                                        $sortField = 'uploadDate',
                                        $sortOrder = 'DESC',
                                        $offset = 0,
                                        $limit = null)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->innerJoin('a.group', 'g');
        $qb->andWhere('g.sysname = :group');
        $qb->setParameter('group', 'default');
        if ($typeName != 'all') {
            $qb->innerJoin('a.mimeType', 'mt');
            $qb->innerJoin('mt.type', 't');
            $qb->andWhere('t.sysname = :type');
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

    /**
     * Gets the number of assets by term
     *
     * @param string $searchTerm
     * @param string $typeName
     * @return integer
     */
    public function getLibraryAssetCount($searchTerm = '',
                                         $typeName = 'all')
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select('count(a.id)');
        $qb->innerJoin('a.group', 'g');
        $qb->andWhere('g.sysname = :group');
        $qb->setParameter('group', 'default');
        if ($typeName != 'all') {
            $qb->innerJoin('a.mimeType', 'mt');
            $qb->innerJoin('mt.type', 't');
            $qb->andWhere('t.sysname = :type');
            $qb->setParameter('type', $typeName);
        }
        if ($searchTerm != '') {
            $qb->andWhere('a.name LIKE \'%'. $searchTerm .'%\'');
        }
        
        return $qb->getQuery()->getSingleScalarResult();
    }
}