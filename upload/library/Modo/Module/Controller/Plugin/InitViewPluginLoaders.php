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
 * @version    $Id: InitViewPluginLoaders.php 297 2010-05-12 13:34:56Z mike $
 */
class InitViewPluginLoaders extends \Zend_Controller_Plugin_Abstract
{
    /**
     * {@inheritdoc}
     *
     * Add the appropriate prefix paths to plugin loaders on the view object for 
     * all registered modules
     *
     * @param  \Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function dispatchLoopStartup(\Zend_Controller_Request_Abstract $request)
    {
        $viewRenderer = \Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer');
        $helperLoader = $viewRenderer->view->getPluginLoader('helper');
        $filterLoader = $viewRenderer->view->getPluginLoader('filter');

        foreach (Module\Registry::getInstance() as $module) {
            $moduleName = $module->getName();
            $modulePath = $module->getPath();
            
            $helperPath   = $modulePath . '/View/Helper';
            $helperPrefix = $moduleName . '\\View\\Helper\\';
            $helperLoader->addPrefixPath($helperPrefix, $helperPath);

            $filterPath   = $modulePath . '/View/Filter';
            $filterPrefix = $moduleName . '\\Filter\\';
            $filterLoader->addPrefixPath($filterPrefix, $filterPath);
        }

        \Core\Model\View::setPluginLoader($helperLoader, 'helper');
        \Core\Model\View::setPluginLoader($filterLoader, 'filter');
    }
}