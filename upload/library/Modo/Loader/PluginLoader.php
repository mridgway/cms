<?php
/**
 * Modo CMS
 */

namespace Modo\Loader;

/**
 * Temporary plugin loader that gives all the functionality of ZF's traditional
 * pluginloader but also supports plugins that are defined in PHP 5.3
 * namespaces.
 *
 * @todo Replace this class whenever Zend Framework is updated to support
 *       namespaced pluginloading.
 *
 * @category   Modo
 * @package    Loader
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: PluginLoader.php 148 2010-02-02 17:38:42Z court $
 */
class PluginLoader extends \Zend_Loader_PluginLoader
{
    /**
     * {@inheritdoc}
     *
     * @param  string $prefix
     * @return string
     */
    protected function _formatPrefix($prefix)
    {
        if ($prefix == "" || substr($prefix, -1) == '\\') {
            return $prefix;
        }

        return rtrim($prefix, '_') . '_';
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $name
     * @param  bool $throwExceptions Whether or not to throw exceptions if the
     * class is not resolved
     * @return string|false Class name of loaded class; false if $throwExceptions
     * if false and no class found
     * @throws Zend_Loader_Exception if class not found
     */
    public function load($name, $throwExceptions = true)
    {
        $name  = $this->_formatName($name);

        if ($this->isLoaded($name)) {
            return $this->getClassName($name);
        }

        if ($this->_useStaticRegistry) {
            $registry = self::$_staticPrefixToPaths[$this->_useStaticRegistry];
        } else {
            $registry = $this->_prefixToPaths;
        }

        $registry  = array_reverse($registry, true);
        $found     = false;
        $classFile = str_replace('_', DIRECTORY_SEPARATOR, str_replace('\\', '_', $name)) . '.php';
        $incFile   = self::getIncludeFileCache();
        foreach ($registry as $prefix => $paths) {
            $className = $prefix . $name;

            if (class_exists($className, false)) {
                $found = true;
                break;
            }

            $paths     = array_reverse($paths, true);

            foreach ($paths as $path) {
                $loadFile = $path . $classFile;
                if (\Zend_Loader::isReadable($loadFile)) {
                    include_once $loadFile;
                    if (class_exists($className, false)) {
                        if (null !== $incFile) {
                            self::_appendIncFile($loadFile);
                        }
                        $found = true;
                        break 2;
                    }
                }
            }
        }

        if (!$found) {
            if (!$throwExceptions) {
                return false;
            }

            $message = "Plugin by name '$name' was not found in the registry; used paths:";
            foreach ($registry as $prefix => $paths) {
                $message .= "\n$prefix: " . implode(PATH_SEPARATOR, $paths);
            }
            require_once 'Zend/Loader/PluginLoader/Exception.php';
            throw new \Zend_Loader_PluginLoader_Exception($message);
       }

        if ($this->_useStaticRegistry) {
            self::$_staticLoadedPlugins[$this->_useStaticRegistry][$name]     = $className;
            self::$_staticLoadedPluginPaths[$this->_useStaticRegistry][$name] = (isset($loadFile) ? $loadFile : '');
        } else {
            $this->_loadedPlugins[$name]     = $className;
            $this->_loadedPluginPaths[$name] = (isset($loadFile) ? $loadFile : '');
        }
        return $className;
    }
}