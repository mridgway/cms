<?php

namespace Core\Module\Registry;

class ConfigStorage extends AbstractStorage
{
    public function load()
    {
        $modules = array();
        $controllerDirs = \ZendX\Application53\Controller\Front::getInstance()->getDispatcher()->getControllerDirectory();
        foreach ($controllerDirs AS $controllerDir) {
            $configPath = realpath($controllerDir . '/../module.ini');
            $config = new \Zend_Config_Ini($configPath);
            $module = \Core\Service\Module::createModuleFromConfig($config);
            $modules[] = $module;
        }
        return $modules;
    }
}