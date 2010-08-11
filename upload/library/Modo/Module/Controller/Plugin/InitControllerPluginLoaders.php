<?php
/**
 * Modo CMS
 */

namespace Modo\Module\Controller\Plugin;

use \Modo\Module;

/**
 * @category   Modo
 * @package    Module
 * @subpackage Controller\Plugin
 * @copyright  Copyright (c) 2010 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: InitControllerPluginLoaders.php 140 2010-01-28 23:23:07Z court $
 */
class InitControllerPluginLoaders extends \Zend_Controller_Plugin_Abstract
{
    /**
     * {@inheritdoc}
     *
     * Add the appropriate prefix paths to the controller helper pluginloader
     *
     * @param  \Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup(\Zend_Controller_Request_Abstract $request)
    {
        $actionLoader = \Zend_Controller_Action_HelperBroker::getPluginLoader();

        foreach (Module\Registry::getInstance() as $module) {
            $helperPath   = $module->getPath() . '/controllers/helpers';
            $helperPrefix = $module->getName() . '\\Controller\\Helper\\';
            $actionLoader->addPrefixPath($helperPrefix, $helperPath);
        }
    }
}