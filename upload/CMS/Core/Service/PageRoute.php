<?php

namespace Core\Service;

/**
 * Service for page routes
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class PageRoute extends \Core\Service\AbstractService
{
    /**
     * @var \Core\Service\Route
     */
    protected $_routeService;

    /**
     * @var \Core\Service\Page
     */
    protected $_pageService;

    public function createAndRouteTo(\Core\Model\Page $page, $routeName, $params = array())
    {
        $route = $this->getRouteService()->findOneBySysname($routeName);
        $pageRoute = $route->routeTo($page, $params);
        $this->getEntityManager()->persist($pageRoute);
        $this->getEntityManager()->flush();
    }

    /**
     * This creates a new PageRoute.
     * 
     * @param array $data
     * @return \Core\Model\PageRoute
     */
    public function create($data)
    {
        if(\array_key_exists('route', $data) && \array_key_exists('sysname', $data['route'])) {
            $route = $this->getRouteService()->findOneBySysname($data['route']['sysname']);
        } else {
            throw \Core\Exception\ValidationException::invalidData('Core\Model\PageRoute', array('required' => 'route sysname is required'));
        }

        if(\array_key_exists('page', $data)) {
            $page = $this->getPageService()->getPage($data['page']);
        } else {
            throw \Core\Exception\ValidationException::invalidData('Core\Model\PageRoute', array('required' => 'a page id is required'));
        }

        $params = null;
        if(\array_key_exists('params', $data)) {
            if(\is_array($data['params'])) {
                $params = $data['params'];
            } else {
                throw \Core\Exception\ValidationException::invalidData('Core\Model\PageRoute', array('paramsNotArray' => 'parameters must be a PHP array data type'));
            }
        }

        $pageRoute = null;
        $uniquePageRoute = $this->getEntityManager()->getRepository('Core\Model\PageRoute')->getPageIdForRoute($route->getId(), \serialize($params));
        if(null == $uniquePageRoute) {
            $pageRoute = $route->routeTo($page, $params);
            $this->getEntityManager()->persist($pageRoute);
            $this->getEntityManager()->flush();
        } else {
            throw \Core\Exception\ValidationException::invalidData('Core\Model\PageRoute', array('notUnique' => 'route template and parameters are not unique'));
        }

        return $pageRoute;
    }

    public function setRouteService(\Core\Service\Route $routeService)
    {
        $this->_routeService = $routeService;
    }

    public function getRouteService()
    {
        return $this->_routeService;
    }

    public function setPageService(\Core\Service\Page $pageService)
    {
        $this->_pageService = $pageService;
    }

    public function getPageService()
    {
        return $this->_pageService;
    }
}