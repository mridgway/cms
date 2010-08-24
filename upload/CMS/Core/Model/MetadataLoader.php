<?php

namespace Core\Model;

/**
 * Adds metadata to doctrine from the module registry
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 */
class MetadataLoader
{
    
    /**
     * Hooks into the loadClassMetadata event of Doctrine to add discriminator mappings for content
     * and block types
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(\Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs)
    {
        // @var $classMetadata \Doctrine\ORM\Mapping\ClassMetadata
        $classMetadata = $eventArgs->getClassMetadata();
        if ($classMetadata->name == 'Core\Model\Block') {
            $modules = $this->getModules();
            $map = array();
            foreach($modules AS $module) {
                $map = array_merge($map, $this->getBlockDiscriminatorMap($module));
            }
            $classMetadata->setDiscriminatorMap($map);
        } else if ($classMetadata->name == 'Core\Model\Content') {
            $modules = $this->getModules();
            $map = array();
            foreach($modules AS $module) {
                $map = array_merge($map, $this->getContentDiscriminatorMap($module));
            }
            $classMetadata->setDiscriminatorMap($map);
        }
    }

    public function getModules()
    {
        try {
            return \Core\Module\Registry::getInstance()->getDatabaseStorage()->getModules();
        } catch (\Exception $e) {
            return \Core\Module\Registry::getInstance()->getConfigStorage()->getModules();
        }
    }

    public function getBlockDiscriminatorMap($module)
    {
        $map = array();
        foreach ($module->blockTypes AS $blockType) {
            $map[$blockType->discriminator] = $blockType->class;
        }
        return $map;
    }

    public function getContentDiscriminatorMap($module)
    {
        $map = array();
        foreach ($module->contentTypes AS $contentType) {
            $map[$contentType->discriminator] = $contentType->class;
        }
        return $map;
    }
}