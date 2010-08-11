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
 * @version    $Id: Frontcontroller.php 140 2010-01-28 23:23:07Z court $
 */
class Frontcontroller extends \Zend_Application_Resource_Frontcontroller
{
    /**
     * {@inheritdoc}
     *
     * Additionally, setup the default plugin loader for action helpers.
     *
     * @return Zend_Controller_Front
     */
    public function init()
    {
        $loader = new \Modo\Loader\PluginLoader(array(
            'Zend_Controller_Action_Helper_' => 'Zend/Controller/Action/Helper/',
            'Modo\Controller\Action\Helper\\' => 'Modo/Controller/Action/Helper'
        ));
        \Zend_Controller_Action_HelperBroker::setPluginLoader($loader);

        return parent::init();
    }
}