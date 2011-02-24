<?php

namespace User\Model\Acl\RoleAssignment;

/**
 * Abstract representation of an acl role
 *
 * @package     CMS
 * @subpackage  User
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 * @Table(name="role_assignment")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"Group" = "User\Model\Acl\RoleAssignment\GroupRoleAssignment", "User" = "User\Model\Acl\RoleAssignment\UserRoleAssignment"})
 *
 * @property int $id
 * @property Role $role
 */
class AbstractRoleAssignment extends \Core\Model\AbstractModel
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User\Model\Acl\Role
     * @ManyToOne(targetEntity="User\Model\Acl\Role")
     * @JoinColumn(name="role", referencedColumnName="id", nullable="false")
     */
    protected $role;

    /**
     * @param Role $role
     */
    public function __construct(\User\Model\Acl\Role $role)
    {
        $this->setRole($role);
    }

    /**
     * @param Role $role
     * @return AbstractRoleAssignment
     */
    public function setRole(\User\Model\Acl\Role $role)
    {
        $this->role = $role;
        return $this;
    }
}