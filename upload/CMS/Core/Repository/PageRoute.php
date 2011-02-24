<?php

namespace Core\Repository;

/**
 * Repository for the page route model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Repository
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class PageRoute extends \Doctrine\ORM\EntityRepository
{
    public function getPageIdForRoute($routeId, $params)
    {
        $qb = $this->_em->getRepository('Core\Model\PageRoute')
                ->createQueryBuilder('pr');
        $qb->select('p.id')
           ->innerJoin('pr.route', 'r')
           ->innerJoin('pr.page', 'p')
           ->where($qb->expr()->eq('r.id', $routeId));

        /* @var $route Core\Model\Route */
        $route = $this->_em->find('Core\Model\Route', $routeId);

        if ($params) {
            $routeParams = $route->getVariables();
            $params = \unserialize($params);
            foreach ($params AS $key => $param) {
                if (!in_array($key, $routeParams)) {
                    unset($params[$key]);
                }
            }

            $qb->andWhere('pr.params = :params');
            $qb->setParameter('params', serialize($params));
        }
        try {
            $pageRoute = $qb->getQuery()->getSingleScalarResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $pageRoute = null;
        }

        return $pageRoute;
    }
}