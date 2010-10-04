<?php

namespace Asset\Model;

/**
 * Represents an Asset
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity(repositoryClass="Asset\Repository\Asset")
 * @Table(name="Asset")
 * @HasLifecycleCallbacks
 * 
 * @property int $id
 * @property string $sysname
 * @property string $name
 * @property Asset\Model\Type $type
 * @property Asset\Model\Group $group
 * @property Asset\Model\MimeType $mimeType
 * @property string $caption
 */
class Asset extends \Core\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Column(name="sysname", type="string", length="64", nullable="false")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(name="name", type="string", length="150", nullable="false")
     */
    protected $name;

    /**
     * @var string
     * @OneToOne(targetEntity="Asset\Model\Extension")
     * @JoinColumn(name="extension", referencedColumnName="sysname", nullable="false")
     */
    protected $extension;

    /**
     * @var Asset\Model\Group
     * @ManyToOne(targetEntity="Asset\Model\Group")
     * @JoinColumn(name="grp", referencedColumnName="sysname", nullable="false")
     */
    protected $group;

    /**
     * @var string
     * @ManyToOne(targetEntity="Asset\Model\MimeType")
     * @JoinColumn(name="mime_type", referencedColumnName="sysname", nullable="false")
     */
    protected $mimeType;

    /**
     * @var string
     * @Column(name="caption", type="string", length="255", nullable="true")
     */
    protected $caption;

    /**
     *
     * @var DateTime
     * @Column(name="upload_date", type="datetime", nullable="false")
     */
    protected $uploadDate;

    /**
     *
     * @param string $sysname
     * @param string $name
     * @param Group $group
     * @param MimeType $mimeType
     */
    public function __construct($sysname, $name, $extension, Group $group, MimeType $mimeType)
    {
        $this->setSysname($sysname);
        $this->setName($name);
        $this->setExtension($extension);
        $this->setGroup($group);
        $this->setMimeType($mimeType);
        $this->setUploadDate();
    }

    /**
     * @param string $sysname
     * @return Asset
     */
    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(0, 64);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname must be between 0 and 64 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    /**
     * @param string $name
     * @return Asset
     */
    public function setName($name)
    {
        $validator = new \Zend_Validate_StringLength(0, 150);
        if (!$validator->isValid($name)) {
            throw new \Core\Model\Exception('Name must be between 0 and 150 characters.');
        }
        $this->name = $name;
        return $this;
    }

    /**
     * @param Extension $ext
     * @return Asset
     */
    public function setExtension(Extension $ext)
    {
        $this->extension = $ext;
        return $this;
    }

    /**
     * @param Group $group
     * @return Asset
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @param MimeType $mimeType
     * @return Asset
     */
    public function setMimeType(MimeType $mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * @param string $caption
     * @return Asset
     */
    public function setCaption($caption = null)
    {
        if (null !== $caption) {
            $validator = new \Zend_Validate_StringLength(0, 255);
            if (!$validator->isValid($caption)) {
                throw new \Core\Model\Exception('Caption must be between 0 and 255 characters.');
            }
        }
        $this->caption = $caption;
        return $this;
    }

    /**
     * @PrePersist
     * @param DateTime $date
     * @return Asset
     */
    public function setUploadDate(\DateTime $date = null)
    {
        if (null === $date){
            $date = new \DateTime();
        }
        $this->uploadDate = $date;
        return $this;
    }

    public function getLocation($sizeName = 'original')
    {
        return $this->group->getLocation()
            . DIRECTORY_SEPARATOR . substr($this->getSysname(), 0, 2)
            . DIRECTORY_SEPARATOR . $this->getSysname()
            . DIRECTORY_SEPARATOR . $sizeName . '.' . $this->extension->sysname;
    }

    public function getUrl($sizeName = 'original')
    {
        return '/assets'
             . '/' . $this->getGroup()->getSysname()
             . '/' . substr($this->getSysname(), 0, 2)
             . '/' . $this->getSysname()
             . '/' . $sizeName . '.' . $this->extension->sysname;
    }
}