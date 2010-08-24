<?php

namespace User\Model\Acl\RoleAssignment;

/**
 * Representation of an acl role for a user
 *
 * @package     CMS
 * @subpackage  User
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity
 *
 * @property User\Model\User $user
 */
class UserRoleAssignment extends AbstractRoleAssignment
{
    /**
     * @var User\Model\User
     * @ManyToOne(targetEntity="User\Model\User")
     * @JoinColumn(name="user", referencedColumnName="id", nullable="false")
     */
    protected $user;

    /**
     * @param User $user
     * @param Role $role
     */
    public function __construct(\User\Model\User $user, \User\Model\Acl\Role $role)
    {
        $this->setUser($user);
        parent::__construct($role);
    }

    /**
     * @param User $user
     * @return UserRoleAssignment
     */
    public function setUser(\User\Model\User $user)
    {
        $this->user = $user;
        return $this;
    }
}