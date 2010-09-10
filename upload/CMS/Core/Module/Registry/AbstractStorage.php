<?php

namespace Core\Module\Registry;

/**
 * Abstract storage for modules and their components
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
abstract class AbstractStorage
{
    protected $_modules = null;

    abstract public function load();

    public function getModules()
    {
        if (null == $this->_modules) {
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
        return $this->_modules[$name];
    }

    public function getBlockType($name)
    {
        foreach ($this->_modules AS $module) {
            foreach ($module->blockTypes AS $blockType) {
                if ($blockType->sysname == $name) {
                    return $blockType;
                }
            }
        }
        return null;
    }

    public function getBlockTypeByClass($class)
    {
        foreach ($this->_modules AS $module) {
            foreach ($module->blockTypes AS $blockType) {
                if ($blockType->class == $class) {
                    return $blockType;
                }
            }
        }
        return null;
    }

    public function getContentType($name)
    {
        foreach ($this->_modules AS $module) {
            foreach ($module->contentTypes AS $contentType) {
                if ($contentType->sysname == $name) {
                    return $contentType;
                }
            }
        }
        return null;
    }

    public function getContentTypeByClass($class)
    {
        foreach ($this->_modules AS $module) {
            foreach ($module->contentTypes AS $contentType) {
                if ($contentType->class == $class) {
                    return $contentType;
                }
            }
        }
        return null;
    }
}