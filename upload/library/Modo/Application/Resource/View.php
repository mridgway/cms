<?php
/**
 * Modo CMS
 */

namespace Modo\Application\Resource;

/**
 * @category   Modo
 * @package    Application
 * @subpackage Resource
 * @copyright  Copyright (c) 2010 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: View.php 140 2010-01-28 23:23:07Z court $
 */
class View extends \Zend_Application_Resource_View
{
    /**
     * {@inheritdoc}
     *
     * Additionally, setup the default plugin loaders for view helpers and view
     * filters.
     *
     * @return Zend_View
     */
    public function init()
    {
        $view = parent::init();

        $helperLoader = new \Modo\Loader\PluginLoader(array(
            'Zend_View_Helper_' => 'Zend/View/Helper/',
            'Modo\View\Helper\\' => 'Modo/View/Helper/'
        ));
        $filterLoader = new \Modo\Loader\PluginLoader(array(
            'Zend_View_Filter_' => 'Zend/View/Filter/',
            'Modo\View\Filter\\' => 'Modo/View/Filter/'
        ));

        $view->setPluginLoader($helperLoader, 'helper')
             ->setPluginLoader($filterLoader, 'filter');

        return $view;
    }
}