<?php
/**
 * Modo CMS
 */

namespace Modo\Module;

/**
 * @category   Modo
 * @package    Registry
 * @copyright  Copyright (c) 2010 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Registry.php 297 2010-05-12 13:34:56Z mike $
 */
class Registry implements \IteratorAggregate
{
    /**
     * @var Registry Singleton instance
     */
    protected static $_instance = null;
    
    /**
     * @var string Base path of the modules directory
     */
    protected $_baseModulePath = null;

    /**
     * @var string Name of the default module class
     */
    protected $_defaultModuleClass = 'Modo\Module';

    /**
     * @var ArrayIterator Registered modules
     */
    protected $_modules;
    

    /**
     * Retrieve singleton instance
     *
     * @return Registry
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Retrieve a module from the registry
     *
     * If a module by that name does not exist in the registry, create one and
     * add it.
     * 
     * @param  string $name
     * @return ModuleInterface
     */
    public static function load($name)
    {
        $self = self::getInstance();
        $name = $self->_formatModuleName((string)$name);

        if (!$self->isRegistered($name)) {
            $class = $self->getDefaultModuleClass();
            $module = new $class($name, $self->getModulePath($name));
            $module->getResourceAutoloader();

            $self->addModule($module);
        }

        return $self->getModule($name);
    }

    /**
     * Add a new module to the registry
     *
     * @param  ModuleInterface $module
     * @return Registry *Provides fluid interface*
     * @throws Exception If a module of that name is already defined
     */
    public function addModule(ModuleInterface $module)
    {
        $name = $this->_formatModuleName($module->getName());

        if ($this->isRegistered($name)) {
            throw new Exception("Module '$name' is already registered");
        }

        $this->_modules[$name] = $module;

        return $this;
    }

    /**
     * Get the base path of the modules directory
     *
     * @return string
     */
    public function getBaseModulePath()
    {
        if (null === $this->_baseModulePath) {
            if (!defined('\APPLICATION_PATH')) {
                throw new \Modo\ConfigException('Cannot determine base module path');
            }

            $path = \APPLICATION_PATH;

            $this->setBaseModulePath($path);
        }

        return $this->_baseModulePath;
    }

    /**
     * Get the specified module
     *
     * @param  string $name
     * @return ModuleInterface
     * @throws \InvalidArgumentException If the specified module is not registered
     */
    public function getModule($name)
    {
        $name = $this->_formatModuleName((string)$name);
        
        if (!$this->isRegistered($name)) {
            throw new \InvalidArgumentException("No module named '$name' is registered");
        }

        return $this->_modules[$name];
    }

    /**
     * Get the name of the default module class
     *
     * @return string
     */
    public function getDefaultModuleClass()
    {
        return $this->_defaultModuleClass;
    }

    /**
     * Get the module iterator
     * 
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->_modules;
    }

    /**
     * Get the complete path to the specified module
     * 
     * @param  string $name
     * @return string
     */
    public function getModulePath($name)
    {
        $name     = $name;
        $basePath = $this->getBaseModulePath();
        $path     = $basePath . \DIRECTORY_SEPARATOR . $name;
        
        return $path;
    }
    
    /**
     * Is the specified module stored in the registry?
     *
     * @param  string $name
     * @return boolean
     */
    public function isRegistered($name)
    {
        return isset($this->_modules[$name]);
    }

    /**
     * Set the base path of the modules directory
     * 
     * @param  string $path
     * @return Registry *Provides fluid interface*
     * @throws \InvalidArgumentException If the supplied path is not a valid directory
     */
    public function setBaseModulePath($path)
    {
        $path = (string)$path;
        
        if (!file_exists($path) || !is_dir($path)) {
            throw new \InvalidArgumentException("'$path' is not a valid directory");
        }
        
        $this->_baseModulePath = $path;

        return $this;
    }

    /**
     * Set the name of the default module class
     * 
     * @param  string $class
     * @return Registry *Provides fluid interface*
     */
    public function setDefaultModuleClass($class)
    {
        $this->_defaultModuleClass = (string)$class;

        return $this;
    }


    /**
     * Constructor
     *
     * Protected for access through getInstance() : singleton
     *
     * Setup the modules array iterator
     */
    protected function __construct()
    {
        $this->_modules = new \ArrayIterator(array());
    }

    /**
     * Format the module name
     * 
     * @param  string $name
     * @return string
     */
    protected function _formatModuleName($name)
    {
        return ucfirst($name);
    }
}