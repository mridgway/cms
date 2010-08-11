<?php
/**
 * Modo CMS
 */

namespace Core\Model;

/**
 * A page template
 *
 * @category   Model
 * @package    Core
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: Template.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 */
class Template extends AbstractPage implements \Modo\Orm\Model\VersionableInterface
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
            throw new \Modo\Model\Exception('Sysname cannot be null.');
        }
        $sysnameLength = new \Zend_Validate_StringLength(3, 50);
        if (!$sysnameLength->isValid($sysname)) {
            throw new \Modo\Model\Exception('Sysname must be between 3 and 50 characters.');
        }
        /* UNTESTABLE... SHOULD BE IN WHATEVER CALLS THIS
        $uniqueSysname = new \Core\Validator\UniqueTemplateSysname();
        if (!$uniqueSysname->isValid($sysname)) {
            throw new \Modo\Model\Exception('Sysname is taken.');
        }
        */
        $this->sysname = $sysname;
    }
}