<?php

namespace Core\Model;

/**
 * The central object of the cms that contains information for the current
 * page
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 */
class SystemPage extends Page
{
    public function canDelete($role)
    {
        return false;
    }
}