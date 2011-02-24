<?php

namespace Asset\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Representation of a group of assets
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="asset_group")
 * @property string $sysname
 * @property string $title
 * @property Asset\Model\Size[] $sizes
 */
class Group extends \Core\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="sysname", type="string", length="50")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(name="title", type="string", length="150", nullable="false")
     */
    protected $title;

    /**
     * @var Asset\Model\Size[]
     * @OneToMany(targetEntity="Asset\Model\Size", mappedBy="group", cascade={"persist"})
     */
    protected $sizes;

    public function __construct($sysname, $title)
    {
        $this->setSysname($sysname);
        $this->setTitle($title);
        $this->setSizes(new ArrayCollection);
    }

    /**
     * @param string $sysname
     * @return Group
     */
    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(0, 50);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname must be between 0 and 50 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    /**
     * @param string $title
     * @return Group
     */
    public function setTitle($title)
    {
        $validator = new \Zend_Validate_StringLength(0, 150);
        if (!$validator->isValid($title)) {
            throw new \Core\Model\Exception('Title must be between 0 and 150 characters.');
        }
        $this->title = $title;
        return $this;
    }

    /**
     * @param Size $size
     * @return Group
     */
    public function addSize($sysname, Size $size)
    {
        $size->setSysname($sysname);
        $size->setGroup($this);
        $this->sizes[] = $size;
        return $this;
    }

    public function getLocation()
    {
        return APPLICATION_ROOT
            . DIRECTORY_SEPARATOR . 'data'
            . DIRECTORY_SEPARATOR . 'assets'
            . DIRECTORY_SEPARATOR . $this->getSysname();
    }
}