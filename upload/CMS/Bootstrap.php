<?php
/**
 * Modo CMS
 *
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Bootstrap.php 302 2010-05-19 19:22:02Z mike $
 */

class Bootstrap extends \ZendX\Application53\Application\Bootstrap
{
    public function _initCoreAutoloader()
    {
        \Zend_Loader_Autoloader::getInstance()->registerNamespace('Core\\');
    }

    public function _initDoctrine()
    {
        $cache = $this->_getCache();
        $config = new \Doctrine\ORM\Configuration();
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver(array());
        $config->setMetadataDriverImpl($driverImpl);
        //$config->setSqlLogger(new \Doctrine\DBAL\Logging\EchoSqlLogger);
        $config->setSqlLogger(new \ZendX\Doctrine2\FirebugProfiler());
        $config->setProxyDir(APPLICATION_ROOT . '/data/proxies');
        $config->setProxyNamespace('Model\Proxy');
        $connectionOptions = array(
            'pdo' => $this->getPluginResource('db')->getDbAdapter()->getConnection()
        );
        $em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

        \Zend_Registry::getInstance()->set('doctrine', $em);

        return $em;
    }

    public function _initMetadataLoader()
    {
        $metadataLoader = new \Modo\Orm\Model\MetadataLoader;

        $evm = \Zend_Registry::get('doctrine')->getEventManager();
        $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $metadataLoader);
    }
    
    public function _initServiceManager()
    {
        \Core\Service\Manager::setEntityManager(Zend_Registry::get('doctrine'));
    }

    public function _initDispatcher()
    {
        $dispatcher = new \Modo\Controller\Dispatcher\Standard();
        $dispatcher->setControllerNamespace('Controller');
        \Zend_Controller_Front::getInstance()->setDispatcher($dispatcher);
        \Zend_Controller_Front::getInstance()->setModuleControllerDirectoryName('Controller');
        \Zend_Controller_Front::getInstance()->setDefaultModule('Core');
        return $dispatcher;
    }

    public function _initRequest()
    {
        $request = new \Modo\Controller\Request\Http;
        Zend_Controller_Front::getInstance()->setRequest($request);
    }
    
    public function _initRouter ()
    {
        $front = \Zend_Controller_Front::getInstance();

        $router = new \Modo\Controller\Router\Rewrite();
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
     * {@inheritdoc}
     *
     * Due to the way in which prefix paths must be specified in a config file,
     * php 5.3 namespaces cannot be added via an ini file.  Plus, you cannot
     * configure the class to be used for the default plugin loader
     * instantiation, so this kills two birds with one stone.
     *
     * @return Zend_Loader_PluginLoader_Interface
     */
    public function getPluginLoader()
    {
        if ($this->_pluginLoader === null) {
            $options = array(
                'Zend_Application_Resource' => 'Zend/Application/Resource',
                'Modo\Application\Resource\\' => 'Modo/Application/Resource'
            );

            $this->_pluginLoader = new \Modo\Loader\PluginLoader($options);
        }

        return $this->_pluginLoader;
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
                $cache->setMemcache($memcache);
                $cache->setNamespace('blog');

                return $cache;
            }
        }

        if (null === $cache && extension_loaded('apc')) {
            $cache = new \Doctrine\Common\Cache\ApcCache();
        } else {
            $cache = new \Doctrine\Common\Cache\ArrayCache();
        }

        $cache->setNamespace('modocms');
        return $cache;
    }
}