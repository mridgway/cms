<?php
/**
 * Modo CMS
 */

namespace Core\Repository;

/**
 * Service for Blocks
 *
 * @category   Repository
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Block.php 297 2010-05-12 13:34:56Z mike $
 */
class Block extends \Doctrine\ORM\EntityRepository
{

    public function getBlocksForPage(\Core\Model\AbstractPage $page)
    {
        $qb = $this->_em->getRepository('Core\Model\Block')->createQueryBuilder('b');
        $qb->select('b, cv');
        $qb->leftJoin('b.configValues', 'cv');
        $qb->where('b.page = :page');
        $qb->setParameter('page', $page);

        return $qb->getQuery()->getResult();
    }
    
    public function getDependentValues(\Core\Model\Block $block)
    {
        $qb = $this->_em->getRepository('Core\Model\Block\Config\Value')->createQueryBuilder('v');
        $qb->where('v.inheritsFrom.id = :block_id');
        $qb->setParameter('block_id', $block->id);

        return $qb->getQuery()->getResult();
    }
}