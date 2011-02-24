<?php

namespace Core\Model;

/**
* A view script model that can be created on the fly
*
* @package CMS
* @subpackage Core
* @category Model
* @copyright Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
* @license http://github.com/modo/cms/blob/master//LICENSE    New BSD License
*/
class View extends \Zend_View
{

    /**
    * @var string
    */
    protected $module;

    /**
    * @var string
    */
    protected $path;

    /**
    * @param string $module
    * @param string $path
    */
    public function __construct($module, $path)
    {
        $this->module = $module;
        $this->path = $path;

        $this->setBasePath($this->getBasePath());
    }

    public function render($name = null)
    {
        if (null === $name) {
            $name = $this->getFile();
        }
        return parent::render($name);
    }

    /**
    * Returns the view script location
    *
    * @return string
    */
    public function getBasePath()
    {
        $path = \APPLICATION_PATH . \DIRECTORY_SEPARATOR
              . $this->module . \DIRECTORY_SEPARATOR
              . 'View' . \DIRECTORY_SEPARATOR;

        return $path;
    }

    /**
    * Returns the view script relative to the base view path
    *
    * @return string
    */
    public function getFile()
    {
        $file = $this->path.'.phtml';

        return $file;
    }

    public static function renderScript ($module, $path, $data = array())
    {
        $view = new self($module, $path);
        $view->assign($data);
        return $view->render();
    }
}