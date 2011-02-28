<?php

namespace Asset\Model;

/**
 * Representation of a size for a given asset
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="asset_size")
 * @property int $id
 * @property Asset\Model\Group $group
 * @property string $sysname
 * @property int $height
 * @property int $width
 */
class Size extends \Core\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Asset\Model\Group
     * @ManyToOne(targetEntity="Asset\Model\Group", inversedBy="sizes")
     * @JoinColumn(name="grp", referencedColumnName="sysname")
     */
    protected $group;

    /**
     * @var string
     * @Column(name="sysname", type="string", length="50", nullable="false")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(name="title", type="string", length="100", nullable="true")
     */
    protected $title;

    /**
     * @var int
     * @Column(name="height", type="integer", nullable="false")
     */
    protected $height;

    /**
     * @var int
     * @Column(name="width", type="integer", nullable="false")
     */
    protected $width;

    /**
     * @var boolean
     * @Column(name="crop", type="boolean", nullable="false")
     */
    protected $crop;

    /**
     * @param int $width
     * @param int $height
     * @param bool $crop
     */
        public function __construct($width, $height, $crop = false)
    {
        $this->setHeight($height);
        $this->setWidth($width);
        $this->setCrop($crop);
    }

    /**
     * @param Group $group
     * @return Size
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @param string $sysname
     * @return Size
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
     * @param int $height
     * @return Size
     */
    public function setHeight($height)
    {
        if (!is_int($height) || $height < 0){
            throw new \Core\Model\Exception('Height must be a positive integer.');
        }
        $this->height = $height;
        return $this;
    }

    /**
     * @param int $width
     * @return Size
     */
    public function setWidth($width)
    {
        if (!is_int($width) || $width < 0){
            throw new \Core\Model\Exception('Width must be a positive integer.');
        }
        $this->width = $width;
        return $this;
    }

    /**
     * @param bool $crop
     * @return Size
     */
    public function setCrop($crop = false)
    {
        if (!is_bool($crop)) {
            throw new \Exception('Crop must be a boolean value.');
        }
        $this->crop = $crop;
        return $this;
    }

    /**
     * Gets the title, or the sysname if title is null
     *
     * @return string
     */
    public function getTitle()
    {
        if (null === $this->title) {
            return $this->sysname;
        } else {
            return $this->title;
        }
    }
}