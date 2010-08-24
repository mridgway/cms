<?php

namespace Core\Model\Layout;

/**
 * Represents a block container on a layout
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity
 * @property string $sysname
 */
class Location extends \Core\Model\AbstractModel
{
    /**
     * @var string
     * @Id @Column(name="sysname", type="string", length="50")
     */
    protected $sysname;

    protected $content = '';

    public function __construct($sysname)
    {
        $this->setSysname($sysname);
    }

    /**
     *
     * @param string $sysname
     * @return Location
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

    public function addContent($content)
    {
        $this->content .= (String)$content;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getSysname();
    }
}