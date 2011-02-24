<?php

namespace Core\Model;

/**
 * Represents a module that has been or can be installed in the CMS
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity(repositoryClass="Core\Repository\Module")
 * @Table(name="module")
 * @property string $name;
 * @property Core\Model\Module\BlockType[] $blockTypes
 * @property Core\Model\Module\ContentType[] $contentTypes
 * @property Core\Model\Module\ActivityType[] $activityTypes
 */
class Module
    extends \Core\Model\AbstractModel
    implements \Zend_Acl_Resource_Interface
{
    /**
     * @var string
     * @Id @Column(name="sysname", type="string", length="50", nullable="false")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(name="title", type="string", length="100", nullable="false")
     */
    protected $title;

    /**
     * @var array
     * @OneToMany(targetEntity="Core\Model\Module\Resource", mappedBy="module", cascade={"persist"}, fetch="EAGER")
     */
    protected $resources;

    protected $blockTypes = null;
    protected $contentTypes = null;
    protected $activityTypes = null;

    public function __construct($sysname, $title='')
    {
        $this->setSysname($sysname);
        if ($title == '') {
            $title = $sysname;
        }
        $this->setTitle($title);
        $this->resources = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(0, 50);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Name must be between 0 and 50 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    public function setTitle($title)
    {
        $validator = new \Zend_Validate_StringLength(0, 100);
        if (!$validator->isValid($title)) {
            throw new \Core\Model\Exception('Name must be between 0 and 100 characters.');
        }
        $this->title = $title;
        return $this;
    }

    public function addResource(\Core\Model\Module\Resource $resource)
    {
        $resource->module = $this;
        if ($resource instanceof Core\Model\Module\BlockType) {
            $this->blockTypes[] = $resource;
        } else if ($resource instanceof Core\Model\Module\ContentType) {
            $this->contentTypes[] = $resource;
        } else if ($resource instanceof  Core\Model\Module\ActivityType) {
            $this->activityTypes[] = $resource;
        }
        $this->resources[] = $resource;
    }

    public function getBlockTypes()
    {
        if (null === $this->blockTypes) {
            $this->blockTypes = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($this->resources as $resource) {
                if ($resource instanceof Module\BlockType) {
                    $this->blockTypes->add($resource);
                }
            }
        }
        return $this->blockTypes;
    }

    public function getContentTypes()
    {
        if (null === $this->contentTypes) {
            $this->contentTypes = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($this->resources as $resource) {
                if ($resource instanceof Module\ContentType) {
                    $this->contentTypes->add($resource);
                }
            }
        }
        return $this->contentTypes;
    }

    public function getActivityTypes()
    {
        if (null === $this->activityTypes) {
            $this->activityTypes = new \Doctrine\Common\Collections\ArrayCollection();
            foreach ($this->resources as $resource) {
                if ($resource instanceof Module\ActivityType) {
                    $this->activityTypes->add($resource);
                }
            }
        }
        return $this->activityTypes;
    }

    public function getBlockType($name)
    {
        foreach ($this->getBlockTypes() as $resource) {
            if ($resource->discriminator == $name) {
                return $resource;
            }
        }
        return null;
    }

    public function getContentType($name)
    {
        foreach ($this->getContentTypes() as $resource) {
            if ($resource->discriminator == $name) {
                return $resource;
            }
        }
        return null;
    }

    public function getActivityType($name)
    {
        foreach ($this->getActivityTypes() as $resource) {
            if ($resource->discriminator == $name) {
                return $resource;
            }
        }
        return null;
    }

    public function getBlockDiscriminatorMap()
    {
        $map = array();
        foreach ($this->getBlockTypes() AS $blockType) {
            $map[$blockType->discriminator] = $blockType->class;
        }
        return $map;
    }

    public function getContentDiscriminatorMap()
    {
        $map = array();
        foreach ($this->getContentTypes() AS $contentType) {
            $map[$contentType->discriminator] = $contentType->class;
        }
        return $map;
    }

    public function getActivityDiscriminatorMap()
    {
        $map = array();
        foreach ($this->getActivityTypes() AS $activityType) {
            $map[$activityType->discriminator] = $activityType->class;
        }
        return $map;
    }

    public function getResourceId()
    {
        return $this->getSysname();
    }
}