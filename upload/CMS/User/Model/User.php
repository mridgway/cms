<?php

namespace User\Model;

/**
 * Representation of a user in the system
 *
 * @package     CMS
 * @subpackage  User
 * @category    Model
 * @copyright   Copyright (c) 2009-2010 Modo Design Group (http://mododesigngroup.com)
 * @license     <license>
 *
 * @Entity
 * @Table(name="User")
 *
 * @property int $id
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property array $roleAssignments
 * @property array $roles
 */
class User extends \Core\Model\AbstractModel implements \Zend_Acl_Role_Interface
{
    /**
     * @var integer
     * @Id @Column(name="id", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @Column(type="string", name="email", nullable="false", length="150")
     */
    protected $email;

    /**
     * @var string
     * @Column(type="string", name="first_name", nullable="false", length="100")
     */
    protected $firstName;

    /**
     * @var string
     * @Column(type="string", name="last_name", nullable="false", length="100")
     */
    protected $lastName;

    /**
     * @var Group
     * @ManyToOne(targetEntity="User\Model\Group")
     * @JoinColumn(name="grp", referencedColumnName="id", nullable="false")
     */
    protected $group;

    /**
     * @var Acl\RoleAssignMent\UserRoleAssignment
     * @OneToMany(targetEntity="User\Model\Acl\RoleAssignment\UserRoleAssignment", mappedBy="user", cascade={"insert", "update", "delete"})
     */
    protected $roleAssignments;

    /**
     * @var array|null
     */
    protected $roles = null;

    /**
     * @var \Modo\Acl
     */
    protected $acl;

    /**
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct($group, $email, $firstName, $lastName)
    {
        $this->setGroup($group);
        $this->setEmail($email);
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setRoleAssignments(new \Doctrine\Common\Collections\ArrayCollection());
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $validator = new \Zend_Validate_EmailAddress();
        if (!$validator->isValid($email)) {
            throw new \Core\Model\Exception('Email address invalid.');
        }
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $firstName
     * @return string
     */
    public function setFirstName($firstName)
    {
        $validator = new \Zend_Validate_StringLength(1, 100);
        if (!$validator->isValid($firstName)) {
            throw new \Core\Model\Exception('FirstName must be between 1 and 100 characters');
        }
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param string $lastName
     * @return string
     */
    public function setLastName($lastName)
    {
        $validator = new \Zend_Validate_StringLength(1, 100);
        if (!$validator->isValid($lastName)) {
            throw new \Core\Model\Exception('LastName must be between 1 and 100 characters');
        }
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @param Group $group
     * @return User
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * Gets a list of roles for this user
     *
     * @param boolean $withGroup whether or not to include the user's group roles
     * @return array
     */
    public function getRoles($withGroup = true)
    {
        if (null === $this->roles) {
            $this->roles = array();
            foreach ($this->roleAssignments AS $roleAssignment) {
                $this->roles[] = $roleAssignment->role;
            }
            if ($withGroup) {
                $this->roles = array_unique(array_merge($this->roles, $this->getGroup()->getRoles()));
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
            $this->roleAssignments[] = new \User\Model\Acl\RoleAssignment\UserRoleAssignment($this, $role);
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

    /**
     * @return string
     */
    public function getRoleId()
    {
        return 'User.' . (isset($this->id)?$this->id:'Guest');
    }

    /**
     * @param mixed $resource
     * @param mixed $privilege
     */
    public function isAllowed($resource = null, $privilege = null)
    {
        if (!isset($this->acl)) {
            throw new \Exception('Acl not set on user.');
        }
        return $this->acl->isAllowed($this, $resource, $privilege);
    }

    /**
     * @param Zend_Acl $acl
     * @return User
     */
    public function setAcl(\Zend_Acl $acl)
    {
        $this->acl = $acl;
        return $this;
    }
}