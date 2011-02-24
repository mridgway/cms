<?php

namespace Core\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Represents a structure that is available to pages. Contains multiple locations that blocks can
 * be inserted into.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="layout")
 * @HasLifecycleCallbacks
 * @property int $id
 * @property string $sysname
 * @property array $locations
 */
class Layout extends \Zend_Layout
{
    /**
     * @var string
     * @Id @Column(name="sysname", type="string" , length="50", unique="true", nullable="false")
     */
    protected $sysname;
    /**
     * @var string
     * @Column(name="title", type="string" , length="100", nullable="true")
     */
    protected $title;

    /**
     * @ManyToMany(targetEntity="Core\Model\Layout\Location", fetch="EAGER", cascade={"all", "persist"})
     * @JoinTable(name="layout_locations",
     *      joinColumns={@JoinColumn(name="layout", referencedColumnName="sysname")},
     *      inverseJoinColumns={@JoinColumn(name="location", referencedColumnName="sysname")}
     *      )
     */
    protected $locations;

    /**
     *
     * @param string $sysname
     */
    public function __construct($sysname = '')
    {
        $this->setSysname($sysname);
        $this->setLocations(new ArrayCollection);
        $this->postLoad();
    }

    /**
     * @PostLoad
     */
    public function postLoad()
    {
        parent::__construct();
    }

    public function render($name = null)
    {
        if (null == $name) {
            $this->setLayoutPath($this->getBasePath());
            $name = $this->getFile();
        }
        foreach ($this->locations AS $location) {
            $this->assign($location->sysname, $location->content);
        }
        return parent::render($name);
    }

    /**
     * Adds a location to the layout
     *
     * @param Layout\Location $location
     */
    public function addLocation(Layout\Location $location)
    {
        $this->locations[] = $location;
    }

    /**
     * Removes a location to the layout
     *
     * @param string $location
     */
    public function removeLocation($sysname)
    {
        foreach ($this->locations AS $key => $location) {
            if ($location->sysname == $sysname) {
                unset($this->locations[$key]);
                break;
            }
        }
    }

    public function getBasePath()
    {
        return APPLICATION_ROOT . "/themes/default/layouts/scripts";
    }

    /**
     * Gets the file location relative to the base layout path
     *
     * @return string
     */
    public function getFile()
    {
        return $this->sysname;
    }


    /**
     * @param array $locations
     * @return Layout
     */
    public function setLocations($locations = null)
    {
        if (null !== $locations) {
            foreach ($locations AS $location) {
                if (!($location instanceof \Core\Model\Layout\Location)) {
                    throw new \Core\Model\Exception('Location array contains invalid locations.');
                }
            }
            $this->locations = null;
            foreach($locations AS $location) {
                $this->addLocation($location);
            }
        } else {
            $this->locations = null;
        }
        return $this;
    }

    /**
     * @param string $sysname
     * @return Layout
     */
    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(1, 50);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname must be between 1 and 50 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    /**
     * @param string $title
     * @return Layout
     */
    public function setTitle($title = null)
    {
        if (null !== $title) {
            $validator = new \Zend_Validate_StringLength(1, 100);
            if (!$validator->isValid($title)) {
                throw new \Core\Model\Exception('Sysname must be between 1 and 100 characters.');
            }
        }
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if (null === $this->title) {
            return $this->sysname;
        }
        return $this->title;
    }

    /**
     * @param string $name
     * @return Layout\Location
     */
    public function getLocation($name)
    {
        foreach ($this->locations AS $location) {
            if ($location->sysname == $name) {
                return $location;
            }
        }
        return null;
    }

    /**************************************************
     * Imported from AbstractModel
     **************************************************/

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
        } else if (property_exists($this, $name)) {
            return $this->{$name};
        }
        return parent::__get($name);
    }

    /**
     * Allows isset() on properties access by magic getters.
     *
     * @param string $name
     * @return bool
     */
    public function __isset ($name) {
        if (property_exists($this, $name) && isset($this->{$name})) {
            return true;
        }
        parent::__isset($name);
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
        } else if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }

        return parent::__set($name, $value);
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
        return parent::__call($name, $args);
    }

    /**
     * Allows doctrine to listen for property changes
     *
     * @param PropertyChangedListener $listener
     */
    public function addPropertyChangedListener(\Doctrine\Common\PropertyChangedListener $listener)
    {
        $this->_listeners[] = $listener;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getSysname();
    }

    /**
     * Sets properties in bulk from an array
     *
     * @param array $array
     */
    public function setData (array $array) {
        foreach ($array as $key => $value) {
            if (property_exists($this, $key))
                $this->__set($key, $value);
        }
    }
}