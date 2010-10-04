<?php

namespace Core\Service;

/**
 * Service for modules
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Service
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Module extends \Core\Service\AbstractService
{
    public static function createModuleFromConfig(\Zend_Config $config)
    {
        $module = new \Core\Model\Module($config->sysname, $config->title);

        if ($config->blockTypes) {
            foreach ($config->blockTypes AS $disc => $type) {
                $blockType = new \Core\Model\Module\BlockType($type->name, $disc, $type->class);
                $blockType->addable = $type->addable ? $type->addable : false;
                $module->addResource($blockType);
            }
        }

        if ($config->contentTypes) {
            foreach ($config->contentTypes AS $disc => $type) {
                $controller = $type->controller?$type->controller:null;
                $contentType = new \Core\Model\Module\ContentType($type->name, $disc, $type->class, $controller);
                $contentType->addable = $type->addable ? $type->addable : false;
                $module->addResource($contentType);
            }
        }

        return $module;
    }
}