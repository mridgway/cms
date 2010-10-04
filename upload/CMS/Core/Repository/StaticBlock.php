<?php

namespace Core\Repository;

/**
 * Repository for the block model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Repository
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class StaticBlock extends \Doctrine\ORM\EntityRepository
{
    public function getContentStaticBlocks(\Core\Model\Content $content)
    {
        $qb = $this->_em->getRepository('Core\Model\Block\StaticBlock')->createQueryBuilder('sb');
        $qb->select('sb');
        $qb->innerJoin('sb.content', 'c');
        $qb->where('c.id = :content_id');
        $qb->setParameter('content_id', $content->id);

        return $qb->getQuery()->getResult();
    }
}