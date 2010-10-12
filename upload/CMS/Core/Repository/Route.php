<?php

namespace Core\Repository;

/**
 * Repository for the route model
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Repository
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Route extends \Doctrine\ORM\EntityRepository
{

    /**
     * Retrieves all routes from the database.
     *
     * @todo cache this
     * @return Core\Model\Route[]
     */
    public function getRoutes()
    {
        $qb = $this->createQueryBuilder('r');
        $results = $qb->getQuery()->execute();
        $routes = array();
        foreach ($results as $route) {
            if ($route->sysname) {
                $routes[$route->sysname] = $route;
            } else {
                $routes['route-'.$route->id] = $route;
            }
        }

        return $routes;
    }

    /**
     * Returns a route for a given id
     *
     * @param mixed $em
     * @return \Core\Model\Route
     */
    public function getRoute($id)
    {
        if (is_numeric($id)) {
            return $this->_em->find('Core\Model\Route', $id);
        }
        return $this->_em->getRepository('Core\Model\Route')->findOneBySysname($id);
    }

    /**
     * Searches database for a route that matches the given route template. Returns false if none
     * found and a Route if found. This is used for validators and should only ever return 1 value.
     * If this throws an exception because multiple conflicts were found, then there is an internal
     * error in the database and data was not properly validated.
     *
     * @param string $routeTemplate
     * @return mixed
     */
    public function getDuplicateFor($routeTemplate)
    {
        $regex = $this->_routeToSqlSearch($routeTemplate);

        $sql = 'SELECT r.id FROM Route AS r where r.template RLIKE \'' . $regex . '\'';
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('id', 'duplicate');

        $query = $this->_em->createNativeQuery($sql, $rsm);
        $result = $query->getSingleResult();
        if ($result) {
            return $this->_em->getReference('Core\Model\Route', $result['duplicate']);
        }

        return false;
    }

    /**
     * Replaces params inside a route with sql wildcards
     *
     * @param string $route
     * @return string
     */
    private function _routeToSqlSearch($route)
    {
        $route = '^' . $route . '$';
        $patterns = array();
        $patterns[] = '/:[a-zA-Z]*/';
        $replacements = array(':[a-zA-Z]*');
        return preg_replace($patterns, $replacements, $route);
    }
}