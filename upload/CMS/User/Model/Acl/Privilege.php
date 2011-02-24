<?php

namespace User\Model\Acl;

/**
 * Representation of a privilege that can be permitted to
 *
 * @package     CMS
 * @subpackage  User
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="privilege")
 *
 * @property int $id
 */
class Privilege extends \Core\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Column(name="sysname", type="string", length="50", nullable="false", unique="true")
     */
    protected $sysname;

    /**
     * @var Core\Model\Module
     * @ManyToOne(targetEntity="Core\Model\Module")
     * @JoinColumn(name="module", referencedColumnName="sysname", nullable="false")
     */
    protected $module;

    /**
     * @var string
     * @Column(name="description", type="string", length="500", nullable="false")
     */
    protected $description;

    /**
     *
     * @param string $sysname
     * @param string $description
     */
    public function __construct($sysname, $description='')
    {
        $this->setSysname($sysname);
        $this->setDescription($description);
    }

    /**
     * @param string $sysname
     * @return Privilege
     */
    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(0, 50);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname must be between 0 and 50 characters long.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    /**
     * @param Module $module
     * @return Privilege
     */
    public function setModule(\Core\Model\Module $module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * @param string $description
     * @return Privilege
     */
    public function setDescription($description = '')
    {
        $validator = new \Zend_Validate_StringLength(0, 500);
        if (!$validator->isValid($description)) {
            throw new \Core\Model\Exception('Description must be between 0 and 500 characters long.');
        }
        $this->description = $description;
        return $this;
    }
}