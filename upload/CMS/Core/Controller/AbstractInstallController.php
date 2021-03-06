<?php

namespace Core\Controller;

/**
 * Controller that holds shared functions for module installation
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Controller
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
abstract class AbstractInstallController extends \ZendX\Application53\Controller\Action
{
    /**
     * @var string
     */
    protected $moduleName = '';

    /**
     * @var Core\Model\Module
     */
    protected $module = null;

    /**
     * @var Core\Module\Registry
     */
    protected $moduleRegistry;

    /**
     * @var array
     */
    protected $classes = array();

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;
    
    /**
     * @var sfServiceContainer
     */
    protected $_sc;

    public function init()
    {
        $sc = $this->getInvokeArg('bootstrap')->serviceContainer;
        $this->_em = $sc->getService('doctrine');
        $this->moduleRegistry = $sc->getService('moduleRegistry');
        $this->_sc = $this->getInvokeArg('bootstrap')->serviceContainer;
    }

    public function indexAction()
    {
        $this->installAction();
    }

    abstract public function installAction();

    /**
     * @return Core\Model\Module
     */
    protected function getModule($moduleName = null)
    {
        if (null === $moduleName) {
            if (null === $this->module) {
                $this->module = $this->moduleRegistry->getConfigStorage()->getModule($this->moduleName);
            }
            return $this->module;
        }
        return $this->moduleRegistry->getConfigStorage()->getModule($moduleName);
    }

    protected function createSchema()
    {
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->_em);
        
        $metadata = array();
        foreach ($this->classes AS $class) {
            $metadata[] = $this->_em->getClassMetadata($class);
        }
        $tool->createSchema($metadata);
    }

    protected function registerModule()
    {
        $module = $this->getModule();

        // Traverse view script directory and add all scripts
        $viewsDirectory = APPLICATION_PATH . '/' . $this->moduleName . '/View/scripts';
        foreach ($this->getModule()->getResources() AS $resource) {
            $resourceViewsDirectory = $viewsDirectory . '/' . $resource->getResourceString() . '/' . $resource->getDiscriminator();
            if (is_dir($resourceViewsDirectory)) {
                $iterator = new \DirectoryIterator($resourceViewsDirectory);
                foreach ($iterator AS $fileinfo) {
                    if (!$fileinfo->isDot() && !$fileinfo->isDir() && substr($fileinfo->getFilename(), 0, 1) != '_') {
                        $fileParts = explode('.', $fileinfo->getFilename());
                        $sysname = $fileParts[0];
                        $this->_em->persist($resource->createView($sysname));
                    }
                }
            }
        }

        $this->_em->persist($module);
        $this->_em->flush();
    }
}