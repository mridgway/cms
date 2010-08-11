<?php
/**
 * Modo CMS
 */

namespace User\Model\Acl\RoleAssignment;

/**
 * Description of Identity
 *
 * @category   Model
 * @package    User
 * @copyright  Copyright (c) 2009 Modo Design Group (http://mododesigngroup.com)
 * @version    $Id: GroupRoleAssignment.php 297 2010-05-12 13:34:56Z mike $
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