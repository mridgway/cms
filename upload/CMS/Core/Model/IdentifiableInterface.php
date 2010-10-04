<?php

namespace Core\Model;

/**
 * Interface for uniquely identifiable content
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
interface IdentifiableInterface
{
    /**
     * Get the unique identifier
     *
     * @return mixed
     */
    public function getIdentifier();
}