<?php

namespace Core\Module;

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

    public function getDatabaseStorage()
    {
        return $this->_databaseStorage;
    }

    public function getConfigStorage()
    {
        return $this->_configStorage;
    }
}