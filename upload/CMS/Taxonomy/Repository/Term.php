<?php

namespace Taxonomy\Repository;

/**
 * Repository for taxonomy terms
 *
 * @package     CMS
 * @subpackage  Taxonomy
 * @category    Repository
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Term extends \Doctrine\ORM\EntityRepository
{

    public function findOneByVocabularySysnameAndName($vocabularyName, $name)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->innerJoin('t.vocabulary', 'v');
        $qb->where('t.name = :name');
        $qb->andWhere('v.sysname = :vocabularySysname');
        $qb->setParameter('name', $name);
        $qb->setParameter('vocabularySysname', $vocabularyName);

        return $qb->getQuery()->getSingleResult();
    }
}