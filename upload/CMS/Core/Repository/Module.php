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
class Module extends \Doctrine\ORM\EntityRepository
{
    public function findAll()
    {
        $qb = \Zend_Registry::get('doctrine')->getRepository('Core\Model\Module')->createQueryBuilder('m');
        $qb->select('m, b, c')
           ->leftJoin('m.blockTypes', 'b')
           ->leftJoin('m.contentTypes', 'c');

        return $qb->getQuery()->getResult();
    }
}