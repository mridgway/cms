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
 * @version    $Id: AbstractRoleAssignment.php 297 2010-05-12 13:34:56Z mike $
 *
 * @Entity
 * @Table(name="RoleAssignment")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"Group" = "User\Model\Acl\RoleAssignment\GroupRoleAssignment", "User" = "User\Model\Acl\RoleAssignment\UserRoleAssignment"})
 *
 * @property int $id
 * @property Role $role
 */
class AbstractRoleAssignment extends \Modo\Orm\Model\AbstractModel
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