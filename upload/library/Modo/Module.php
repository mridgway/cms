<?php
/**
 * Modo CMS
 */

namespace Modo;

/**
 * @category   Modo
 * @package    Module
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Module.php 297 2010-05-12 13:34:56Z mike $
 */
class Module implements Module\ModuleInterface
{
    /**
     * @var string Name of the default resource autoloader class
     */
    protected static $_defaultResourceClass = 'Modo\Application\Module\Autoloader';

    /**
     * @var string Name of this module
     */
    protected $_name;

    /**
     * @var configuration data for this module
     */
    protected $_config;

    /**
     * @var \Zend_Loader_Autoloader_Resource Resource autoloader for this module
     */
    protected $_resourceAutoloader = null;


    /**
     * Get the name of the default resource autoloader class
     * 
     * @return string
     */
    public static function getDefaultResourceClass()
    {
        return self::$_defaultResourceClass;
    }

    /**
     * Set the name of the default resource autoloader class
     *
     * @param string
     */
    public static function setDefaultResourceClass($class)
    {
        self::$_defaultResourceClass = (string)$class;
    }

    /**
     * Constructor
     *
     * Set the name of the module.
     * 
     * @param string $name Name of this module
     * @param string $path Path to this module
     */
    public function __construct($name, $path)
    {
        $this->_setName($name)
             ->_setPath($path);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Get the module namespace
     * 
     * @return string
     */
    public function getNamespace()
    {
        return $this->getName() . '\\';
    }

    /**
     * Get the base path of the current module
     * 
     * @return string
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Get the configuration of this module
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Loads the module.ini file for the module
     *
     * @return Zend_Config
     */
    public function loadConfig()
    {
        $configPath = $this->getPath() . '/module.ini';
        try {
            $config = new \Zend_Config_Ini($configPath);
        } catch (\Zend_Config_Exception $e) {
            return;
        }
        return $this->_config = $config->toArray();
    }

    /**
     * Get the resource autoloader for this module
     *
     * @return \Zend_Loader_Autoloader_Resource
     */
    public function getResourceAutoloader()
    {
        if (null === $this->_resourceAutoloader) {
            $class = self::getDefaultResourceClass();
            /* @var $loader \Zend_Loader_Autoloader */
            $loader = $class::getInstance();
            $loader->registerNamespace($this->getNamespace());

            $this->_resourceAutoloader = $loader;
        }

        return $this->_resourceAutoloader;
    }

    /**
     * Set the base path of the current module
     * 
     * @param  string $path
     * @return Module *Provides fluid interface*
     */
    public function setPath($path)
    {
        $this->_path = (string)$path;

        return $this;
    }

    /**
     * Set the resource autoloader for this module
     * 
     * @param  \Zend_Loader_Autoloader_Resource $autoloader
     * @return Module *Provides fluid interface*
     */
    public function setResourceAutoloader(\Zend_Loader_Autoloader_Resource $autoloader)
    {
        $this->_resourceAutoloader = $autoloader;

        return $this;
    }


    /**
     * Set the name of this module
     *
     * @param  string $name
     * @return Module *Provides fluid interface*
     */
    protected function _setName($name)
    {
        $this->_name = (string)$name;

        return $this;
    }

    /**
     * Set the base path of the current module
     * 
     * @param  string $path
     * @return Module *Provides fluid interface*
     */
    protected function _setPath($path)
    {
        $this->_path = (string)$path;

        return $this;
    }
}