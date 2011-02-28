<?php

namespace Asset\Model;

/**
 * Represents a file extension
 *
 * @package     CMS
 * @subpackage  Asset
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="asset_extension")
 * @property string $sysname
 */
class Extension extends \Core\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="sysname", type="string", length="5")
     */
    protected $sysname;

    /**
     * @var Asset\Model\MimeType
     * @ManyToOne(targetEntity="Asset\Model\MimeType", inversedBy="extensions")
     * @JoinColumn(name="mime_type", referencedColumnName="sysname", nullable="false")
     */
    protected $mimeType;

    /**
     * @param string $sysname
     * @param MimeType $mimeType
     */
    public function __construct($sysname)
    {
        $this->setSysname($sysname);
    }

    /**
     * @param string $sysname
     * @return Extension
     */
    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(0, 5);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname (' . $sysname . ') must be between 0 and 5 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    /**
     * @param MimeType $mimeType
     * @return Extension
     */
    public function setMimeType(MimeType $mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }
}