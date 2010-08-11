<?php
/**
 * Modo CMS
 */

namespace Asset\Model;

/**
 * Description of Group
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Extension.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 * @Table(name="Asset_Extension")
 * @property string $sysname
 */
class Extension extends \Modo\Orm\Model\AbstractModel implements \Modo\Orm\Model\VersionableInterface
{
    /**
     * @var integer
     * @Id @Column(name="sysname", type="string", length="5")
     */
    protected $sysname;

    /**
     * @var Asset\Model\MimeType
     * @ManyToOne(targetEntity="Asset\Model\MimeType")
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
            throw new \Modo\Model\Exception('Sysname (' . $sysname . ') must be between 0 and 5 characters.');
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