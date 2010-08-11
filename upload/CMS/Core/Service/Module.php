<?php
/**
 * Modo CMS
 */

namespace Core\Service;

/**
 * Service for Pages
 *
 * @category   Page
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Module.php 297 2010-05-12 13:34:56Z mike $
 */
class Module extends \Modo\Service\AbstractService
{
    public static function createModuleFromConfig(\Zend_Config $config)
    {
        $module = new \Core\Model\Module($config->sysname, $config->title);

        if ($config->blockTypes) {
            foreach ($config->blockTypes AS $disc => $type) {
                $blockType = new \Core\Model\Module\Block($type->name, $disc, $type->class);
                $module->addBlock($blockType);
            }
        }

        if ($config->contentTypes) {
            foreach ($config->contentTypes AS $disc => $type) {
                $controller = $type->controller?$type->controller:null;
                $contentType = new \Core\Model\Module\Content($type->name, $disc, $type->class, $controller);
                $module->addContent($contentType);
            }
        }

        return $module;
    }
}