<?php
/**
 * Modo CMS
 */

namespace Core\Model;

use \Doctrine\Common;

/**
 * Base class for Doctrine models
 *
 * @category   Modo
 * @package    Orm
 * @subpackage Model
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: AbstractModel.php 206 2010-02-25 15:35:09Z mike $
 */
abstract class AbstractModel
    implements Common\NotifyPropertyChanged,
               IdentifiableInterface
{
    protected $_listeners = array();

    /**
     * Provides access to class properties. Looks for function get{$name} first.
     *
     * @param string $name
     * @return mixed
     */
    public function __get ($name) {
        $method = 'get'.ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        } 
        return $this->{$name};
    }

    /**
     * Allows isset() on properties access by magic getters.
     *
     * @param string $name
     * @return bool
     */
    public function __isset ($name) {
        if (property_exists($this, $name)) {
            return true;
        }
        return false;
    }

    /**
     * Provides access to change class properties. Looks for function set{$name} first.
     *
     * @param string $name
     * @param mixed $value
     * @return Core\Model\Route
     */
    public function __set ($name, $value) {
        $method = 'set'.ucfirst($name);
        if (method_exists($this, $method)) {
            return $this->{$method}($value);
        } else {
            $this->{$name} = $value;
        }

        return $this;
    }

    /**
     * Provides access to variables via get and set methods even if they don't exist.
     *
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call ($name, $args) {
        $var = lcfirst(substr($name, 3));
        if (property_exists($this, $var)) {
            if (substr($name, 0, 3) == 'get') {
                return $this->__get($var);
            } else if (substr($name, 0, 3) == 'set') {
                return $this->__set($var, $args[0]);
            }
        }

        throw new \Exception('Method `'.$name.'` does not exist.');
    }

    /**
     * Allows doctrine to listen for property changes
     *
     * @param PropertyChangedListener $listener
     */
    public function addPropertyChangedListener(Common\PropertyChangedListener $listener)
    {
        $this->_listeners[] = $listener;
    }

    /**
     * {@inheritdoc}
     * 
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->getId();
    }

    /**
     * Sets properties in bulk from an array
     *
     * @param array $array
     */
    public function setData (array $array) {
        foreach ($array as $key => $value) {
            if (property_exists($this, $key)) {
                $this->__set($key, $value);
            }
        }
    }

    /**
     * Disable setter
     */
    public function setId()
    {
        throw new \Core\Model\Exception('Id is not allowed to be set.');
    }
}