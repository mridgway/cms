<?php

namespace Core\Service\Layout;

/**
 * Service for block functionality
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Location extends \Core\Service\AbstractService
{
    /**
     * Finds a location with name $sysname.
     * 
     * @param <type> $sysname
     * @return \Core\Model\Layout\Location
     */
    public function getLocation($sysname)
    {
        if(!$sysname) {
            throw new \Exception('A sysname is required to get a location.');
        }

        return $this->_em->getRepository('Core\Model\Layout\Location')->findOneBySysname($sysname);
    }
}