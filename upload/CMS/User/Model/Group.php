<?php

namespace User\Model;

/**
 * Representation of a user group that can have roles assigned to it
 *
 * @package     CMS
 * @subpackage  User
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity(repositoryClass="User\Repository\Group")
 * @Table(name="user_group")
 *
 * @property int $id
 * @property string $name
 * @property array $roleAssignments
 * @property array $roles
 */
class Group extends \Core\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Column(type="string", length="50", nullable="FALSE")
     */
    protected $sysname;

    /**
     * @var string
     * @Column(name="name", type="string", length="50")
     */
    protected $name;

    /**
     * @var Acl\RoleAssignMent\UserRoleAssignment
     * @OneToMany(targetEntity="User\Model\Acl\RoleAssignment\GroupRoleAssignment", mappedBy="group", cascade={"persist", "update", "delete"})
     */
    protected $roleAssignments;

    /**
     * @var array|null
     */
    protected $roles = null;

    /**
     * @param string $type
     * @param string $identity
     */
    public function __construct($sysname, $name)
    {
        $this->setSysname($sysname);
        $this->setName($name);
        $this->setRoleAssignments(new \Doctrine\Common\Collections\ArrayCollection());
    }

    /**
     * @param string $name
     * @return Group
     */
    public function setName($name)
    {
        $validator = new \Zend_Validate_StringLength(1,50);
        if (!$validator->isValid($name)) {
            throw new \Core\Model\Exception('Group name must be between 1 and 50 characters.');
        }
        $this->name = $name;
        return $this;
    }

    /**
     * Gets a list of roles for this group
     *
     * @return array
     */
    public function getRoles()
    {
        // Load roles if they are not loaded yet
        if (null === $this->roles) {
            $this->roles = array();
            foreach ($this->roleAssignments AS $roleAssignment) {
                $this->roles[] = $roleAssignment->role;
            }
        }
        return $this->roles;
    }

    /**
     * @param Role $role
     * @return Group
     */
    public function addRole(\User\Model\Acl\Role $role)
    {
        if (!in_array($role, $this->getRoles())) {
            $this->roleAssignments[] = new \User\Model\Acl\RoleAssignment\GroupRoleAssignment($this, $role);
            $this->roles[] = $role;
        }
        return $this;
    }

    /**
     * @return Group
     */
    public function removeRole(\User\Model\Acl\Role $role)
    {
        foreach ($this->roleAssignments AS $key => $roleAssignment) {
            if ($roleAssignment->role == $role) {
                unset($this->roleAssignments[$key]);
            }
        }
        if (null !== $this->roles) {
            foreach ($this->roles AS $key => $r) {
                if ($r == $role) {
                    unset($this->roles[$key]);
                }
            }
        }
        return $this;
    }
}