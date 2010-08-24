<?php

namespace Core\Model;

use \Core\Model;

/**
 * A zend route that is persistable
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity(repositoryClass="Core\Repository\Route")
 * @HasLifecycleCallbacks
 *
 * @property int $id
 * @property string $template
 * @property string $sysname
 * @property bool $isDirect
 */
class Route
    extends \Zend_Controller_Router_Route
    implements Model\IdentifiableInterface
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The string that defines how the route is accessed as a URL. Can contain parameters defined
     * by :<param>. Template's must be unique and not have interfering parameters (blog/:test and
     * blog/:id interfere with each other).
     * 
     * @var string
     * @Column(name="template", type="string", length="150", unique="true")
     */
    protected $template;

    /**
     * @var string
     * @Column(name="sysname", type="string", length="100", nullable="true", unique="true")
     */
    protected $sysname;

    /**
     * @var bool
     * @Column(name="is_direct", type="boolean", nullable="false")
     */
    protected $isDirect;

    /**
     *
     * @var array
     * @OneToMany(targetEntity="Core\Model\PageRoute", mappedBy="route", cascade={"persist", "update"})
     */
    protected $pageRoutes;

    /**
     * @var string
     * @Column(name="module", type="string", length="50", nullable="false")
     */
    protected $module;

    /**
     * @var string
     * @Column(name="controller", type="string", length="50", nullable="false")
     */
    protected $controller;

    /**
     * @var string
     * @Column(name="action", type="string", length="50", nullable="false")
     */
    protected $action;

    /*
     * this needs to be overridden to take in no params so that we can load from database
     */
    public function __construct ($route, $sysname = null, $defaults = array(), $reqs = array(), \Zend_Translate $translator = null, $locale = null)
    {
        $this->template = $route;
        $this->setSysname($sysname);
        $this->pageRoutes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setIsDirect(false);

        // set default module/controller/view
        $module = isset($defaults['module'])?$defaults['module']:'default';
        $this->setModule($module);
        $controller = isset($defaults['controller'])?$defaults['controller']:'page';
        $this->setController($controller);
        $action = isset($defaults['action'])?$defaults['action']:'view';
        $this->setAction($action);

        $this->postLoad();
    }

    /**
     * Calls the parent constructor to parse the template and set the defaults
     * 
     * @PostLoad
     */
    public function postLoad ()
    {
        parent::__construct($this->template, array('module' => $this->getModule(),
                                                   'controller' => $this->getController(),
                                                   'action' => $this->getAction()), array(), null, null);
    }

    /**
     * Routes to a page given the parameters
     *
     * @param Page $page
     * @param array $params
     * @return PageRoute
     */
    public function routeTo(Page $page, $params = array())
    {
        $pageRoute = new PageRoute($this, $page);
        $pageRoute->setParams($params);
        $this->pageRoutes[] = $pageRoute;
        
        return $pageRoute;
    }

    /**
     * Checks route against a URI. Tests as static route first and then as paramterized route
     *
     * @param string $path
     * @param bool $partial
     * @return array
     */
    public function match ($path, $partial = false)
    {
        //match static
        if (trim($path, '/') == $this->template) {
            $this->_defaults['routeId'] = $this->id;
            return $this->_defaults;
        }

        //match route
        $m = parent::match($path, $partial);
        if (is_array($m)) {
            $m['routeId'] = $this->id;
        }
        return $m;
    }

    /**
     *
     * @param string $template
     * @return Route
     */
    public function setTemplate($template)
    {
        throw new \Exception('Template cannot be changed. Remove this route and create a new one.');
    }

    /**
     *
     * @param string $sysname
     * @return Route
     */
    public function setSysname($sysname = null)
    {
        if (null !== $sysname) {
            $validator = new \Zend_Validate_StringLength(0, 100);
            if (!$validator->isValid($sysname)) {
                throw new \Core\Model\Exception('Sysname must be between 0 and 100 characters.');
            }
        }
        $this->sysname = $sysname;
        return $this;
    }

    /**
     *
     * @param array $pageRoutes
     */
    public function setPageRoutes($pageRoutes)
    {
        throw new \Core\Model\Exception('Please use RouteTo() to add pageRoutes.');
    }

    /**
     *
     * @param bool $isDirect
     * @return Route
     */
    public function setIsDirect($isDirect)
    {
        if (!is_bool($isDirect)) {
            throw new \Core\Model\Exception('isDirect must be boolean');
        }
        $this->isDirect = $isDirect;
        return $this;
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
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->id;
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