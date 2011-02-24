<?php

namespace User\Model\Acl;

/**
 * Representation of a role that can have permissions to resources
 *
 * @package     CMS
 * @subpackage  User
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="role")
 *
 * @property int $id
 */
class Role extends \Core\Model\AbstractModel implements \Zend_Acl_Role_Interface
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var integer
     * @Column(name="sysname", type="string", length="500", nullable="FALSE", unique="TRUE")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(name="description", type="string", length="500", nullable="FALSE")
     */
    protected $description;

    /**
     * @param string $type
     * @param string $identity
     */
    public function __construct($sysname, $description = '')
    {
        $this->setSysname($sysname);
        $this->setDescription($description);
    }

    /**
     * @param string $sysname
     * @return Role
     */
    public function setSysname($sysname)
    {
        $validator = new \Zend_Validate_StringLength(1,50);
        if (!$validator->isValid($sysname)) {
            throw new \Core\Model\Exception('Sysname must be between 1 and 50 characters.');
        }
        $this->sysname = $sysname;
        return $this;
    }

    /**
     * @param string $description
     * @return Role
     */
    public function setDescription($description = '')
    {
        $validator = new \Zend_Validate_StringLength(0,500);
        if (!$validator->isValid($description)) {
            throw new \Core\Model\Exception('Description must be between 0 and 500 characters.');
        }
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoleId()
    {
        return $this->getSysname();
    }
}