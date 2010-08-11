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
 * @version    $Id: UserRoleAssignment.php 297 2010-05-12 13:34:56Z mike $
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