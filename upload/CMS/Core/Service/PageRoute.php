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

    public function createAndRouteTo(\Core\Model\Page $page, $routeName)
    {
        $route = $this->getRouteService()->findOneBySysname($routeName);
        $pageRoute = $route->routeTo($page);
        $this->getEntityManager()->persist($pageRoute);
        $this->getEntityManager()->flush();
    }

    public function setRouteService(\Core\Service\Route $routeService)
    {
        $this->_routeService = $routeService;
    }

    public function getRouteService()
    {
        return $this->_routeService;
    }
}