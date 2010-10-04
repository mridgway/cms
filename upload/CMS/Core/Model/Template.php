<?php

namespace Core\Model;

/**
 * A template that can be used to create pages with the same blocks
 *
 * @package     CMS
 * @subpackage  Core
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 */
class Template extends AbstractPage
{
    /**
     * Allows a module to uniquely grab its template to create new pages.
     *
     * @var string
     * @Column(name="sysname", type="string", length="50", nullable="false", unique="true")
     */
    protected $sysname;

    public function __construct($sysname, Layout $layout)
    {
        $this->setSysname($sysname);
        parent::__construct($layout);
    }

    public function setSysname($sysname)
    {
        if (null == $sysname) {
            throw new \Core\Model\Exception('Sysname cannot be null.');
        }
        $sysnameLength = new \Zend_Validate_StringLength(3, 50);
        if (!$sysnameLength->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname must be between 3 and 50 characters.');
        }
        /* UNTESTABLE... SHOULD BE IN WHATEVER CALLS THIS
        $uniqueSysname = new \Core\Validator\UniqueTemplateSysname();
        if (!$uniqueSysname->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname is taken.');
        }
        */
        $this->sysname = $sysname;
    }
}