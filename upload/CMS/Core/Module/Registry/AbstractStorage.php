<?php

namespace Core\Module\Registry;

/**
 * Abstract storage for modules and their components
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
abstract class AbstractStorage
{
    protected $_modules = null;

    abstract public function load();

    public function getModules($reload = false)
    {
        if (null == $this->_modules || $reload) {
            foreach($this->load() AS $module) {
                $this->_modules[$module->sysname] = $module;
            }
        }
        return $this->_modules;
    }

    /**
     * @param string $name
     * @return Core\Model\Module
     */
    public function getModule($name)
    {
        $modules = $this->getModules();

        if (!\array_key_exists($name, $modules)) {
            $modules = $this->getModules(true);
        }

        return $modules[$name];
    }

    public function getBlockTypeForBlock(\Core\Model\Block $block)
    {
        if ($blockType = $this->getBlockTypeByClass(get_class($block))) {
            return $blockType;
        }
        foreach (class_parents($block) AS $className) {
            if ($blockType = $this->getBlockTypeByClass($className)) {
                return $blockType;
            }
        }
        return null;
    }

    /**
     * @param string $name
     * @return Core\Model\Module\BlockType
     */
    public function getBlockType($name)
    {
        foreach ($this->getModules() AS $module) {
            foreach ($module->blockTypes AS $blockType) {
                if ($blockType->sysname == $name) {
                    return $blockType;
                }
            }
        }
        return null;
    }

    /**
     * @param string $class
     * @return Core\Model\Module\BlockType
     */
    public function getBlockTypeByClass($class)
    {
        foreach ($this->getModules() AS $module) {
            foreach ($module->blockTypes AS $blockType) {
                if ($blockType->class == $class) {
                    return $blockType;
                }
            }
        }
        return null;
    }

    /**
     * @param string $name
     * @return Core\Model\Module\ContentType
     */
    public function getContentType($name)
    {
        foreach ($this->getModules() AS $module) {
            foreach ($module->contentTypes AS $contentType) {
                if ($contentType->sysname == $name) {
                    return $contentType;
                }
            }
        }
        return null;
    }

    /**
     * @param string $class
     * @return Core\Model\Module\ContentType
     */
    public function getContentTypeByClass($class)
    {
        foreach ($this->getModules() AS $module) {
            foreach ($module->contentTypes AS $contentType) {
                if ($contentType->class == $class) {
                    return $contentType;
                }
            }
        }
        return null;
    }

    public function getActivityType($name)
    {
        foreach ($this->getModules() AS $module) {
            foreach ($module->activityTypes AS $activityType) {
                if ($activityType->sysname == $name) {
                    return $activityType;
                }
            }
        }
        return null;
    }

    public function getActivityTypeByClass($class)
    {
        foreach ($this->getModules() AS $module) {
            foreach ($module->activityTypes AS $activityType) {
                if ($activityType->class == $class) {
                    return $activityType;
                }
            }
        }
        return null;
    }

    public function reset()
    {
        $this->_modules = null;
    }
}