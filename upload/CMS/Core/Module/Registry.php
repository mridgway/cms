<?php

namespace Core\Module;

/**
 * A singleton class that stores module information
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Module
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 */
class Registry
{
    private static $_instance = null;

    protected $_databaseStorage = null;

    protected $_configStorage = null;

    public function __construct()
    {
        $this->_databaseStorage = new Registry\DatabaseStorage(\Zend_Registry::get('doctrine'));
        $this->_configStorage = new Registry\ConfigStorage();
    }

    /**
     *
     * @return Registry
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::setInstance(new Registry());
        }
        return self::$_instance;
    }

    public static function setInstance($instance)
    {
        self::$_instance = $instance;
    }

    /**
     *
     * @return DatabaseStorage
     */
    public function getDatabaseStorage()
    {
        return $this->_databaseStorage;
    }

    /**
     *
     * @return ConfigStorage
     */
    public function getConfigStorage()
    {
        return $this->_configStorage;
    }

    public static function destroy ()
    {
        self::$_instance = null;
    }
}