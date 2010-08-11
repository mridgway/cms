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
 * @version    $Id: Page.php 297 2010-05-12 13:34:56Z mike $
 */
class Page extends \Doctrine\ORM\EntityRepository
{
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