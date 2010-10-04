<?php

namespace User\Model\Acl\RoleAssignment;

/**
 * Represenation of an acl role for a user group
 *
 * @package     CMS
 * @subpackage  User
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     http://github.com/modo/cms/blob/master//LICENSE    New BSD License
 *
 * @Entity
 *
 * @property User\Model\Group $group
 */
class GroupRoleAssignment extends AbstractRoleAssignment
{
    /**
     * @var User\Model\Group
     * @ManyToOne(targetEntity="User\Model\Group")
     * @JoinColumn(name="grp", referencedColumnName="id", nullable="false")
     */
    protected $group;

    /**
     * @param Group $group
     * @param Role $role
     */
    public function __construct(\User\Model\Group $group, \User\Model\Acl\Role $role)
    {
        $this->setGroup($group);
        parent::__construct($role);
    }

    /**
     * @param Group $group
     * @return GroupRoleAssignment
     */
    public function setGroup(\User\Model\Group $group)
    {
        $this->group = $group;
        return $this;
    }
}