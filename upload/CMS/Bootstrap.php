<?php

/**
 * Bootstraps required resources for the application
 *
 * @package     CMS
 * @category    Bootstrap
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * User registry: Zend_Auth
 * User registery: Zend_Acl
 * Doctrine
 *
 */
class Bootstrap extends \ZendX\Application53\Application\Bootstrap
{
    public function _initCoreAutoloader()
    {
        \Zend_Loader_Autoloader::getInstance()->registerNamespace('Core');
    }

    protected function setServiceContainer($options)
    {
        require_once $options['autoloaderpath'];
        sfServiceContainerAutoloader::register();
        $sc = new \sfServiceContainerBuilder();
        $this->serviceContainer = $sc;
        
        $sc->setParameter('APPLICATION_ROOT', APPLICATION_ROOT);

        // this is the only way to get the service container to Core\Controller\Plugin\Predispatch
        \Zend_Registry::set('serviceContainer', $sc);
    }

    protected function _initServiceContainer()
    {
        $this->serviceContainer->setService('pdoConnection', $this->getPluginResource('db')->getDbAdapter()->getConnection());
        $this->serviceContainer->setService('cache', $this->_getCache());

        $this->bootstrap('FrontController');
        $options = $this->getOption('serviceContainer');
        $files = array($options['path']);
        $containerDirs = \Zend_Controller_Front::getInstance()->getDispatcher()->getControllerDirectory();
        foreach ($containerDirs AS $containerDir) {
            if ($containerPath = realpath($containerDir . '/../container.xml')) {
                $files[] = $containerPath;
            }
        }

        $loader = new \sfServiceContainerLoaderFileXml($this->serviceContainer);
        $loader->load($files);

        $dumper = new \sfServiceContainerDumperXml($this->serviceContainer);

        return $this->serviceContainer;
    }

    public function _initDoctrine()
    {
        $this->bootstrap('serviceContainer');
        $em = $this->getResource('serviceContainer')->getService('doctrine');

        \Zend_Registry::getInstance()->set('doctrine', $em);

        return $em;
    }

    public function _initMetadataLoader()
    {
        $metadataLoader = $this->serviceContainer->getService('metadataLoader');

        $evm = \Zend_Registry::get('doctrine')->getEventManager();
        $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $metadataLoader);
    }
    
    public function _initServiceManager()
    {
        \Core\Service\Manager::setEntityManager(\Zend_Registry::get('doctrine'));
    }

    public function _initRequest()
    {
        $request = new \Core\Controller\Request\Http;
        \Zend_Controller_Front::getInstance()->setRequest($request);
    }
    
    public function _initRouter ()
    {
        $front = \Zend_Controller_Front::getInstance();

        $router = new \Core\Controller\Router\Rewrite();
        $front->setRouter($router);

        $router->removeDefaultRoutes();
        try {
            $routes = \Zend_Registry::get('doctrine')->getRepository('Core\Model\Route')->getRoutes();
            if(empty($routes)) {
                throw new \Exception('No routes found.');
            }
            $router->addRoutes($routes);
        } catch (\Exception $e) {
            $this->_setupDefaultRoutes($router);
        }
    }

    /**
     * Get the options that were provided for a given resource
     *
     * @param  string $resource Name of the resource
     * @return array|null
     */
    public function getResourceOptions($resource)
    {
        $resourceOptions = $this->getOption('resources');
        
        if (null !== $resourceOptions && isset($resourceOptions[$resource])) {
            return $resourceOptions[$resource];
        }

        return null;
    }

    protected function _setupDefaultRoutes($router)
    {
        $coreDirectRoute = new \Core\Model\Route('direct/:controller/:action/*');
        $coreDirectRoute->isDirect = true;

        $coreModuleRoute = new \Core\Model\Route('direct/:module/:controller/:action/*');
        $coreModuleRoute->isDirect = true;

        $installRoute = new \Core\Model\Route('install', null, array(
            'module' => 'core',
            'controller' => 'install',
            'action' => 'index'
        ));
        $installRoute->isDirect = true;

        $router->addRoute('direct2', $coreModuleRoute);
        $router->addRoute('direct', $coreDirectRoute);
        $router->addRoute('direct', $installRoute);
    }

    protected function _getCache()
    {
        $cache = null;
        if(class_exists('Memcache')) {
            $memcache = new \Memcache;
            if (@$memcache->connect('127.0.0.1')) {
                $cache = new \Doctrine\Common\Cache\MemcacheCache();
            }
        }

        if (null === $cache && extension_loaded('apc')) {
            $cache = new \Doctrine\Common\Cache\ApcCache();
        } else {
            $cache = new \Doctrine\Common\Cache\ArrayCache();
        }

        $cache->setNamespace('cms');
        return $cache;
    }
}