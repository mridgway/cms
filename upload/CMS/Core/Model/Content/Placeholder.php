<?php

namespace Core\Model\Content;

/**
 * A content type that is used in templates to specify where new blocks will be placed when creating
 * a new page based off of the template.
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity
 * @property string $contentType
 * @property string $sysname
 * @property string $description
 */
class Placeholder extends \Core\Model\Content
{

    /**
     * The identifier used by a module to find and replace this block.
     *
     * @var string
     * @Column(name="sysname", type="string", length="100", nullable="false", unique="true")
     */
    protected $sysname;

    /**
     * The type of content this can hold
     *
     * @var string
     * @Column(name="content_type", type="string", length="150", nullable="false")
     */
    protected $contentType;

    /**
     * A short description of what this placeholder is used for.
     *
     * @var string
     * @Column(name="description", type="string", length="500", nullable="false")
     */
    protected $description;
    

    /**
     *
     * @param string $sysname
     * @param string $contentType
     * @param string $description
     */
    public function __construct($sysname, $contentType, $description = '')
    {
        $this->setSysname($sysname);
        $this->setContentType($contentType);
        $this->setDescription($description);
    }

    /**
     *
     * @param string $sysname
     * @return Placeholder
     */
    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(1, 100);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname must be between 1 and 100 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    /**
     *
     * @param string $contentType
     * @return Placeholder
     */
    public function setContentType($contentType)
    {
        $validator = new \Zend_Validate_StringLength(1, 150);
        if (!$validator->isValid($contentType)) {
            throw new \Core\Model\Exception('ContentType must be between 1 and 100 characters.');
        }
        if (!class_exists($contentType)) {
            throw new \Core\Model\Exception('Class does not exist.');
        }
        $this->contentType = $contentType;
        return $this;
    }

    /**
     *
     * @param string $description
     * @return Placeholder
     */
    public function setDescription($description = '')
    {
        $validator = new \Zend_Validate_StringLength(0, 150);
        if (!$validator->isValid($description)) {
            throw new \Core\Model\Exception('Description must be less than 500 characters.');
        }
        $this->description = $description;
        return $this;
    }
}