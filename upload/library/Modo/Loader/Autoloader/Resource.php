<?php
/**
 * Modo CMS
 */

namespace Modo\Loader\Autoloader;

/**
 * Temporary resource class that gives all the functionality of ZF's traditional
 * resource autoloader but also supports resources that are defined in PHP 5.3
 * namespaces.
 *
 * @todo Replace this class whenever Zend Framework is updated to support
 *       namespaced resource autoloading.
 *
 * @category   Modo
 * @package    Loader
 * @subpackage Autoloader
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Resource.php 142 2010-02-01 14:12:09Z court $
 */
class Resource extends \Zend_Loader_Autoloader_Resource
{
    /**
     * @var string The type of namespace separator
     */
    protected $_namespaceSeparator = '_';


    /**
     * {@inheritdoc}
     *
     * @param  array|Zend_Config $options Configuration options for resource autoloader
     * @return void
     */
    public function __construct($options)
    {
        if ($options instanceof \Zend_Config) {
            $options = $options->toArray();
        }
        if (!is_array($options)) {
            require_once 'Zend/Loader/Exception.php';
            throw new \Zend_Loader_Exception('Options must be passed to resource loader constructor');
        }

        $this->setOptions($options);

        $namespace = $this->getNamespace();
        if ((null === $namespace)
            || (null === $this->getBasePath())
        ) {
            require_once 'Zend/Loader/Exception.php';
            throw new \Zend_Loader_Exception('Resource loader requires both a namespace and a base path for initialization');
        }

        if (!empty($namespace)) {
            $namespace .= $this->getNamespaceSeparator();
        }
        \Zend_Loader_Autoloader::getInstance()->unshiftAutoloader($this, $namespace);
    }

    /**
     * {@inheritdoc}
     *
     * @param string $class
     * @return False if not matched other wise the correct path
     */
    public function getClassPath($class)
    {
        $segments           = explode('_', str_replace('\\', '_', $class));
        $namespaceTopLevel  = $this->getNamespace();
        $namespace          = '';
        $namespaceSeparator = $this->getNamespaceSeparator();

        if (!empty($namespaceTopLevel)) {
            $namespace = array_shift($segments);
            if ($namespace != $namespaceTopLevel) {
                // wrong prefix? we're done
                return false;
            }
        }

        if (count($segments) < 2) {
            // assumes all resources have a component and class name, minimum
            return false;
        }

        $final     = array_pop($segments);
        $component = $namespace;
        $lastMatch = false;
        do {
            $segment    = array_shift($segments);
            $component .= empty($component) 
                        ? $segment
                        : $namespaceSeparator . $segment;
            if (isset($this->_components[$component])) {
                $lastMatch = $component;
            }
        } while (count($segments));

        if (!$lastMatch) {
            return false;
        }

        $final = substr($class, strlen($lastMatch) + 1);
        $path = $this->_components[$lastMatch];
        $classPath = $path . '/'
                   . str_replace($namespaceSeparator, '/', $final) . '.php';

        if (\Zend_Loader::isReadable($classPath)) {
            return $classPath;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $namespace
     * @return Zend_Loader_Autoloader_Resource
     */
    public function setNamespace($namespace)
    {
        $separator = '_';
        $namespace = (string) $namespace;

        if (substr($namespace, -1) == '\\') {
            $separator = '\\';
        }

        $namespace = rtrim($namespace, '_\\');

        $this->_namespace = $namespace;
        $this->_setNamespaceSeparator($separator);

        return $this;
    }

    /**
     * Set the separator to be used for the current namespace
     *
     * @param  string $separator
     * @return Zend_Loader_Autoloader_Resource
     */
    protected function _setNamespaceSeparator($separator)
    {
        $this->_namespaceSeparator = (string) $separator;
        return $this;
    }

    /**
     * Get the separator to be used for the current namespace
     *
     * @return string
     */
    public function getNamespaceSeparator()
    {
        return $this->_namespaceSeparator;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $type identifier for the resource type being loaded
     * @param  string $path path relative to resource base path containing the resource types
     * @param  null|string $namespace sub-component namespace to append to base namespace that qualifies this resource type
     * @return Zend_Loader_Autoloader_Resource
     */
    public function addResourceType($type, $path, $namespace = null)
    {
        $type = strtolower($type);
        if (!isset($this->_resourceTypes[$type])) {
            if (null === $namespace) {
                require_once 'Zend/Loader/Exception.php';
                throw new \Zend_Loader_Exception('Initial definition of a resource type must include a namespace');
            }
            $namespaceTopLevel  = $this->getNamespace();
            $namespaceSeparator = $this->getNamespaceSeparator();
            $namespace = ucfirst(trim($namespace, $namespaceSeparator));
            $this->_resourceTypes[$type] = array(
                'namespace' => empty($namespaceTopLevel)
                            ?  $namespace
                            :  $namespaceTopLevel . $namespaceSeparator . $namespace,
            );
        }
        if (!is_string($path)) {
            require_once 'Zend/Loader/Exception.php';
            throw new \Zend_Loader_Exception('Invalid path specification provided; must be string');
        }
        $this->_resourceTypes[$type]['path'] = $this->getBasePath() . '/' . rtrim($path, '\/');

        $component = $this->_resourceTypes[$type]['namespace'];
        $this->_components[$component] = $this->_resourceTypes[$type]['path'];
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $resource
     * @param  string $type
     * @return object
     * @throws Zend_Loader_Exception if resource type not specified or invalid
     */
    public function load($resource, $type = null)
    {
        if (null === $type) {
            $type = $this->getDefaultResourceType();
            if (empty($type)) {
                require_once 'Zend/Loader/Exception.php';
                throw new \Zend_Loader_Exception('No resource type specified');
            }
        }
        if (!$this->hasResourceType($type)) {
            require_once 'Zend/Loader/Exception.php';
            throw new \Zend_Loader_Exception('Invalid resource type specified');
        }
        $namespace = $this->_resourceTypes[$type]['namespace'];
        $class     = $namespace . $this->getNamespaceSeparator() . ucfirst($resource);
        if (!isset($this->_resources[$class])) {
            $this->_resources[$class] = new $class;
        }
        return $this->_resources[$class];
    }
}