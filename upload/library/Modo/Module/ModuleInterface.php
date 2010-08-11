<?php
/**
 * Modo CMS
 */

namespace Modo\Module;

/**
 * @category   Modo
 * @package    Module
 * @copyright  Copyright (c) 2010 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: ModuleInterface.php 140 2010-01-28 23:23:07Z court $
 */
interface ModuleInterface
{
    /**
     * Get the module name
     *
     * @return string
     */
    public function getName();
}