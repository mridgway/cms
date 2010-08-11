<?php
/**
 * Modo CMS
 */

namespace Modo\Application\Module;

/**
 * @category   Modo
 * @package    PACKAGE
 * @subpackage SUBPACKAGE
 * @copyright  Copyright (c) 2010 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: AbstractBootstrap.php 297 2010-05-12 13:34:56Z mike $
 */
abstract class AbstractBootstrap extends \Zend_Application_Module_Bootstrap
{
    /**
     * {@inheritdoc}
     */
    public function __construct($application)
    {
        $module = \Modo\Module\Registry::load($this->getModuleName());

        $module->loadConfig();

        parent::__construct($application);
    }
}