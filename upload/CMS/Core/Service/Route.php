<?php

namespace Core\Service;

/**
 * Service for routes
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Route extends \Core\Service\AbstractService
{
    /**
     * Returns a new Route.
     *
     * @param string $routeName
     * @return \Core\Model\Route
     */
    public function create($routeName)
    {
        return new \Core\Model\Route($routeName);
    }

    public function findOneBySysname($sysname)
    {
        return $this->getEntityManager()->getRepository('Core\Model\Route')->findOneBySysname($sysname);
    }
}