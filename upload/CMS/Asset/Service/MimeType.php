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
class MimeType extends AbstractAssetService
{
    /**
     * Find a MimeType by sysname.
     *
     * @param string $sysname
     * @return Asset\Model\MimeType
     */
    public function getMimeType($sysname)
    {
        return $this->findBySysname('Asset\Model\MimeType', $sysname);
    }
}