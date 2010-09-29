<?php

namespace Core\Model\Module;

/**
 * Represents a content type that is installed with the module
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity
 * @Table(name="Module_ContentType")
 * @property int $id
 */
class ContentType
    extends Resource
{
    /**
     * @var string
     * @Column(name="controller", type="string", length="100", nullable="true")
     */
    protected $controller;

    public function __construct($title, $discriminator, $class, $controller = null)
    {
        parent::__construct($title, $discriminator, $class);
        $this->setController($controller);
    }

    public function setController($controller)
    {
        if(null !== $controller) {
            if (!class_exists($controller)) {
                throw new \Core\Model\Exception('Class does not exist.');
            }
        }
        $this->controller = $controller;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return $this->getModule()->getResourceId() . '.Content.' . $this->getDiscriminator();
    }
}