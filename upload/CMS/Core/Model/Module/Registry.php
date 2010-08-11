<?php

namespace Core\Model\Module;

class Registry
{
    private static $_instance = null;

    protected $_database = null;

    protected $_config = null;

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::setInstance(new Registry());
        }
        return self::$_instance;
    }

    public static function setInstance(Registry $instance)
    {
        self::$_instance = $instance;
    }

    public function getModules($fromDatabase = true)
    {
        if ($fromDatabase) {
            return $this->getDatabaseModules();
        } else {
            return $this->getConfigModules();
        }
    }

    public function getDatabaseModules()
    {
        if (null === $this->_database) {
            $this->loadFromDatabase();
        }
        return $this->_database;
    }

    public function loadFromDatabase()
    {
        $em = \Zend_Registry::get('doctrine');
        $this->_database = $em->getRepository('Core\Model\Module')->findAll();
        return $this->_database;
    }

    public function getConfigModules()
    {
        if (null === $this->_config) {
            $this->loadFromConfigs();
        }
        return $this->_config;
    }

    public function getConfigModule($name)
    {
        if (null === $this->_config) {
            $this->loadFromConfigs();
        }
        return $this->_config[$name];
    }

    public function loadFromConfigs()
    {
        $this->_config = array();
        $controllerDirs = \ZendX\Application53\Controller\Front::getInstance()->getDispatcher()->getControllerDirectory();
        foreach ($controllerDirs AS $controllerDir) {
            $configPath = realpath($controllerDir . '/../module.ini');
            $config = new \Zend_Config_Ini($configPath);
            $module = \Core\Service\Module::createModuleFromConfig($config);
            $this->_config[$module->sysname] = $module;
        }
    }
}