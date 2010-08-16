<?php
/**
 * Modo CMS
 */

namespace Core\Model;

use \Core\Model;

/**
 * Description of View
 *
 * @category   View
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: View.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity(repositoryClass="Core\Repository\View")
 * @property int $id
 * @property string $module
 * @property string $type
 * @property string $sysname
 * @property string $label
 */
class View extends Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Column(name="module", type="string", length="100", nullable="false")
     */
    protected $module;

    /**
     * @var string
     * @Column(name="type", type="string", length="100", nullable="false")
     */
    protected $type;

    /**
     * @var string
     * @Column(name="sysname", type="string", length="100", nullable="false")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(name="label", type="string", length="100", nullable="true")
     */
    protected $label;

    /**
     * @var Zend_Loader_PluginLoader
     */
    private static $_pluginLoader = array();

    /**
     * @param string $module
     * @param string $type
     * @param string $sysname
     */
    public function __construct($module, $type, $sysname)
    {
        $this->module = $module;
        $this->type = $type;
        $this->sysname = $sysname;
    }

    /**
     * Returns the view script location
     *
     * @return string
     */
    public function getBasePath()
    {
        $file = \APPLICATION_PATH . \DIRECTORY_SEPARATOR
              . $this->module . \DIRECTORY_SEPARATOR
              . 'View' . \DIRECTORY_SEPARATOR;

        return $file;
    }

    /**
     * Returns the view script relative to the base view path
     *
     * @return string
     */
    public function getFile()
    {
        $file = $this->type . \DIRECTORY_SEPARATOR;
        $file .= strtolower($this->sysname).'.phtml';

        return $file;
    }

    public function getInstance()
    {
        /* @var $view Zend_View */
        $view;
        if (\Zend_Controller_Front::getInstance()->getParam('bootstrap')) {
            $view =  clone \Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('view');
        } else {
            $view = new \Zend_View();
        }
        $view->clearVars();
        $view->addBasePath($this->getBasePath());
        if ($helperLoader = self::getPluginLoader('helper')) {
            $view->setPluginLoader($helperLoader, 'helper');
        }
        if ($filterLoader = self::getPluginLoader('filter')) {
            $view->setPluginLoader(self::getPluginLoader('filter'), 'filter');
        }
        return $view;
    }

    public static function setPluginLoader($pluginLoader, $type)
    {
        self::$_pluginLoader[$type] = $pluginLoader;
    }

    public static function getPluginLoader($type)
    {
        return array_key_exists($type, self::$_pluginLoader) ? self::$_pluginLoader[$type] : false;
    }
}