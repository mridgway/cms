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
class Group extends AbstractAssetService
{
    /**
     * Find a group by sysname.
     *
     * @param string $sysname
     * @return Asset\Model\Group
     */
    public function getGroup($sysname)
    {
        return $this->findBySysname('Asset\Model\Group', $sysname);
    }
}