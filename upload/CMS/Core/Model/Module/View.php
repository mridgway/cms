<?php

namespace Core\Model\Module;

use \Core\Model;

/**
 * Represents a a view script that is installed in the system. Creates instances
 * of Zend_View that blocks use to display their content.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity(repositoryClass="Core\Repository\Module\View")
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
     * @var Core\Model\Module\Resource
     * @ManyToOne(targetEntity="Core\Model\Module\Resource", inversedBy="views")
     * @JoinColumn(referencedColumnName="id")
     */
    protected $resource;

    /**
     * @var string
     * @Column(name="sysname", type="string", length="100", nullable="false")
     */
    protected $sysname;

    /**
     * @param string Core\Model\Module\Resource
     * @param string $sysname
     */
    public function __construct(Resource $resource, $sysname)
    {
        $this->resource = $resource;
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
              . $this->resource->module->sysname . \DIRECTORY_SEPARATOR
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
        $file = $this->resource->getResourceString() . \DIRECTORY_SEPARATOR;
        $file .= $this->resource->discriminator . \DIRECTORY_SEPARATOR;
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

        return $view;
    }
}