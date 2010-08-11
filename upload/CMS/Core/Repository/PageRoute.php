<?php
/**
 * Modo CMS
 */

namespace Core\Repository;

/**
 * Service for PageRoutes
 *
 * @category   Repository
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: PageRoute.php 297 2010-05-12 13:34:56Z mike $
 */
class PageRoute extends \Doctrine\ORM\EntityRepository
{
    public function getPageRoute($routeId, $params)
    {
        $qb = $this->_em->getRepository('Core\Model\PageRoute')
                ->createQueryBuilder('pr');
        $qb->innerJoin('pr.route', 'r')
           ->where($qb->expr()->eq('r.id', $routeId));

        if ($params) {
            $qb->andWhere('pr.params = :params');
            $qb->setParameter('params', $params);
        }
        try {
            $pageRoute = $qb->getQuery()->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $pageRoute = null;
        }

        return $pageRoute;
    }
}