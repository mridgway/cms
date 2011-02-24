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
class Route extends \Core\Service\AbstractModel
{
    /**
     * Returns a new Route.
     *
     * @param string $template
     * @return \Core\Model\Route
     */
    public function create($template)
    {
        return new \Core\Model\Route($template);
    }

    public function retrieve($id)
    {
        return $this->_retrieve($id);
    }

    public function findOneBySysname($sysname)
    {
        return $this->getEntityManager()->getRepository('Core\Model\Route')->findOneBySysname($sysname);
    }

    public function findOneByTemplate($template)
    {
        return $this->getEntityManager()->getRepository('Core\Model\Route')->findOneByTemplate($template);
    }
}