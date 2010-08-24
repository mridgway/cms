<?php

namespace Core\Service;

/**
 * Service for block functionality
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class Block extends \Core\Service\AbstractService
{

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
        $results = $this->_em->getRepository('Core\Model\Block')->getDependentValues($block);
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
        $controllerName = $this->getBlockController($block);
        if (null === $controllerName || !method_exists($controllerName, $action)) {
            throw new \Exception('Block controller/action does not exist.');
        }
        $controller = new $controllerName;
        $controller->setEntityManager($this->getEntityManager());
        $controller->setRequest($request);
        return $controller->$action($block);
    }

    /**
     * @todo Make this not suck
     * @param Block $block
     * @return string
     */
    public function getBlockController(\Core\Model\Block $block)
    {
        $modules = \Core\Module\Registry::getInstance()->getDatabaseStorage();
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
     * @param Block $block
     */
    public function deleteBlock(\Core\Model\Block $block)
    {
        // remove config dependencies on this block
        $this->removeConfigDependencies($block);

        // remove block config values
        foreach($block->getConfigValues() AS $value) {
            $this->_em->remove($value);
        }
        $block->removeConfigValues();

        $this->_em->remove($block);

        $this->_em->flush();
    }
}