<?php

namespace Core\Service;

/**
 * Service for block functionality
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Block extends \Core\Service\AbstractService
{
    /**
     * @var \Core\Module\Registry
     */
    protected $_moduleRegistry;

    /**
     * @var \Core\Service\Layout\Location
     */
    protected $_locationService;

    /**
     * @var \sfServiceContainer
     */
    protected $_sc;

    /**
     * @var \Core\Service\Block\StaticBlock
     */
    protected $_staticBlockService;
    
    /**
     * Gets all config values or content type properties for a given block
     *
     * @param Block $block
     * @return array
     */
    public function getVariables(\Core\Model\Block $block)
    {
        $vars = array();
        if ($block instanceof \Core\Model\Block\DynamicBlock) {
            if (null !== $block->getConfigProperties()) {
                $vars = array_merge($vars, array_keys($block->getConfigProperties()));
            }
        } else {
            if ($block->getContent() instanceof \Core\Model\Content\Placeholder) {
                $class = $block->getContent()->getContentType();
                $properties = $this->getEntityManager()->getClassMetadata($class)->getReflectionProperties();
                $vars = array_merge($vars, array_keys($properties));
            } else {
                $class = get_class($block->getContent());
                $properties = $this->getEntityManager()->getClassMetadata($class)->getReflectionProperties();
                $vars = array_merge($vars, array_keys($properties));
            }
        }
        return $vars;
    }

    /**
     * Sets inheritsFrom property to null on config values that depend on the given block
     *
     * @param Block $block
     */
    public function removeConfigDependencies(\Core\Model\Block $block)
    {
        $results = $this->getEntityManager()->getRepository('Core\Model\Block')->getDependentValues($block);
        foreach ($results AS $value) {
            $value->setInheritsFrom(null);
        }
    }

    /**
     *
     * @param Block $block
     * @param string $action
     * @param Zend_Controller_Request_Http $request
     * @return string
     */
    public function dispatchBlockAction(\Core\Model\Block $block, $action, \Zend_Controller_Request_Http $request)
    {
        $controller = $this->getBlockControllerObject($block);
        if (!method_exists($controller, $action)) {
            throw new \Exception('Block controller/action does not exist.');
        }
        $controller->setServiceContainer($this->getServiceContainer());
        $controller->setRequest($request);
        return $controller->$action($block);
    }

    public function getBlockControllerObject(\Core\Model\Block $block)
    {
        $controllerName = $this->getBlockController($block);

        if(null === $controllerName) {
            throw new Exception(get_class($block) . ' controller is not specified.  Check the module.ini file.');
        }

        return new $controllerName;
    }

    /**
     * @todo Make this not suck
     * @param Block $block
     * @return string
     */
    public function getBlockController(\Core\Model\Block $block)
    {
        $modules = $this->getModuleRegistry()->getDatabaseStorage()->getModules();
        foreach ($modules AS $module) {
            foreach($module->contentTypes AS $type) {
                if ($type->class == get_class($block->content)) {
                    if ($type->controller) {
                        if (class_exists($type->controller)) {
                            return $type->controller;
                        }
                    }
                }
            }
        }
        return null;
    }

    /**
     * Deletes a block, deletes unshared content, and removes config dependencies.
     *
     * @param Block $block
     */
    public function delete(\Core\Model\Block $block)
    {
        // remove config dependencies on this block
        $this->removeConfigDependencies($block);

        if($block instanceof \Core\Model\Block\StaticBlock)
        {
            $this->getStaticBlockService()->delete($block);
        }

        $this->getEntityManager()->remove($block);

        $this->getEntityManager()->flush();
    }

    /**
     * Initializes a block.
     * 
     * @param \Core\Model\Block $block
     * @param  \Zend_Controller_Request_Http $request
     */
    public function initBlock(\Core\Model\Block $block, \Zend_Controller_Request_Http $request)
    {
        if ($block instanceof \Core\Model\Block\DynamicBlock) {
            // Initialize the dynamic block
            $block->setRequest($request);
            $block->setServiceContainer($this->getServiceContainer());
            $block->init();
        }
    }

    /**
     * Checks whether the current user has permission to view $block.
     *
     * @param \Core\Model\Block $block
     * @return boolean
     */
    public function canView(\Core\Model\Block $block)
    {
        return $block->canView($this->getAuth()->getIdentity());
    }

    /**
     * Modifies $block location attribute.
     *
     * @param \Core\Model\Block $block
     * @param string $locationSysname
     */
    public function updateLocation(\Core\Model\Block $block, $locationSysname)
    {
        $location = $this->getLocationService()->getLocation($locationSysname);
        $block->location = $location;
    }

    /**
     * Modifies $block weight attribute.
     * 
     * @param \Core\Model\Block $block
     * @param integer $weight
     */
    public function updateWeight(\Core\Model\Block $block, $weight)
    {
        if(\is_numeric($weight)) {
            $block->weight = $weight;
        } else {
            throw new \Exception('Weight must be numeric.');
        }
    }

    /**
     * Modifies $block attributes.  The structure of $blockObject must match \Core\Model\Block.
     * 
     * @param \Core\Model\Block $block
     * @param \stdClass $blockObject
     */
    public function update(\Core\Model\Block $block, \stdClass $blockObject)
    {
        $this->updateLocation($block, $blockObject->location);
        $this->updateWeight($block, $blockObject->weight);
    }

    public function getModuleRegistry()
    {
        return $this->_moduleRegistry;
    }

    public function setModuleRegistry(\Core\Module\Registry $moduleRegistry)
    {
        $this->_moduleRegistry = $moduleRegistry;
    }

    public function getLocationService()
    {
        return $this->_locationService;
    }

    public function setLocationService(\Core\Service\Layout\Location $locationService)
    {
        $this->_locationService = $locationService;
    }

    public function getServiceContainer()
    {
        return $this->_sc;
    }

    public function setServiceContainer(\sfServiceContainer $serviceContainer)
    {
        $this->_sc = $serviceContainer;
    }

    public function setStaticBlockService(\Core\Service\Block\StaticBlock $staticBlockService)
    {
        $this->_staticBlockService = $staticBlockService;
    }

    public function getStaticBlockService()
    {
        return $this->_staticBlockService;
    }
}