<?php

namespace Asset\Service;

/**
 * Service for assets
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Extension extends AbstractAssetService
{
    /**
     * Find an extension by sysname.
     * 
     * @param string $sysname
     * @return Asset\Model\Extension
     */
    public function getExtension($sysname)
    {
        return $this->findBySysname('Asset\Model\Extension', $sysname);
    }
}